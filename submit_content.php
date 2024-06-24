<?php
session_start();

// Dummy admin login check for simplicity
function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];

    // Handling file uploads
    $uploadDirectory = 'uploads/';
    $uploadedFiles = [];
    foreach ($_FILES['file']['name'] as $key => $name) {
        $tmp_name = $_FILES['file']['tmp_name'][$key];
        $uploadPath = $uploadDirectory . basename($name);
        if (move_uploaded_file($tmp_name, $uploadPath)) {
            $uploadedFiles[] = $uploadPath;
        }
    }

    // Save the data to a text file for simplicity
    $file = fopen('submissions.txt', 'a');
    fwrite($file, "Name: $name\nEmail: $email\nContent: $content\nFiles: " . implode(', ', $uploadedFiles) . "\n\n");
    fclose($file);

    // Redirect back to the form page or a thank you page
    header('Location: thank_you.html');
    exit;
}

// Admin view
if (isAdmin()) {
    $submissions = file_get_contents('submissions.txt');
    echo nl2br($submissions);
} else {
    echo "Access Denied. Only admins can view this page.";
}
?>
