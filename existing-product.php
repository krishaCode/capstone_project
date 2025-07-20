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

                    
                