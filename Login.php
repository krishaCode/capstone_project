<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$factory = (new Factory)
    ->withServiceAccount(_DIR_ . '/scale-8ca5f-firebase-adminsdk-fbsvc-d6280abfa4.json')
    ->withDatabaseUri('https://scale-8ca5f-default-rtdb.firebaseio.com/');
$auth = $factory->createAuth();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $signInResult = $auth->signInWithEmailAndPassword($email, $password);
            $firebaseUser = $auth->getUserByEmail($email);

            session_start();
            $_SESSION['user_id'] = $firebaseUser->uid;
            $_SESSION['email'] = $firebaseUser->email;

            header("Location: dashboard.php");
            exit;

        } catch (\Kreait\Firebase\Exception\Auth\AuthError $e) {
            $error = 'Invalid email or password.';
        } catch (Exception $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Log In</h2>
