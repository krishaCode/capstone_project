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
