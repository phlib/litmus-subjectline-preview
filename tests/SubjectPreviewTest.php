<?php

declare(strict_types=1);

namespace Phlib\LitmusSubjectPreview\Test;

use Phlib\LitmusSubjectPreview\SubjectPreview;
use PHPUnit\Framework\TestCase;

/**
 * @package Phlib\Litmus-Subject-Preview
 */
class SubjectPreviewTest extends TestCase
{
    /**
     * @dataProvider dataGetSubject
     */
    public function testGetSubject(string $value, string $expected): void
    {
        $subjectPreview = new SubjectPreview();

        $subjectPreview->setSubject($value);

        static::assertSame($expected, $subjectPreview->getSubject());
    }

    public function dataGetSubject(): array
    {
        $baseString = sha1(uniqid()); // 40 chars
        $multibyte = '€'; // Euro \u{20AC}
        return [
            'shortSubject' => [
                str_repeat($baseString, 2),
                str_repeat($baseString, 2),
            ],
            'shortSenderNewline' => [
                $baseString . "\r\n" . $baseString,
                $baseString . ' ' . $baseString, // Replace newline
            ],
            'longSubject' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 100), // Truncate to 100 chars
            ],
            'longSubjectMb' => [
                str_repeat($baseString, 2) . $multibyte . str_repeat($baseString, 2),
                str_repeat($baseString, 2) . $multibyte . substr($baseString, 0, 19), // MB-safe truncate to 100 chars
            ],
            'longSenderNewline' => [
                str_repeat($baseString, 2) . "\r\n" . str_repeat($baseString, 2),
                str_repeat($baseString, 2) . ' ' . substr($baseString, 0, 19), // Replace newline, truncate to 100 chars
            ],
        ];
    }

    /**
     * @dataProvider dataGetBody
     */
    public function testGetBody(string $value, string $expected): void
    {
        $subjectPreview = new SubjectPreview();

        $subjectPreview->setBody($value);

        static::assertSame($expected, $subjectPreview->getBody());
    }

    public function dataGetBody(): array
    {
        $baseString = sha1(uniqid()); // 40 chars
        $multibyte = '€'; // Euro \u{20AC}
        return [
            'shortBody' => [
                str_repeat($baseString, 2),
                str_repeat($baseString, 2),
            ],
            'shortSenderNewline' => [
                $baseString . "\r\n" . $baseString,
                $baseString . ' ' . $baseString, // Replace newline
            ],
            'longBody' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 100), // Truncate to 100 chars
            ],
            'longSubjectMb' => [
                str_repeat($baseString, 2) . $multibyte . str_repeat($baseString, 2),
                str_repeat($baseString, 2) . $multibyte . substr($baseString, 0, 19), // MB-safe truncate to 100 chars
            ],
            'longSenderNewline' => [
                str_repeat($baseString, 2) . "\r\n" . str_repeat($baseString, 2),
                str_repeat($baseString, 2) . ' ' . substr($baseString, 0, 19), // Replace newline, truncate to 100 chars
            ],
        ];
    }

    /**
     * @dataProvider dataGetSender
     */
    public function testGetSender(string $value, string $expected): void
    {
        $subjectPreview = new SubjectPreview();

        $subjectPreview->setSender($value);

        static::assertSame($expected, $subjectPreview->getSender());
    }

    public function dataGetSender(): array
    {
        $baseString = sha1(uniqid()); // 40 chars
        $multibyte = '€'; // Euro \u{20AC}
        return [
            'shortSender' => [
                $baseString,
                $baseString,
            ],
            'shortSenderNewline' => [
                $baseString . "\r\n",
                $baseString . ' ', // Replace newline
            ],
            'longSender' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 50), // Truncate to 50 chars
            ],
            'longSubjectMb' => [
                $baseString . $multibyte . $baseString,
                $baseString . $multibyte . substr($baseString, 0, 9), // MB-safe truncate to 50 chars
            ],
            'longSenderNewline' => [
                $baseString . "\r\n" . $baseString,
                $baseString . ' ' . substr($baseString, 0, 9), // Replace newline, truncate to 50 chars
            ],
        ];
    }

    public function testGetEmailClient(): void
    {
        $checkValue = sha1(uniqid());

        $subjectPreview = new SubjectPreview();
        $subjectPreview->setSubject($checkValue);
        $subjectPreview->setBody('');
        $subjectPreview->setSender('');

        $emailClient = $subjectPreview->getEmailClient('ol2003');

        self::assertEquals('ol2003', $emailClient->getSlug());

        // Test EmailClient is using the given subject
        self::assertStringContainsString($checkValue, $emailClient->getUrl(false));
    }
}
