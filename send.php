<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log("FILES content: " . print_r($_FILES, true));

error_log(">>> send.php called");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Wrong request method: " . $_SERVER['REQUEST_METHOD']);
    echo "❌ Use POST method";
    exit;
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Enable SMTP debug output
    $mail->SMTPDebug = 2; // Set to 4 for very verbose, or 2 for general info
    $mail->Debugoutput = function ($str, $level) {
          error_log("SMTP DEBUG [$level]: $str");
    };

    // Gather form data
    $product = htmlspecialchars($_POST['product'] ?? 'N/A');
    $size = htmlspecialchars($_POST['size'] ?? 'N/A');
    $quantity = htmlspecialchars($_POST['quantity'] ?? 'N/A');

    $bodyText = "New Order:\n\nProduct: $product\nSize: $size\nQuantity: $quantity";
    error_log("📨 Preparing to send email:\n$bodyText");

    // Gmail SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'raj202patel@gmail.com'; // Your Gmail
    $mail->Password = '<API_PASS>';   // App password (regenerate ASAP if public)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('raj202patel@gmail.com', 'PrintEase Bot');
    $mail->addAddress('rakshitpatel640@gmail.com');
    $mail->addBCC('raj202patel@gmail.com'); 

    // Email content
    $mail->Subject = 'New PrintEase Order';
    $mail->Body = $bodyText;

    // Attachment if present
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
          $fileTmp = $_FILES['upload_file']['tmp_name'];
          $fileName = $_FILES['upload_file']['name'];
          $mail->addAttachment($fileTmp, $fileName);
          error_log("📎 Attached file: $fileName");
    } else {
          echo "<h2>Email sent via Gmail SMTP successfully.</h2>";
          error_log("📎 No file attached or file upload error.");
    }

    // Send email
    $mail->send();
    echo "<h2>Email sent via Gmail SMTP successfully.</h2>";

} catch (Exception $e) {
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
    echo "<h2>Failed to send email.<br>Error: " . htmlspecialchars($mail->ErrorInfo) . "</h2>";
}
