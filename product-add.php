

<?php
include 'firebase_config.php';

$success_message = '';
$error_message = '';

if (
    isset($_POST['product_ID']) && isset($_POST['product_name']) &&
    isset($_POST['User_ID']) && isset($_POST['Weight'])
) {
    $created_by = isset($_POST['created_by']) && !empty($_POST['created_by']) ? $_POST['created_by'] : 'admin';
    
    $newProduct = [
        'product_ID' => $_POST['product_ID'],
        'product_name' => $_POST['product_name'],
        'User_ID' => $_POST['User_ID'],
        'Weight' => $_POST['Weight'],
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $created_by
    ];

    $url = FIREBASE_URL . 'products.json?auth=' . FIREBASE_SECRET;
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/json",
            'content' => json_encode($newProduct)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result !== false) {
        $success_message = "✅ Product successfully added to database!";
    } else {
        $error_message = "❌ Error adding product to database!";
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
    
    <!--FONT AWESOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
    
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
    
    <!-- Main CSS File -->
    <link href="product-add.css" rel="stylesheet">
    
   
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
                <h2><i class="bi bi-plus-circle"></i> Add New Product</h2>
                <p class="mb-0">Add new product, "Easily add a new product to your online store today"</p>
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
                        <label for="product_ID" class="form-label">
                            <i class="bi bi-box-seam"></i> Product ID
                        </label>
                        <input type="text" name="product_ID" id="product_ID" class="form-control" placeholder="Enter product ID..." required />
                    </div>

                    <div class="form-group">
                        <label for="product_name" class="form-label">
                            <i class="bi bi-box-seam"></i> Product Name
                        </label>
                        <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name..." required />
                    </div>
                        
                        <div class="form-group">
                        <label for="User_ID" class="form-label">
                            <i class="bi bi-box-seam"></i> User ID
                        </label>
                        <input type="text" name="User_ID" id="User_ID" class="form-control" placeholder="Enter User ID..." required />
                    </div>
                    
                    <div class="form-group">
                        <label for="Weight" class="form-label">
                            <i class="bi bi-box-seam"></i> Weight(Kg/g)
                        </label>
                        <input type="text" name="Weight" id="Weight" class="form-control" placeholder="Enter Weight..." required />
                    </div>
                    
                   
                    
                    <div class="text-center mt-4">
                        <button type="submit" name="add_product" class="btn-submit" id="addProductBtn">
                            <i class="bi bi-plus-circle"></i> Add Product
                        </button>
                    </div>
                </form>
                </div>
            </div>
    </main>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>
    
    <!-- Main JS File -->
    <script src="../assets/js/main.js"></script>
    
    <!-- Firebase SDKs for getting current user -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    
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


