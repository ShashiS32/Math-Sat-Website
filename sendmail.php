<?php
// sendmail.php (Gmail SMTP version)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$name  = isset($_POST['name'])  ? strip_tags(trim($_POST['name'])) : 'New User';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$msg   = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';

if (empty($email)) {
    echo 'Email address is required.';
    exit;
}

$body  = "New signup details:\n\n";
$body .= "Name: {$name}\n";
$body .= "Email: {$email}\n";
if ($msg) {
    $body .= "\nMessage:\n{$msg}\n";
}

$mail = new PHPMailer(true);
try {
    // ─── SMTP SETTINGS ─────────────────────────────────────────────────
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'shashwats17@gmail.com';   // your Gmail address
    $mail->Password   = 'bgmyrsdysiqxyzdu';        // your 16-char App Password (no spaces)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ─── RECIPIENTS ────────────────────────────────────────────────────
    $mail->setFrom('no-reply@yourdomain.com', 'Your Website');
    $mail->addAddress('shashwats17@gmail.com', 'Site Admin');
    $mail->addReplyTo($email, $name);

    // ─── CONTENT ───────────────────────────────────────────────────────
    $mail->isHTML(false);
    $mail->Subject = '🆕 New User Registration';
    $mail->Body    = $body;

    $mail->send();
    header('Location: thankyou.html');
    exit;

} catch (Exception $e) {
    error_log('Mail error: ' . $mail->ErrorInfo);
    echo 'Sorry, we couldn’t send the email. Please try again later.';
}
