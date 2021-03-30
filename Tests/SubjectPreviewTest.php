<?php

namespace Litmus\Tests;

use Litmus\SubjectPreview\SubjectPreview;
use Litmus\SubjectPreview\EmailClient;

/**
 * @package Phlib\Litmus-Subject-Preview
 */
class SubjectPreviewTest extends \PHPUnit_Framework_TestCase
{
    protected $subjectPreview;

    public function setUp()
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

    protected function tearDown()
    {
        $this->subjectPreview = null;
    }

    public function testSubjectPreview()
    {
        $this->assertEquals(100, strlen($this->subjectPreview->getSubject()));
        $this->assertEquals(100, strlen($this->subjectPreview->getBody()));
        $this->assertEquals(50, strlen($this->subjectPreview->getSender()));
    }

    public function testEmailClient()
    {
        $this->assertCount(6, EmailClient::getAvailableEmailClients());

        foreach (EmailClient::getAvailableEmailClients() as $emailClientSlug) {
            $this->assertInstanceOf(
                'Litmus\SubjectPreview\EmailClient',
                $this->subjectPreview->getEmailClient($emailClientSlug)
            );
        }

        $emailClients = EmailClient::getAvailableEmailClients();
        $emailClient = $this->subjectPreview->getEmailClient($emailClients[0]);

        $this->assertEquals('ol2003', $emailClient->getSlug());
        $this->assertEquals('Outlook 2003', $emailClient->getName());
        $this->assertTrue($emailClient->getHasToast());
        $size = $emailClient->getGlobalSize();
        $this->assertEquals(128, $size['height']);
        $this->assertEquals(841, $size['width']);
        $size = $emailClient->getToastSize();
        $this->assertEquals(74, $size['height']);
        $this->assertEquals(329, $size['width']);
    }

    public function testUrl()
    {
        $this->assertStringStartsWith(
            'https://allclients.litmus.com/s/?c=ol2003' .
            '&s=abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuv' .
            '&p=zyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfe' .
            '&f=aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyy' .
            '&t=subject&rnd=',
            $this->subjectPreview->getEmailClient('ol2003')->getUrl()
        );
    }
}
