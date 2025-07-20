<?php
include 'firebase_config.php';

$success_message = '';
$error_message = '';

if (
    isset($_POST['Update_product']) && 
    isset($_POST['User_ID']) && isset($_POST['Sensor_ID']) &&
    isset($_POST['Rack_No']) && isset($_POST['Product_ID']) &&
    isset($_POST['Product_Name']) && isset($_POST['Weight_Level'])
) {
    $created_by = isset($_POST['created_by']) && !empty($_POST['created_by']) ? $_POST['created_by'] : 'admin';
    
    $newRackItem = [
        'User_ID' => $_POST['User_ID'],
        'Sensor_ID' => $_POST['Sensor_ID'],
        'Rack_No' => $_POST['Rack_No'],
        'Product_ID' => $_POST['Product_ID'],
        'Product_Name' => $_POST['Product_Name'],
        'Weight_Level' => $_POST['Weight_Level'],
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $created_by
    ];

    $url = FIREBASE_URL . 'rack-items.json?auth=' . FIREBASE_SECRET;
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/json",
            'content' => json_encode($newRackItem)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result !== false) {
        $success_message = "✅ Rack item successfully added to database!";
    } else {
        $error_message = "❌ Error adding rack item to database!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Product | Stockguard Management System Admin</title>

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
    
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    
    <!-- Main CSS File -->
    <link href="product-add.css" rel="stylesheet">
    
    <!-- Firebase SDKs for getting current user -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
</head>

<header>
    <div class="container">

        <h1 class="logo">
            <span id="logo-first">Stockguard</span>
            <span id="logo-last">Management System</span>
        </h1>

        <!-- i elements are for font awesome icons home, info, briefcase, envelope -->
        <nav class="site-nav">
            <ul>
                <li>
                    <a href="dashboard.php">
                        <i class="fa fa-home site-nav-icon"></i>Dashboard</a>
                </li>
                <li>
                    <a href="product-add.php">
                        <i class="fa fa-info site-nav-icon"></i>Product Add</a>
                </li>
                <li>
                    <a href="existing-product.php">
                        <i class="fa fa-briefcase site-nav-icon"></i>Existing Product</a>
               </li>
                <li>
                    <a href="products.php">
                        <i class="fa fa-envelope site-nav-icon"></i>Products</a>
                </li>
                <li>
                    <a href="rack-item.php">
                        <i class="fa fa-envelope site-nav-icon"></i>Rack Item</a>
                </li>
            </ul>
        </nav>

        <!-- Menu btn that toggles onClick and changes appearance ( .menu-toggle CSS ) -->
        <div class="menu-toggle">
            <div class="hamburger"></div>
        </div>
    </div>
</header>



    <main class="main">
        <div class="form-container" data-aos="fade-up">
            <div class="form-header">
                <h2><i class="bi bi-plus-circle"></i> Update Existing Product</h2>
                <p class="mb-0">Existing product , "View ,manage and update all your Existing product listings easily"</p>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success" id="successAlert">
                    <i class="bi bi-check-circle-fill"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error" id="errorAlert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-body">
                <form method="POST" enctype="multipart/form-data">
                    <!-- Hidden field for current user name -->
                    <input type="hidden" name="created_by" id="created_by" value="admin">

                     <div class="form-group">
                        <label for="User_ID" class="form-label">
                            <i class="bi bi-box-seam"></i> User ID
                        </label>
                        <input type="text" name="User_ID" id="User_ID" class="form-control" placeholder="Enter User ID..." required />
                    </div>

                    <div class="form-group">
                        <label for="Sensor_ID" class="form-label">
                            <i class="bi bi-box-seam"></i> Sensor ID
                        </label>
                        <input type="text" name="Sensor_ID" id="Sensor_ID" class="form-control" placeholder="Enter Sensor ID..." required />
                    </div>
                        
                        <div class="form-group">
                        <label for="Rack_No" class="form-label">
                            <i class="bi bi-box-seam"></i> Rack No
                        </label>
                        <input type="text" name="Rack_No" id="Rack_No" class="form-control" placeholder="Enter Rack No..." required />
                    </div>
                    
                    <div class="form-group">
                        <label for="Product_ID" class="form-label">
                            <i class="bi bi-box-seam"></i> Product ID
                        </label>
                        <input type="text" name="Product_ID" id="Product_ID" class="form-control" placeholder="Enter Product ID to auto-fill product name if it exists..." required onblur="checkProductID()" />
                        
                    </div>
                    
                   <div class="form-group">
                        <label for="Product_Name" class="form-label">
                            <i class="bi bi-box-seam"></i> Product Name
                        </label>
                        <input type="text" name="Product_Name" id="Product_Name" class="form-control" placeholder="Enter Product Name..." required />
                        <small id="product-status" class="form-text"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="Weight_Level" class="form-label">
                            <i class="bi bi-box-seam"></i> Low Weight Threshold(Kg)
                        </label>
                        <input type="text" name="Weight_Level" id="Weight_Level" class="form-control" placeholder="Enter Weight Level..." required />
                        <small class="form-text text-muted">Alert will be triggered when weight drops below this value</small>
                    </div>
                    
                    
                    <div class="text-center mt-4">
                        <button type="submit" name="Update_product" class="btn-submit">
                            <i class="bi bi-plus-circle"></i> Update Product
                        </button>
                    </div>
                    </form>
                </div>
            </div>
    </main>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    
    <!-- Main JS File -->
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyApZbf0d5o7EM-n6G43ZCca62z52masNz4",
            authDomain: "capstone-f46be.firebaseapp.com",
            databaseURL: "https://capstone-f46be-default-rtdb.firebaseio.com/",
            projectId: "capstone-f46be",
            storageBucket: "capstone-f46be.appspot.com",
            messagingSenderId: "631924495249",
            appId: "123456789"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const database = firebase.database();

        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'slide'
        });

        // Check authentication and set user name
        firebase.auth().onAuthStateChanged((user) => {
            if (user) {
                console.log('User authenticated:', user.uid);
                loadUserNameForForm(user.uid);
            } else {
                // Redirect to login if not authenticated
                window.location.href = '../login.php';
            }
        });

        function loadUserNameForForm(uid) {
            const adminRef = database.ref('admins/' + uid);
            adminRef.once('value').then((snapshot) => {
                if (snapshot.exists()) {
                    const adminData = snapshot.val();
                    const currentUser = firebase.auth().currentUser;
                    const userName = adminData.fullname || 
                                   (currentUser && currentUser.displayName) || 
                                   adminData.displayName || 
                                   adminData.name || 
                                   'Admin';
                    
                    // Set the hidden field value
                    document.getElementById('created_by').value = userName;
                    console.log('Set created_by field to:', userName);
                }
            }).catch((error) => {
                console.error('Error loading admin data:', error);
                // Keep default 'admin' value if error
            });
        }

        // Function to check if Product ID exists and auto-fill product name
        function checkProductID() {
            const productIDInput = document.getElementById('Product_ID');
            const productNameInput = document.getElementById('Product_Name');
            const statusElement = document.getElementById('product-status');
            const productID = productIDInput.value.trim();
            
            if (productID === '') {
                productNameInput.value = '';
                statusElement.innerHTML = '';
                return;
            }
            
            // Show loading message
            statusElement.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Checking Product ID...';
            statusElement.className = 'form-text text-info';
            
            // Fetch products from Firebase
            const productsRef = database.ref('products');
            productsRef.once('value').then((snapshot) => {
                let productFound = false;
                
                if (snapshot.exists()) {
                    const products = snapshot.val();
                    
                    // Search for matching product ID
                    Object.keys(products).forEach(key => {
                        const product = products[key];
                        if (product.product_ID && product.product_ID.toLowerCase() === productID.toLowerCase()) {
                            // Product found - auto-fill the name
                            productNameInput.value = product.product_name || '';
                            statusElement.innerHTML = '<i class="bi bi-check-circle-fill"></i> Product found! Name auto-filled.';
                            statusElement.className = 'form-text text-success';
                            productFound = true;
                            return;
                        }
                    });
                }
                
               if (!productFound) {
                    // Product not found
                    productNameInput.value = '';
                    statusElement.innerHTML = '<i class="bi bi-info-circle-fill"></i> Product ID not found. Please enter manually.';
                    statusElement.className = 'form-text text-warning';
                }
            }).catch((error) => {
                console.error('Error fetching products:', error);
                statusElement.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Error checking Product ID.';
                statusElement.className = 'form-text text-danger';
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 5000);
    </script>
    
    <style>
        .alert {
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert i {
            font-size: 1.2em;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Product ID Check Styles */
        .text-info {
            color: #17a2b8 !important;
        }
        
        .text-success {
            color: #28a745 !important;
        }
        
        .text-warning {
            color: #ffc107 !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        #product-status {
            font-weight: 500;
            margin-top: 5px;
        }
        
        #product-status i {
            margin-right: 5px;
        }
    </style>

<footer>
<div class="footer">
<div class="row">
<a href="#"><i class="fa fa-facebook"></i></a>
<a href="#"><i class="fa fa-instagram"></i></a>
<a href="#"><i class="fa fa-youtube"></i></a>
<a href="#"><i class="fa fa-twitter"></i></a>
</div>

<div class="row">
<ul>
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="product-add.php">Product Add</a></li>
<li><a href="existing-product.php">Existing Product</a></li>
<li><a href="products.php">Products</a></li>
<li><a href="rack-item.php">Rack Item</a></li>
<li><a href="contactus.php">Contact US</a></li>
</ul>
</div>

<div class="row">
 Stockguard Management System Copyright © 2025 - All rights reserved 
</div>
</div>
</footer>

</body>
</html>


                    
                