
<?php
require _DIR_ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$factory = (new Factory)
    ->withServiceAccount(_DIR_ . '/scale-8ca5f-firebase-adminsdk-fbsvc-d6280abfa4.json')
    ->withDatabaseUri('https://scale-8ca5f-default-rtdb.firebaseio.com/'); // ✅ Correct Firebase DB URL

$auth = $factory->createAuth();
$database = $factory->createDatabase();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($password) || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        try {
            $user = $auth->createUser([
                'email' => $email,
                'emailVerified' => false,
                'password' => $password,
                'displayName' => $username,
                'disabled' => false,
            ]);

            // Save extra data to Realtime DB
            $database->getReference('users/' . $user->uid)->set([
                'username' => $username,
                'email' => $email,
                'createdAt' => date('Y-m-d H:i:s')
            ]);

            // ✅ Redirect to login
            header('Location: login.php');
            exit();

        } catch (\Kreait\Firebase\Exception\Auth\EmailExists $e) {
            $errors[] = 'Email already exists.';
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        } catch (Exception $e) {
            $errors[] = 'An error occurred: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign-p.css">
</head>
<body>
<div class="signup-container">
    <h2>Sign Up</h2>

    <?php if (!empty($errors)): ?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>User Name:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <div class="login-link">Already have an account? <a href="login.php">Log In</a></div>
</div>
</body>
</html>