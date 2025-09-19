<?php
session_start();
// Only allow admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $error = '';
    if ($firstname === '' || $surname === '' || $email === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Check for duplicate email
            $check = $conn->prepare("SELECT id FROM participant WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                $error = 'This email address is already registered as a participant.';
            } else {
                $stmt = $conn->prepare("INSERT INTO participant (firstname, surname, email) VALUES (?, ?, ?)");
                $stmt->execute([$firstname, $surname, $email]);
                header('Location: view_participants_edit_delete.php?success=1');
                exit();
            }
        } catch(PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
} else {
    $error = 'Invalid request.';
}
if (!empty($error)) {
    echo '<!DOCTYPE html><html><head><title>Add Participant Error</title></head><body>';
    echo '<h2>Error Adding Participant</h2>';
    echo '<p>' . htmlspecialchars($error) . '</p>';
    echo '<a href="add_participant_form.php">Back to Add Participant</a>';
    echo '</body></html>';
}
?>
