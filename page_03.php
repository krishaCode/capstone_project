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

<script>
  const db = firebase.database();
  const tbody = document.getElementById("product-table-body");

  function loadTable() {
    db.ref("RACKS").once("value", snap => {
      tbody.innerHTML = '';
      const racks = snap.val();
      for (let key in racks) {
        const rack = racks[key];
        db.ref("PRODUCTS/" + rack.product_id).once("value", productSnap => {
          const product = productSnap.val();
          db.ref("WEIGHT/" + rack.rack_no).once("value", weightSnap => {
            const weightOnRack = weightSnap.val() ?? 0;
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${rack.rack_no}</td>
              <td>${rack.sensor_id}</td>
              <td>${product?.product_name ?? rack.product_id}</td>
              <td>${product?.product_weight ?? "N/A"}</td>
              <td>${weightOnRack}</td>
              <td>
                <button onclick="editItem('${key}')">Edit</button>
                <button onclick="deleteItem('${key}')">Delete</button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        });
      }
    });
  }

  function deleteItem(id) {
    if (confirm("Delete this entry?")) {
      db.ref("RACKS/" + id).remove().then(loadTable);
    }
  }

  function editItem(id) {
    window.location.href = "existing-p.php?edit=" + id;
  }

  loadTable();
</script>


</body>
</html>
