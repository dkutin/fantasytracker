<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/lib/PHPMailer/src/Exception.php');
require (__DIR__ . '/lib/PHPMailer/src/PHPMailer.php');
require (__DIR__ . '/lib/PHPMailer/src/SMTP.php');

include_once (__DIR__ . '/constants.php');

class Mail
{
    protected $username;
    protected $password;
    protected $mail;

    function __construct()
    {
        if (empty(FANTASY_TRACKER_PASSWORD) && empty(FANTASY_TRACKER_EMAIL)) {
            return;
        }
        $this->username = FANTASY_TRACKER_EMAIL;
        $this->password = FANTASY_TRACKER_PASSWORD;
    }

    function initializeMail()
    {
        $mail = new PHPMailer();

        // SMTP Settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Host= 'smtp.gmail.com';
        $mail->Port = 587;
        // AUTH Settings
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        // Subject and Sender
        $mail->SetFrom($this->username, "Fantasy Tracker");
        $mail->Subject = "Your Fantasy Tracker Update!";
        $this->mail = $mail;
    }

    function sendEmail($recipient, $data)
    {
        // Add Recipient Address
        $this->mail->addAddress($recipient);
        // Add body content
        $this->mail->isHTML(TRUE);
        $this->mail->msgHTML($data);

        return $this->mail->send();
    }
}
