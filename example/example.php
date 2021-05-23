<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Phlib\LitmusSubjectPreview\EmailClient;
use Phlib\LitmusSubjectPreview\SubjectPreview;

// Build subject details
$subjectPreview = new SubjectPreview(
    'Lorem ipsum',
    'Lorem ipsum dolor sit amet',
    'Example sender <sender@example.com>',
);

// Fluent access to single client's preview URI
$previewUri = EmailClient::create('ol2003')->getInboxUrl($subjectPreview);
echo '<pre>' . $previewUri . '</pre>';

echo "\n\n";

// Output all available images
foreach (EmailClient::getAvailableEmailClients() as $clientName) {
    $emailClient = EmailClient::create($clientName);
    echo "<h1>{$emailClient->getName()} ({$emailClient->getSlug()})</h1>\n";

    $subjectSize = $emailClient->getGlobalSize();
    echo <<<HTML
<img src="{$emailClient->getInboxUrl($subjectPreview)}"
    alt="{$emailClient->getSlug()} subject"
    width="{$subjectSize['width']}"
    height="{$subjectSize['height']}"
    />

HTML;

    if ($emailClient->getHasToast()) {
        $toastSize = $emailClient->getToastSize();
        echo <<<HTML
<img src="{$emailClient->getToastUrl($subjectPreview)}"
    alt="{$emailClient->getSlug()} toast"
    width="{$toastSize['width']}"
    height="{$toastSize['height']}"
    />

HTML;
    }

    echo "\n\n";
}
