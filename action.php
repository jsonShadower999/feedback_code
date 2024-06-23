<?php
session_start();
require_once 'config.php';

$post_action = isset($_POST['action']) ? $_POST['action'] : null;

if (!is_null($post_action)) {
    switch ($post_action) {
        case 'add':
            $address = isset($_POST['email']) ? trim($_POST['email']) : '';
            $name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $country = isset($_POST['country']) ? trim($_POST['country']) : '';

            // Validate inputs
            $error = '';
            $emailErr = '';

            if (empty($address) || !filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address!';
                $emailErr='Invalid mail id';
                
                show_index('Invalid Email, Provide a Valid Email');

            } elseif (empty($name)) {
                $error = 'Name cannot be empty!';
                show_index('Name is required ');
               
            } elseif (strlen($name) > 50) {
                $error = 'Name is too long to be saved!';
                show_index('Name is too long to be saved !');
            } elseif ($rating < 1 || $rating > 10) {
                $error = 'Rating should be between 1 and 10!';
                show_index('Rating should be between 1 and 10!');
            } elseif (empty($country)) {
                $error = 'Please provide the country you live in!';
               show_index('Please provide the country you live in!');
            } elseif (emailExists($address)) {
               
                show_index('This email address has already been recorded. Thank you for visiting us!');
            }

            if (!empty($error)) {
                show_index($error); // Redirect back to index.php with error message
            } else {
                // If all validations pass, add email to database
                add_email($address, $name, $rating, $country);
                $_SESSION['submitted'] = true;
            }
            break;

        default:
            show_index('Unknown action; Maybe you are trying to submit an empty form!!!');
            break;
    }
} else {
    show_index('No action triggered!');
}

// Function to check if email already exists in the database
function emailExists($address)
{
    require_once 'config.php';

    try {
        $dns = 'mysql:host=' . HOST . ';dbname=' . DB;
        $pdo = new PDO($dns, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT COUNT(*) AS count FROM emails WHERE address = :address');
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['count'] > 0);
    } catch (PDOException $e) {
        show_index('MySQL connection failed: ' . $e->getMessage());
    }

    return false;
}

// Function to add email to database
function add_email($address, $name, $rating, $country)
{
    require_once 'config.php';

    try {
        $dns = 'mysql:host=' . HOST . ';dbname=' . DB;
        $pdo = new PDO($dns, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert into database
        $timestamp = time();
        $stmt = $pdo->prepare('INSERT INTO emails (address, name, rating, country, created_at) VALUES (:address, :name, :rating, :country, :created_at)');
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $timestamp, PDO::PARAM_INT);
        $inserted = $stmt->execute();
        $stmt = $pdo->query('SELECT address, created_at FROM emails ORDER BY created_at DESC LIMIT 1');
        $last_email = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($inserted) {
            show_index(); // Redirect to index.php after successful insertion
        } else {
            show_index('Something went wrong, please try again!');
        }
    } catch (PDOException $e) {
        show_index('MySQL connection failed: ' . $e->getMessage());
    }
}

// Function to redirect to index.php with optional error message
function show_index($msg = null)
{
    if (is_null($msg)) {
        header('Location: index.php');
    } else {
        header('Location: index.php?error=' . urlencode($msg));
    }
    exit();
}
