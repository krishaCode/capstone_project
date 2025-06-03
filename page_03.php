<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rack Items</title>
  <link rel="stylesheet" href="rack-item.css">
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
  <div class="container">
  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Rack Number</th>
          <th>Sensor ID</th>
          <th>Product</th>
          <th>Product Weight (Kg)</th>
          <th>Weight on Rack (Kg)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="product-table-body"></tbody>
    </table>
  </div>
</div>
<a href="dashboard.php"><button class="back-button">←</button></a>
<a href="existing-p.php"><button class="add-button">+</button></a>

</body>
</html>
