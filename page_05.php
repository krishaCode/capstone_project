
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Existing Product</title>
  <link rel="stylesheet" href="Existing-p.css">
  <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
  <script>
    const firebaseConfig = {
      apiKey: "YOUR_API_KEY",
      authDomain: "YOUR_PROJECT.firebaseapp.com",
      databaseURL: "https://YOUR_PROJECT.firebaseio.com",
      projectId: "YOUR_PROJECT",
      storageBucket: "YOUR_PROJECT.appspot.com",
      messagingSenderId: "SENDER_ID",
      appId: "APP_ID"
    };
    firebase.initializeApp(firebaseConfig);
  </script>
</head>
<body>
  <div class="form-container">
  <h2>Existing Product</h2>
  <form id="rackForm">
    <input type="text" id="userId" placeholder="User ID" required>
    <input type="text" id="sensorId" placeholder="Sensor ID" required>
    <input type="text" id="rackNo" placeholder="Rack No" required>
    <input type="text" id="product_id" placeholder="Product ID" required>
    <input type="number" id="weight_level" placeholder="Weight Level (Kg)" required>
    <button type="submit">Submit</button>
  </form>
  </div>
  
  <script>
    const db = firebase.database();
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit');

    if (editId) {
      db.ref("RACKS/" + editId).once("value", (snap) => {
        const data = snap.val();
        document.getElementById("userId").value = data.user_id;
        document.getElementById("sensorId").value = data.sensor_id;
        document.getElementById("rackNo").value = data.rack_no;
        document.getElementById("product_id").value = data.product_id;
        document.getElementById("weight_level").value = data.weight_level;
      });
    }

    document.getElementById("rackForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const rackData = {
        user_id: document.getElementById("userId").value,
        sensor_id: document.getElementById("sensorId").value,
        rack_no: document.getElementById("rackNo").value,
        product_id: document.getElementById("product_id").value,
        weight_level: parseFloat(document.getElementById("weight_level").value)
      };

      if (editId) {
        db.ref("RACKS/" + editId).set(rackData).then(() => location.href = "rack-item.php");
      } else {
        db.ref("RACKS").push(rackData).then(() => location.href = "rack-item.php");
      }
    });
  </script>
</body>
</html>

css-
* {
    box-sizing: border-box;
  }
  
  body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #ffffff;
     display: flex;
    height: 100vh;
    align-items: center;
    justify-content: center;
    background: url('background.jpg') no-repeat center center fixed;
    background-size: cover;
  }
  
  
  .form-container {
    position: relative;
    width: 350px;
    height: fit-content;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    text-align: left;
    background: none; /* remove direct background */
    z-index: 1; /* keep above the background layer */
}

/* Background image layer */
.form-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('form-background.jpg') no-repeat center center;
    background-size: cover;
    opacity: 0.3; /* lower opacity for background only */
    z-index: 0;
    pointer-events: none; /* allows clicks to pass through */
}
  
  .form-container h2 {
    color: white;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: bold;
    text-shadow: 1px 1px 4px #000;
    text-align: center;
  }
  
  form input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 10px;
    border: none;
    font-size: 14px;
  }
  
  form button {
    width: 100%;
    padding: 10px;
    background-color: #5e6df7;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
  }
  
  form button:hover {
    background-color: #4a58d4;
  }
