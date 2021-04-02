<?php

declare(strict_types=1);

namespace Phlib\LitmusSubjectPreview\Test;

use Phlib\LitmusSubjectPreview\SubjectPreview;
use Phlib\LitmusSubjectPreview\EmailClient;
use PHPUnit\Framework\TestCase;

/**
 * @package Phlib\Litmus-Subject-Preview
 */
class EmailClientTest extends TestCase
{
    protected $subjectPreview;

    public function setUp(): void
    {
        $this->subjectPreview = new SubjectPreview();
        $this->subjectPreview
            ->setSubject(
                'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz' .
                'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz'
            )
            ->setBody(
                'zyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcba' .
                'zyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcba'
            )
            ->setSender('aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz')
        ;
    }

    protected function tearDown(): void
    {
        $this->subjectPreview = null;
    }

    public function testEmailClient(): void
    {
        static::assertCount(6, EmailClient::getAvailableEmailClients());

        foreach (EmailClient::getAvailableEmailClients() as $emailClientSlug) {
            static::assertInstanceOf(
                EmailClient::class,
                $this->subjectPreview->getEmailClient($emailClientSlug)
            );
        }

        $emailClients = EmailClient::getAvailableEmailClients();
        $emailClient = $this->subjectPreview->getEmailClient($emailClients[0]);

        static::assertEquals('ol2003', $emailClient->getSlug());
        static::assertEquals('Outlook 2003', $emailClient->getName());
        static::assertTrue($emailClient->getHasToast());
        $size = $emailClient->getGlobalSize();
        static::assertEquals(128, $size['height']);
        static::assertEquals(841, $size['width']);
        $size = $emailClient->getToastSize();
        static::assertEquals(74, $size['height']);
        static::assertEquals(329, $size['width']);
    }

    public function testUrl(): void
    {
        static::assertStringStartsWith(
            'https://allclients.litmus.com/s/?c=ol2003' .
            '&s=abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuv' .
            '&p=zyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfe' .
            '&f=aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyy' .
            '&t=subject&rnd=',
            $this->subjectPreview->getEmailClient('ol2003')->getUrl()
        );
    }
}
