<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

function clean($value) {
    return trim(strip_tags((string)$value));
}

$name = clean($_POST['name'] ?? '');
$business = clean($_POST['business'] ?? '');
$city = clean($_POST['city'] ?? '');
$platform = clean($_POST['platform'] ?? '');
$video_url = filter_var($_POST['video_url'] ?? '', FILTER_SANITIZE_URL);
$message = clean($_POST['message'] ?? '');
$consent = clean($_POST['consent'] ?? '');

if ($name === '' || $platform === '' || !filter_var($video_url, FILTER_VALIDATE_URL) || $consent !== 'yes') {
    http_response_code(400);
    exit('Please complete the required fields and provide a valid public video URL.');
}

$to = 'info@threeroots.net';
$subject = 'Three Roots Customer Video Submission';

$body = "Customer Video Submission\n\n";
$body .= "Name: {$name}\n";
$body .= "Business / Dealer / Breeder: {$business}\n";
$body .= "City: {$city}\n";
$body .= "Platform: {$platform}\n";
$body .= "Video URL: {$video_url}\n";
$body .= "Message: {$message}\n";
$body .= "Consent to review/display: Yes\n";

$headers = "From: website@threerootsanimalnutrition.net\r\n";
$headers .= "Reply-To: info@threeroots.net\r\n";

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Video Submitted</title></head><body style="font-family:Arial,sans-serif;background:#f7f1df;color:#173b2c;padding:40px"><div style="max-width:700px;margin:auto;background:#fffdf7;border:1px solid #cad7c5;border-radius:16px;padding:28px"><h1>Thank you.</h1><p>Your video link was sent to Three Roots Animal Nutrition for review.</p><p><a href="./" style="color:#275f43;font-weight:bold">Return to the website</a></p></div></body></html>';
} else {
    http_response_code(500);
    echo 'We could not send your submission. Please email the video link to info@threeroots.net.';
}
?>