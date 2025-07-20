<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login | Stockguard Management System</title>

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="login.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
</head>
<body>

    <div class="login-container">
        <!-- Login Image/Branding Side -->
        <div class="login-image">
            <h2>Stockguard Management System</h2>
            <p>Welcome to the Stockguard Administration Panel</p>
            <p>Manage products, inventory, and system content efficiently</p>
        </div>

        <!-- Login Form Side -->
        <div class="login-form">
            <h2 class="form-title">Admin Login</h2>
            <p class="form-subtitle">Please sign in to access the dashboard</p>
            
            <!-- Error Message Container -->
            <div id="error-message" class="error-message" style="display: none;">
                <i class="bi bi-exclamation-triangle"></i> <span id="error-text"></span>
            </div>
            
            <!-- Success Message Container -->
            <div id="success-message" class="success-message" style="display: none; background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="bi bi-check-circle"></i> <span id="success-text"></span>
            </div>
            
            <!-- Loading Spinner -->
            <div id="loading-spinner" class="loading-spinner" style="display: none; text-align: center; margin-bottom: 20px;">
                <i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite; font-size: 24px; color: #4CAF50;"></i>
                <p style="margin-top: 10px; color: #666;">Signing in...</p>
            </div>
            
            <form id="loginForm">
                <div class="form-group">
                    <i class="bi bi-envelope form-icon"></i>
                    <input type="email" name="email" id="email" class="form-control" 
                           placeholder="Enter your email address" required />
                </div>
                
                <div class="form-group">
                    <i class="bi bi-lock form-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="Enter your password" required />
                </div>
                
                <button type="submit" id="signin-btn" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Log In
                </button>
            </form>
            
            <div class="back-link">
                <a href="signup.php">
                    <i class="bi bi-arrow-left"></i> Back to Sign In
                </a>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Firebase Configuration and Authentication Script -->
    <script>
        // Firebase configuration (Replace with your actual Firebase config)
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

        // Ensure user starts with a clean session
        auth.signOut().then(() => {
            console.log('User signed out for fresh login');
        }).catch((error) => {
            console.log('No user was signed in');
        });

        // Check for registration success message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('registered') === 'success') {
            showSuccess('Registration successful! Please login with your credentials.');
        }

        // Function to check admin status in Realtime Database
        function checkAdminStatus(user) {
            const adminRef = database.ref('admins/' + user.uid);
            const userRef = database.ref('users/' + user.uid);
            
            // First check if user is in admins collection
            adminRef.once('value').then((snapshot) => {
                if (snapshot.exists()) {
                    const adminData = snapshot.val();
                    if (adminData.role === 'admin' && adminData.status === 'active') {
                        // Update last login
                        adminRef.update({
                            lastLogin: firebase.database.ServerValue.TIMESTAMP,
                            lastLoginIP: getUserIP()
                        });
                        
                        // Redirect to admin dashboard
                        showSuccess('Login successful! Redirecting to admin dashboard...');
                        setTimeout(() => {
                            window.location.href = 'admin/dashboard.php';
                        }, 1500);
                    } else if (adminData.status === 'pending') {
                        showError('Your admin account is pending approval. Please wait for activation.');
                        auth.signOut();
                    } else {
                        showError('Access denied. Your admin account is not active.');
                        auth.signOut();
                    }
                } else {
                    // Check if user is in regular users collection
                    userRef.once('value').then((userSnapshot) => {
                        if (userSnapshot.exists()) {
                            const userData = userSnapshot.val();
                            if (userData.status === 'active') {
                                // Update last login
                                userRef.update({
                                    lastLogin: firebase.database.ServerValue.TIMESTAMP,
                                    lastLoginIP: getUserIP()
                                });
                                
                                // Redirect based on user role
                                showSuccess('Login successful! Redirecting to your dashboard...');
                                setTimeout(() => {
                                    switch(userData.role) {
                                        case 'seller':
                                            window.location.href = '../seller/dashboard.php';
                                            break;
                                        case 'buyer':
                                            window.location.href = '../buyer/dashboard.php';
                                            break;
                                        default:
                                            window.location.href = '../shared/index.php';
                                    }
                                }, 1500);
                            } else {
                                showError('Your account is not active. Please contact support.');
                                auth.signOut();
                            }
                        } else {
                            showError('Account not found. Please register first.');
                            auth.signOut();
                        }
                    });
                }
            }).catch((error) => {
                showError('Error checking account status: ' + error.message);
                auth.signOut();
            });
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            // Validate input
            if (!email || !password) {
                showError('Please enter both email and password.');
                return;
            }
            
            // Show loading spinner
            showLoading(true);
            hideMessages();

            // Sign in with Firebase Auth only after user submits form
            auth.signInWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    const user = userCredential.user;
                    
                    // Check user/admin status and redirect appropriately
                    checkAdminStatus(user);
                })
                .catch((error) => {
                    showLoading(false);
                    handleAuthError(error);
                });
        });

        // Function to handle authentication errors
        function handleAuthError(error) {
            let errorMessage = '';
            
            switch (error.code) {
                case 'auth/user-not-found':
                    errorMessage = 'No admin account found with this email address.';
                    break;
                case 'auth/wrong-password':
                    errorMessage = 'Incorrect password. Please try again.';
                    break;
                case 'auth/invalid-email':
                    errorMessage = 'Please enter a valid email address.';
                    break;
                case 'auth/user-disabled':
                    errorMessage = 'This admin account has been disabled.';
                    break;
                case 'auth/too-many-requests':
                    errorMessage = 'Too many failed attempts. Please try again later.';
                    break;
                case 'auth/network-request-failed':
                    errorMessage = 'Network error. Please check your connection.';
                    break;
                default:
                    errorMessage = 'Login failed: ' + error.message;
            }
            
            showError(errorMessage);
        }

        // Utility functions for UI feedback
        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorText.textContent = message;
            errorDiv.style.display = 'block';
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('success-message');
            const successText = document.getElementById('success-text');
            successText.textContent = message;
            successDiv.style.display = 'block';
        }

        function showLoading(show) {
            const loadingDiv = document.getElementById('loading-spinner');
            const submitBtn = document.getElementById('signin-btn');
            
            if (show) {
                loadingDiv.style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            } else {
                loadingDiv.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            }
        }

        function hideMessages() {
            document.getElementById('error-message').style.display = 'none';
            document.getElementById('success-message').style.display = 'none';
        }

        // Function to get user IP (simplified)
        function getUserIP() {
            // You can implement IP detection or use a service
            return 'Unknown';
        }

        // Add CSS for loading animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>

</body>
</html>
