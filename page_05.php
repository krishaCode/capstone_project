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

</body>
</html>
