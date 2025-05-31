<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$factory = (new Factory)
    ->withServiceAccount(_DIR_ . '/scale-8ca5f-firebase-adminsdk-fbsvc-d6280abfa4.json')
    ->withDatabaseUri('https://scale-8ca5f-default-rtdb.firebaseio.com/');
$auth = $factory->createAuth();
$error = '';
