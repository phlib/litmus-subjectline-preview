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
        $subjectPreview = new SubjectPreview(
            $value,
            '',
            '',
        );

        static::assertSame($expected, $subjectPreview->getSubject());
    }

    /**
     * @dataProvider dataGetSubject
     */
    public function testWithSubject(string $value, string $expected): void
    {
        $subject = sha1(uniqid());
        $body = sha1(uniqid());
        $sender = sha1(uniqid());

        $subjectPreview = new SubjectPreview(
            $subject,
            $body,
            $sender,
        );

        $newSubject = $subjectPreview->withSubject($value);

        // Original instance is unmodified
        static::assertSame($subject, $subjectPreview->getSubject());

        // New instance has cleaned new value
        static::assertSame($expected, $newSubject->getSubject());

        // New instance has original values for other properties
        static::assertSame($body, $newSubject->getBody());
        static::assertSame($sender, $newSubject->getSender());
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
        $subjectPreview = new SubjectPreview(
            '',
            $value,
            '',
        );

        static::assertSame($expected, $subjectPreview->getBody());
    }

    /**
     * @dataProvider dataGetBody
     */
    public function testWithBody(string $value, string $expected): void
    {
        $subject = sha1(uniqid());
        $body = sha1(uniqid());
        $sender = sha1(uniqid());

        $subjectPreview = new SubjectPreview(
            $subject,
            $body,
            $sender,
        );

        $newSubject = $subjectPreview->withBody($value);

        // Original instance is unmodified
        static::assertSame($body, $subjectPreview->getBody());

        // New instance has cleaned new value
        static::assertSame($expected, $newSubject->getBody());

        // New instance has original values for other properties
        static::assertSame($subject, $newSubject->getSubject());
        static::assertSame($sender, $newSubject->getSender());
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
        $subjectPreview = new SubjectPreview(
            '',
            '',
            $value,
        );

        static::assertSame($expected, $subjectPreview->getSender());
    }

    /**
     * @dataProvider dataGetSender
     */
    public function testWithSender(string $value, string $expected): void
    {
        $subject = sha1(uniqid());
        $body = sha1(uniqid());
        $sender = sha1(uniqid());

        $subjectPreview = new SubjectPreview(
            $subject,
            $body,
            $sender,
        );

        $newSubject = $subjectPreview->withSender($value);

        // Original instance is unmodified
        static::assertSame($sender, $subjectPreview->getSender());

        // New instance has cleaned new value
        static::assertSame($expected, $newSubject->getSender());

        // New instance has original values for other properties
        static::assertSame($subject, $newSubject->getSubject());
        static::assertSame($body, $newSubject->getBody());
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
}
