<?php
include 'firebase_config.php';

$success_message = '';
$error_message = '';

// Handle edit request
if (isset($_POST['edit_product']) && isset($_POST['firebase_key'])) {
    $firebase_key = $_POST['firebase_key'];
    $updated_by = isset($_POST['updated_by']) && !empty($_POST['updated_by']) ? $_POST['updated_by'] : 'admin';
    
    $updated_product = [
        'product_ID' => $_POST['product_ID'],
        'product_name' => $_POST['product_name'],
        'User_ID' => $_POST['User_ID'],
        'Weight' => $_POST['Weight'],
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $updated_by
    ];
    
    $update_url = FIREBASE_URL . 'products/' . $firebase_key . '.json?auth=' . FIREBASE_SECRET;
    $options = [
        'http' => [
            'method'  => 'PATCH',
            'header'  => "Content-type: application/json",
            'content' => json_encode($updated_product)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($update_url, false, $context);
    
    if ($result !== false) {
        $success_message = "✅ Product updated successfully!";
    } else {
        $error_message = "❌ Error updating product!";
    }
}

// Handle delete request
if (isset($_POST['delete_product']) && isset($_POST['firebase_key'])) {
    $firebase_key = $_POST['firebase_key'];
    $delete_url = FIREBASE_URL . 'products/' . $firebase_key . '.json?auth=' . FIREBASE_SECRET;
    
    $options = [
        'http' => [
            'method'  => 'DELETE',
            'header'  => "Content-type: application/json"
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($delete_url, false, $context);
    
    if ($result !== false) {
        $success_message = "✅ Product deleted successfully!";
    } else {
        $error_message = "❌ Error deleting product!";
    }
}

// Fetch products from Firebase
$products = [];
$url = FIREBASE_URL . 'products.json?auth=' . FIREBASE_SECRET;
$response = file_get_contents($url);

if ($response !== false) {
    $data = json_decode($response, true);
    if ($data) {
        foreach ($data as $key => $product) {
            $product['firebase_key'] = $key;
            $products[] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Stockguard Management System Admin</title>
    
    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,909&display=swap" rel="stylesheet">
    
    <!--FONT AWESOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
    
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="products.css" rel="stylesheet">
    
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

<div class="main-content">
        <div class="container">
            
            <!-- Page Header -->
            <div class="page-header" style="display: flex; align-items: center; justify-content: flex-start; gap: px; padding: 20px 0;">
                <div class="first" style="flex: 1; margin-left: 30px;">
                    <h2><i class="bi bi-people"></i> Products</h2>
                    <p class="mb-0 text-muted">Manage all Products in the system</p>
                </div>
                <div class="second" style="display: flex; align-items: center; margin-right: 30px;">
                    <a href="product-add.php" class="btn-admin">
                        <i class="bi bi-person-plus"></i> Add New Product
                    </a>
                </div>
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

            <!-- Products Grid -->
            <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin: 30px 0; padding: 0 30px;">
                <?php if (empty($products)): ?>
                    <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                        <i class="bi bi-box" style="font-size: 3em; margin-bottom: 20px;"></i>
                        <h3>No Products Found</h3>
                        <p>Start by adding your first product!</p>
                        <a href="product-add.php" class="btn-admin">
                            <i class="bi bi-plus-circle"></i> Add Product
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-header">
                                <div class="product-id">
                                    <span class="id-label">ID:</span>
                                    <span class="id-value"><?php echo htmlspecialchars($product['product_ID']); ?></span>
                                </div>
                                <div class="product-actions">
                                    <button class="action-btn edit-btn" title="Edit Product" onclick="editProduct('<?php echo $product['firebase_key']; ?>', '<?php echo htmlspecialchars($product['product_ID']); ?>', '<?php echo htmlspecialchars($product['product_name']); ?>', '<?php echo htmlspecialchars($product['User_ID']); ?>', '<?php echo htmlspecialchars($product['Weight']); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Delete Product" onclick="deleteProduct('<?php echo $product['firebase_key']; ?>', '<?php echo htmlspecialchars($product['product_name']); ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                
                                <div class="product-details">
                                    <div class="detail-item">
                                        <i class="bi bi-person"></i>
                                        <span class="detail-label">User ID:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($product['User_ID']); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <i class="bi bi-weight"></i>
                                        <span class="detail-label">Weight:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($product['Weight']); ?></span>
                                    </div>
                                    
                                    <?php if (isset($product['created_at'])): ?>
                                        <div class="detail-item">
                                            <i class="bi bi-calendar"></i>
                                            <span class="detail-label">Created:</span>
                                            <span class="detail-value"><?php echo date('M j, Y', strtotime($product['created_at'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($product['created_by'])): ?>
                                        <div class="detail-item">
                                            <i class="bi bi-person-check"></i>
                                            <span class="detail-label">By:</span>
                                            <span class="detail-value" data-created-by="<?php echo htmlspecialchars($product['created_by']); ?>">
                                                <?php echo htmlspecialchars($product['created_by']); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<!-- Edit Product Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="bi bi-pencil-square"></i> Edit Product</h3>
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            <input type="hidden" name="edit_product" value="1">
            <input type="hidden" id="edit_firebase_key" name="firebase_key" value="">
            <input type="hidden" id="edit_updated_by" name="updated_by" value="admin">
            
            <div class="form-group">
                <label for="edit_product_ID">Product ID:</label>
                <input type="text" id="edit_product_ID" name="product_ID" required>
            </div>
            
            <div class="form-group">
                <label for="edit_product_name">Product Name:</label>
                <input type="text" id="edit_product_name" name="product_name" required>
            </div>
            
            <div class="form-group">
                <label for="edit_user_ID">User ID:</label>
                <input type="text" id="edit_user_ID" name="User_ID" required>
            </div>
            
            <div class="form-group">
                <label for="edit_weight">Weight:</label>
                <input type="text" id="edit_weight" name="Weight" required>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="delete_product" value="1">
    <input type="hidden" id="delete_firebase_key" name="firebase_key" value="">
</form>

<!-- Card Styling -->
<style>
    .product-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 24px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e1e5e9;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }
    
    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .product-id {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .id-label {
        color: #6c757d;
        font-weight: 500;
    }
    
    .id-value {
        color: #2c3e50;
        font-weight: 600;
        margin-left: 5px;
    }
    
    .product-actions {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 10px;
        cursor: pointer;
        transition: all 0.2s;
        color: #6c757d;
    }
    
    .edit-btn:hover {
        background: #17a2b8;
        color: white;
        border-color: #17a2b8;
    }
    
    .delete-btn:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    
    .product-name {
        color: #2c3e50;
        margin: 0 0 20px 0;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
    }
    
    .product-details {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 8px;
    }
    
    .detail-item i {
        color: #17a2b8;
        width: 16px;
    }
    
    .detail-label {
        font-weight: 500;
        min-width: 70px;
    }
    
    .detail-value {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .no-products {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .btn-admin {
        background: #399fde;
        color: (135deg, #52a1d2, #399fde);;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-admin:hover {
        background: #1d6ea1ff;
        color: white;
        text-decoration: none;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: 1fr !important;
            padding: 0 15px !important;
        }
        
        .page-header {
            flex-direction: column !important;
            gap: 15px !important;
        }
        
        .page-header .first,
        .page-header .second {
            margin: 0 !important;
            text-align: center;
        }
    }
    
    /* Alert Messages */
    .alert {
        padding: 15px 20px;
        margin: 20px 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
    }
    
    .alert-success {
        background: #d4edda;
        color: #2999d6ff;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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
    
    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 0;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: modalIn 0.3s ease-out;
    }
    
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }
    
    .modal-header h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6c757d;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }
    
    .close-btn:hover {
        background: #e9ecef;
        color: #495057;
    }
    
    .form-group {
        margin-bottom: 20px;
        padding: 0 25px;
    }
    
    .form-group:first-of-type {
        margin-top: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #17a2b8;
        box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
    }
    
    .form-actions {
        padding: 20px 25px;
        border-top: 1px solid #dee2e6;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        background: #467eb6ff;
        border-radius: 0 0 12px 12px;
    }
    
    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .btn-cancel:hover {
        background: #5a6268;
    }
    
    .btn-save {
        background: (135deg, #52a1d2, #399fde);;
        color: #399fde;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-save:hover {
        background: (135deg, #52a1d2, #399fde);;
    }
</style>

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

    // Check authentication and update user names
    firebase.auth().onAuthStateChanged((user) => {
        if (user) {
            console.log('User authenticated:', user.uid);
            loadUserNameAndUpdate(user.uid);
        } else {
            // Redirect to login if not authenticated
            window.location.href = '../login.php';
        }
    });

    function loadUserNameAndUpdate(uid) {
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
                
                // Store user name globally for use in edit form
                window.currentUserName = userName;
                
                // Update all "By: admin" text to show actual user name
                updateCreatedByText(userName);
                
                // Set the hidden field in edit form if it exists
                const editUpdatedByField = document.getElementById('edit_updated_by');
                if (editUpdatedByField) {
                    editUpdatedByField.value = userName;
                }
            }
        }).catch((error) => {
            console.error('Error loading admin data:', error);
        });
    }

    function updateCreatedByText(userName) {
        // Find all elements with created_by data attribute
        const createdByElements = document.querySelectorAll('[data-created-by]');
        createdByElements.forEach(element => {
            const originalValue = element.getAttribute('data-created-by');
            if (originalValue === 'admin') {
                element.textContent = userName;
            }
        });
    }

    function editProduct(firebaseKey, productID, productName, userID, weight) {
        // Fill the form with current product data
        document.getElementById('edit_firebase_key').value = firebaseKey;
        document.getElementById('edit_product_ID').value = productID;
        document.getElementById('edit_product_name').value = productName;
        document.getElementById('edit_user_ID').value = userID;
        document.getElementById('edit_weight').value = weight;
        
        // Show the modal
        document.getElementById('editModal').style.display = 'flex';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
    
    function deleteProduct(firebaseKey, productName) {
        if (confirm(`Are you sure you want to delete "${productName}"?\n\nThis action cannot be undone.`)) {
            document.getElementById('delete_firebase_key').value = firebaseKey;
            document.getElementById('deleteForm').submit();
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
    
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

