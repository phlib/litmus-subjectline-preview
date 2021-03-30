<?php

require __DIR__ . '/../vendor/autoload.php';

use Phlib\LitmusSubjectPreview\EmailClient;
use Phlib\LitmusSubjectPreview\SubjectPreview;

// Build subject details
$subjectPreview = new SubjectPreview();
$subjectPreview
    ->setSubject('Lorem ipsum')
    ->setBody('Lorem ipsum dolor sit amet')
    ->setSender('Example sender <sender@example.com>')
;

// Fluent access to single client's preview URI
$previewUri = $subjectPreview->getEmailClient('ol2003')->getUrl();

echo '<pre>' . $previewUri . '</pre>';

echo "\n\n";

// Output all available images
foreach (EmailClient::getAvailableEmailClients() as $clientName) {
    $emailClient = EmailClient::getInstance($clientName);
    $emailClient->setSubjectPreview($subjectPreview);
    echo "<h1>{$emailClient->getName()} ({$emailClient->getSlug()})</h1>\n";

    $subjectSize = $emailClient->getGlobalSize();
    echo <<<HTML
<img src="{$emailClient->getUrl(false)}"
    alt="{$emailClient->getSlug()} subject"
    width="{$subjectSize['width']}"
    height="{$subjectSize['height']}"
    />

HTML;

    if ($emailClient->getHasToast()) {
        $toastSize = $emailClient->getToastSize();
        echo <<<HTML
<img src="{$emailClient->getUrl(true)}"
    alt="{$emailClient->getSlug()} toast"
    width="{$toastSize['width']}"
    height="{$toastSize['height']}"
    />

HTML;
    }

    echo "\n\n";
}
