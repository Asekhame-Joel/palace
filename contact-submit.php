<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

function respond(bool $success, string $message, int $code = 200): void
{
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.', 405);
}

if (!csrf_verify()) {
    respond(false, 'Your session has expired. Please refresh the page and try again.', 403);
}

// Honeypot — bots tend to fill every field.
if (!empty($_POST['company'])) {
    // Silently "succeed" so bots don't learn anything, but don't store the message.
    respond(true, 'Thank you. Your message has been received by the palace court.');
}

$name    = trim((string) ($_POST['name'] ?? ''));
$email   = trim((string) ($_POST['email'] ?? ''));
$phone   = trim((string) ($_POST['phone'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || mb_strlen($name) > 150) {
    respond(false, 'Please enter your full name.');
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
    respond(false, 'Please enter a valid email address.');
}
if ($message === '' || mb_strlen($message) > 5000) {
    respond(false, 'Please enter a message (up to 5000 characters).');
}

// Basic rate limiting per session to deter abuse.
ensure_session();
$now = time();
$_SESSION['contact_submits'] = array_filter($_SESSION['contact_submits'] ?? [], fn($t) => $t > $now - 3600);
if (count($_SESSION['contact_submits']) >= 5) {
    respond(false, 'You have sent several messages recently. Please try again later.', 429);
}

try {
    $stmt = db()->prepare('INSERT INTO messages (name, email, phone, subject, message, status, created_at)
                            VALUES (:name, :email, :phone, :subject, :message, "unread", NOW())');
    $stmt->execute([
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone !== '' ? $phone : null,
        'subject' => $subject !== '' ? $subject : null,
        'message' => $message,
    ]);
} catch (Throwable $e) {
    respond(false, 'Could not send your message right now. Please try again shortly.', 500);
}

$_SESSION['contact_submits'][] = $now;

// Optional email notification — never let a mail failure break the submission.
$notifyTo = getenv('CONTACT_NOTIFY_EMAIL');
if ($notifyTo) {
    $mailSubject = 'New palace contact message: ' . ($subject !== '' ? $subject : 'General enquiry');
    $mailBody = "Name: $name\nEmail: $email\nPhone: $phone\n\n$message";
    $headers = 'From: no-reply@' . preg_replace('/^www\./', '', parse_url(SITE_URL, PHP_URL_HOST) ?: 'localhost');
    @mail($notifyTo, $mailSubject, $mailBody, $headers);
}

respond(true, 'Thank you. Your message has been received by the palace court.');
