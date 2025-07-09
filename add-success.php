<?php
session_start();
include 'firebase_config.php';

if (
    isset($_POST['rackNo']) && isset($_POST['product']) &&
    isset($_POST['quantity']) && isset($_POST['userId']) &&
    isset($_POST['weight'])
) {
    // Set timezone and current date/time
    date_default_timezone_set('Asia/Kolkata'); // Change to your timezone as needed
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $_SESSION['product_added_date'] = $date;
    $_SESSION['product_added_time'] = $time;

    $data = [
        'rackNo' => $_POST['rackNo'],
        'product' => $_POST['product'],
        'quantity' => $_POST['quantity'],
        'userId' => $_POST['userId'],
        'weight' => $_POST['weight'],
        'addedDate' => $date,
        'addedTime' => $time
    ];

    $url = FIREBASE_URL . 'products.json?auth=' . FIREBASE_SECRET;
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/json",
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
}

$date = $_SESSION['product_added_date'] ?? 'Unknown Date';
$time = $_SESSION['product_added_time'] ?? 'Unknown Time';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Success</title>
    <link rel="stylesheet" href="add-success.css">
</head>
<body>
    <div class="first_s"><br>
        <div class="message">Added Product Successfully!</div><br><br>
        <div class="date">Date: <?php echo htmlspecialchars($date); ?></div><br><br>
        <div class="time">Time: <?php echo htmlspecialchars($time); ?></div><br><br>
        <div class="messsage_l">Data has been recorded</div><br><br>
        <div class="product">Go to check items <a href="products.php" style="color: #007bff; text-decoration: underline;">View Products</a></div>
    </div>
</body>
</html>
