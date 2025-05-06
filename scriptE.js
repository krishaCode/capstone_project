document.getElementById("productForm").addEventListener("submit", function(e) {
    e.preventDefault();
  
    const userId = document.getElementById("userId").value;
    const sensorId = document.getElementById("sensorId").value;
    const rackNo = document.getElementById("rackNo").value;
    const current = parseFloat(document.getElementById("currentPercentage").value);
    const added = parseFloat(document.getElementById("newPercentage").value);
  
    const total = current + added;
  
    alert(`User ID: ${userId}\nSensor ID: ${sensorId}\nRack No: ${rackNo}\nUpdated Total: ${total}%`);
  });
  
  