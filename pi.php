<?php

include (__DIR__ . '/ServiceCall.php');
include (__DIR__ . '/Analytics.php');
include (__DIR__ . '/Mail.php');

$request = new ServiceCall();
$analytics = new Analytics();
$mailer = new Mail();

// First we grab Roster and FA stats for the week
$request->getRosterStats();
$request->getFreeAgentsStats();
// Then we generate the report
$data = $analytics->generateReport();

// Initialize mailer credentials and send email from generated CSV
$mailer->initializeMail();
$status = $mailer->sendEmail('', $data);

