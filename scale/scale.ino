#include <Arduino.h>
#define ENABLE_USER_AUTH
#define ENABLE_DATABASE
#include <WiFi.h>
#include <time.h>
#include <WiFiClientSecure.h>
#include <FirebaseClient.h>
#include "HX711.h"

// Your Firebase project API Key
#define Web_API_KEY     "AIzaSyApZbf0d5o7EM-n6G43ZCca62z52masNz4"
#define DATABASE_URL    "https://capstone-f46be-default-rtdb.firebaseio.com/"
#define USER_EMAIL      "invenguard@gmail.com"
#define USER_PASS       "Admin@1234"

// Forward-declare the callback
void processData(AsyncResult & aResult);

// Authentication
UserAuth user_auth(Web_API_KEY, USER_EMAIL, USER_PASS,3000);

// Firebase components
FirebaseApp app;
WiFiClientSecure ssl_client;
using AsyncClient = AsyncClientClass;
AsyncClient aClient(ssl_client);
RealtimeDatabase Database;

// HX711 pins & calibration
#define LOADCELL_DOUT_PIN1   5
#define LOADCELL_SCK_PIN1    4
#define LOADCELL_DOUT_PIN2   18
#define LOADCELL_SCK_PIN2    0
#define calibration_factor  -230

HX711 scale1, scale2;
float scaleValue = 0.01;

// Timers
unsigned long lastScaleReadTime = 0;
unsigned long lastTimeUpdate    = 0;
const long scaleInterval        = 2000;
const long timeUpdateInterval   = 10000;

// Wi-Fi & NTP
const char* ssid            = "Dialog 4G 036";
const char* password        = "Krish@2002";
const char* ntpServer       = "pool.ntp.org";
const long  gmtOffset_sec   = 0;
const int   daylightOffset_sec = 0;

void setup() {
  Serial.begin(115200);
  delay(500);

  // Init scales
  scale1.begin(LOADCELL_DOUT_PIN1, LOADCELL_SCK_PIN1);
  scale2.begin(LOADCELL_DOUT_PIN2, LOADCELL_SCK_PIN2);
  scale1.set_scale(calibration_factor);
  scale2.set_scale(calibration_factor);
  scale1.tare(); scale2.tare();
  Serial.println("Scales initialized");

  // Wi-Fi
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  int to = 0;
  while (WiFi.status() != WL_CONNECTED && to++ < 50) {
    delay(500); Serial.print(".");
  }
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi connected");
    Serial.print("IP: "); Serial.println(WiFi.localIP());
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    printUTCTime();
  } else {
    Serial.println("\nWiFi failed – running offline");
  }

  // Firebase init
  ssl_client.setInsecure();
  ssl_client.setConnectionTimeout(1000);
  ssl_client.setHandshakeTimeout(5);
  initializeApp(aClient, app, getAuth(user_auth), processData, "authTask");
  app.getApp<RealtimeDatabase>(Database);
  Database.url(DATABASE_URL);
}

void loop() {
  unsigned long now = millis();

  // Periodic NTP + reconnect
  if (now - lastTimeUpdate >= timeUpdateInterval) {
    lastTimeUpdate = now;
    if (WiFi.status() == WL_CONNECTED) {
      printUTCTime();
    } else {
      Serial.println("WiFi lost – reconnecting...");
      WiFi.begin(ssid, password);
    }
  }

  if (WiFi.status() == WL_CONNECTED) {
    app.loop();  // pump Firebase auth/tasks
    if (now - lastScaleReadTime >= scaleInterval) {
      lastScaleReadTime = now;
      readScales();
    }
  }
}

void readScales() {
  float r1 = scale1.get_units();
  float r2 = scale2.get_units();
  scaleValue = ((r1 + r2) * (-1) )/ 1000.0;  // to kg

  Serial.printf("S1: %.1fg  S2: %.1fg  Total: %.3fkg\n", r1, r2, scaleValue);
  Database.set<float>(aClient, "/WEIGHT/Value", scaleValue, processData, "RTDB_Send_Float");
}

void printUTCTime() {
  struct tm ti;
  if (!getLocalTime(&ti)) {
    Serial.println("Failed to get time");
    return;
  }
  char buf[32];
  strftime(buf, sizeof(buf), "%Y-%m-%d %H:%M:%S", &ti);
  Serial.print("UTC Time: ");
  Serial.println(buf);
  Serial.println("User: kushan-abeysekara");
}

// ——— Correct AsyncResult handler ———
void processData(AsyncResult & aResult) {
  // Only handle real results
  if (!aResult.isResult())
    return;

  // Event (e.g. stream event)
  if (aResult.isEvent())
    Firebase.printf("Event task: %s, msg: %s, code: %d\n",
                    aResult.uid().c_str(),
                    aResult.eventLog().message().c_str(),
                    aResult.eventLog().code());

  // Debug messages
  if (aResult.isDebug())
    Firebase.printf("Debug task: %s, msg: %s\n",
                    aResult.uid().c_str(),
                    aResult.debug().c_str());

  // Errors
  if (aResult.isError())
    Firebase.printf("Error task: %s, msg: %s, code: %d\n",
                    aResult.uid().c_str(),
                    aResult.error().message().c_str(),
                    aResult.error().code());

  // Successful payloads
  if (aResult.available())
    Firebase.printf("Completed task: %s, payload: %s\n",
                    aResult.uid().c_str(),
                    aResult.c_str());
}
