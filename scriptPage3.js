const products = [
    {
      rack: "01",
      sensor: "01",
      img: "images/image_01.jpeg", 
      weight: "50g",
      percentage: "75%",
    },
    {
      rack: "02",
      sensor: "02",
      img: "images/image_02.jpeg",
      weight: "80g",
      percentage: "67%",
    },
    {
      rack: "03",
      sensor: "03",
      img: "images/image_03.jpeg", 
      weight: "80g",
      percentage: "59%",
    }
  ];
  
  const tableBody = document.getElementById("product-table-body");
  
  products.forEach(product => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${product.rack}</td>
      <td>${product.sensor}</td>
      <td><img src="${product.img}" alt="Product"></td>
      <td>${product.weight}</td>
      <td>${product.percentage}</td>
      <td><span class="status-dot"></span></td>
    `;
    tableBody.appendChild(row);
  });
  