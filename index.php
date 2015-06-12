<?php
require 'vendor/mandrill/mandrill/src/Mandrill.php';
require 'config/settings.php';
require 'config/receivers.php';

if(!isset($_POST['mandrill_events'])) {
    echo 'A mandrill error occurred: Invalid mandrill_events';
    exit;
}
$mail = array_pop(json_decode($_POST['mandrill_events']));

$attachments = array();
if(isset($mail->msg->attachments)) {
    foreach ($mail->msg->attachments as $attachment) {
        $attachments[] = array(
            'type' => $attachment->type,
            'name' => $attachment->name,
            'content' => $attachment->content,
        );
    }
}
 
$headers = array();
// Support only Reply-to header
if(isset($mail->msg->headers->{'Reply-to'})) {
    $headers[] = array('Reply-to' => $mail->msg->headers->{'Reply-to'});
}

try {
    $mandrill = new Mandrill($settings['API_KEY']);
    // Search if the alias is defined in config/receivers.php, otherwise fallback to the catch-all
    $mail_receivers = array();
    if(isset($receivers[$mail->msg->email])) {
        $mail_receivers = $receivers[$mail->msg->email];
    }else{
        $mail_receivers = $receivers['*'];
    }
    // Compose the message. More info on https://mandrillapp.com/api/docs/messages.JSON.html#method=send
    $message = array(
        'html' => $mail->msg->html,
        'text' => $mail->msg->text,
        'subject' => "[".$mail->msg->email."] ".$mail->msg->subject,
        'from_email' => $mail->msg->from_email,
        'from_name' => $mail->msg->from_name,
        'to' => $mail_receivers,
        'attachments' => $attachments,
        'headers' => $headers,
    );
    $async = false;
    $result = $mandrill->messages->send($message, $async);
    print_r($result);
} catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_PaymentRequired - This feature is only available for accounts with a positive balance.
    throw $e;
}