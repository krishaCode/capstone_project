document.getElementById("productForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const db = firebase.database();
  const productId = document.getElementById("product_id").value;
  const productData = {
    product_name: document.getElementById("product").value,
    product_weight: parseFloat(document.getElementById("weight").value),
    user_id: document.getElementById("userId").value
  };

  db.ref("PRODUCTS/" + productId).set(productData)
    .then(() => {
      alert("Product saved.");
      document.getElementById("productForm").reset();
    })
    .catch((error) => {
      alert("Error: " + error.message);
    });
});