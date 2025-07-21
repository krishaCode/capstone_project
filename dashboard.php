<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Stockguard Management System</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    
    
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>

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

        
    </div>
</header>


    <main class="main">
        <div class="form-container" data-aos="fade-up">
              <div class="main-content">
        <div class="login-container">
            <div class="login-image">
                <h2>Stockguard Management System</h2>
                <p>Admin Dashboard</p>
                <p>Welcome to the administration panel</p>
            </div>

        <div class="login-form">
            <h2 class="form-title">Dashboard</h2>
            <p class="form-subtitle">Welcome, <span id="admin-name">Admin</span>!</p>
            
            <div style="text-align: center; margin: 40px 0;">
                <button class="btn-login" style="margin: 10px 0; width: 100%;" onclick="window.location.href='product-add.php'">
                    <i class="bi bi-people"></i> Add New Product
                </button>
                <button class="btn-login" style="margin: 10px 0; width: 100%;" onclick="window.location.href='existing-product.php'">
                    <i class="bi bi-people"></i> Updating Existing Product
                </button>
                <button class="btn-login" style="margin: 10px 0; width: 100%;" onclick="window.location.href='products.php'">
                    <i class="bi bi-cart"></i> Check Product
                </button>
                <button class="btn-login" style="margin: 10px 0; width: 100%;" onclick="window.location.href='rack-item.php'">
                    <i class="bi bi-person-gear"></i> Check Rack Item
                </button>
                <button class="btn-login" style="margin: 10px 0; width: 100%; background: #dc3545;" onclick="logout()">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>
        </div>
    </div>
            </div>
    </main>


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

        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const database = firebase.database();

        // Check authentication status
        firebase.auth().onAuthStateChanged((user) => {
            if (user) {
                console.log('User authenticated:', user.uid, 'Display Name:', user.displayName);
                // Load admin data
                loadAdminData(user.uid);
            } else {
                // Redirect to login if not authenticated
                window.location.href = '../login.php';
            }
        });
        
        function loadAdminData(uid) {
            const adminRef = database.ref('admins/' + uid);
            adminRef.once('value').then((snapshot) => {
                if (snapshot.exists()) {
                    const adminData = snapshot.val();
                    console.log('Admin data loaded:', adminData); // Debug log
                    
                    // Use fullname from signup data, fallback to displayName from auth, or Admin
                    const currentUser = firebase.auth().currentUser;
                    const userName = adminData.fullname || 
                                   (currentUser && currentUser.displayName) || 
                                   adminData.displayName || 
                                   adminData.name || 
                                   'Admin';
                    console.log('Setting user name to:', userName); // Debug log
                    
                    document.getElementById('admin-name').textContent = userName;
                } else {
                    console.log('No admin data found for UID:', uid); // Debug log
                    // Try to use display name from auth if database has no data
                    const currentUser = firebase.auth().currentUser;
                    if (currentUser && currentUser.displayName) {
                        document.getElementById('admin-name').textContent = currentUser.displayName;
                    } else {
                        alert('Admin data not found. Please contact support.');
                        logout();
                    }
                }
            }).catch((error) => {
                console.error('Error loading admin data:', error);
                // Fallback to display name from auth or default
                const currentUser = firebase.auth().currentUser;
                const fallbackName = (currentUser && currentUser.displayName) || 'Admin';
                document.getElementById('admin-name').textContent = fallbackName;
            });
        }

        function logout() {
            auth.signOut().then(() => {
                window.location.href = '../login.php';
            }).catch((error) => {
                console.error('Error signing out:', error);
            });
        }
    </script>

    
</body>
</html>

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

