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
    public function testGetAvailableEmailClients(): void
    {
        $expected = [
            'ol2003',
            'ol2007',
            'ol2010',
            'hotmail',
            'gmail',
            'yahoo',
        ];

        $actual = EmailClient::getAvailableEmailClients();

        static::assertCount(count($expected), $actual);

        foreach ($expected as $emailClientSlug) {
            static::assertContains($emailClientSlug, $actual);
        }
    }

    public function testGetInstanceInvalid(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('does not exist');

        EmailClient::getInstance(sha1(uniqid()));
    }

    /**
     * @dataProvider dataEmailClientMeta
     */
    public function testEmailClientMeta(
        string $slug,
        string $name,
        bool $hasToast,
        int $width,
        int $height,
        ?int $toastWidth,
        ?int $toastHeight
    ): void {
        $emailClient = EmailClient::getInstance($slug);

        static::assertEquals($slug, $emailClient->getSlug());
        static::assertEquals($name, $emailClient->getName());
        static::assertSame($hasToast, $emailClient->getHasToast());
        $size = $emailClient->getGlobalSize();
        static::assertEquals($width, $size['width']);
        static::assertEquals($height, $size['height']);
        $size = $emailClient->getToastSize();
        if ($toastWidth === null) {
            static::assertNull($size);
        } else {
            static::assertEquals($toastWidth, $size['width']);
            static::assertEquals($toastHeight, $size['height']);
        }
    }

    public function dataEmailClientMeta(): array
    {
        return [
            'ol2003' => [
                'slug' => 'ol2003',
                'name' => 'Outlook 2003',
                'hasToast' => true,
                'width' => 841,
                'height' => 128,
                'toastWidth' => 329,
                'toastHeight' => 74,
            ],
            'ol2007' => [
                'slug' => 'ol2007',
                'name' => 'Outlook 2007',
                'hasToast' => true,
                'width' => 662,
                'height' => 169,
                'toastWidth' => 329,
                'toastHeight' => 74,
            ],
            'ol2010' => [
                'slug' => 'ol2010',
                'name' => 'Outlook 2010',
                'hasToast' => true,
                'width' => 579,
                'height' => 128,
                'toastWidth' => 329,
                'toastHeight' => 74,
            ],
            'hotmail' => [
                'slug' => 'hotmail',
                'name' => 'Hotmail',
                'hasToast' => false,
                'width' => 687,
                'height' => 110,
                'toastWidth' => null,
                'toastHeight' => null,
            ],
            'gmail' => [
                'slug' => 'gmail',
                'name' => 'Gmail',
                'hasToast' => false,
                'width' => 803,
                'height' => 83,
                'toastWidth' => null,
                'toastHeight' => null,
            ],
            'yahoo' => [
                'slug' => 'yahoo',
                'name' => 'Yahoo',
                'hasToast' => false,
                'width' => 601,
                'height' => 104,
                'toastWidth' => null,
                'toastHeight' => null,
            ],
        ];
    }

    public function testUrlBase(): void
    {
        $subject = sha1(uniqid());
        $body = sha1(uniqid());
        $sender = sha1(uniqid());

        $subjectPreview = new SubjectPreview();
        $subjectPreview
            ->setSubject($subject)
            ->setBody($body)
            ->setSender($sender)
        ;

        $uri = EmailClient::getInstance('ol2003')->getInboxUrl($subjectPreview);

        static::assertStringStartsWith('https://allclients.litmus.com/s/?c=ol2003', $uri);

        $query = parse_url($uri, PHP_URL_QUERY);
        $params = [];
        parse_str($query, $params);

        static::assertArrayHasKey('s', $params);
        static::assertEquals($subject, $params['s']);

        static::assertArrayHasKey('p', $params);
        static::assertEquals($body, $params['p']);

        static::assertArrayHasKey('f', $params);
        static::assertEquals($sender, $params['f']);

        static::assertArrayHasKey('rnd', $params);
        static::assertIsNumeric($params['rnd']);
    }

    public function testUrlInbox(): void
    {
        $subjectPreview = new SubjectPreview();
        $subjectPreview
            ->setSubject(sha1(uniqid()))
            ->setBody(sha1(uniqid()))
            ->setSender(sha1(uniqid()))
        ;

        $uri = EmailClient::getInstance('ol2003')->getInboxUrl($subjectPreview);

        $query = parse_url($uri, PHP_URL_QUERY);
        $params = [];
        parse_str($query, $params);

        static::assertArrayHasKey('t', $params);
        static::assertEquals('subject', $params['t']);
    }

    public function testUrlToast(): void
    {
        $subjectPreview = new SubjectPreview();
        $subjectPreview
            ->setSubject(sha1(uniqid()))
            ->setBody(sha1(uniqid()))
            ->setSender(sha1(uniqid()))
        ;

        $uri = EmailClient::getInstance('ol2003')->getToastUrl($subjectPreview);

        $query = parse_url($uri, PHP_URL_QUERY);
        $params = [];
        parse_str($query, $params);

        static::assertArrayHasKey('t', $params);
        static::assertEquals('toast', $params['t']);
    }

    public function testUrlToastNone(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Toast not supported');

        $subjectPreview = new SubjectPreview();
        $subjectPreview
            ->setSubject(sha1(uniqid()))
            ->setBody(sha1(uniqid()))
            ->setSender(sha1(uniqid()))
        ;

        EmailClient::getInstance('yahoo')->getToastUrl($subjectPreview);
    }
}
