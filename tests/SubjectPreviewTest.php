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
        return [
            'shortSubject' => [
                str_repeat($baseString, 2),
                str_repeat($baseString, 2),
            ],
            'longSubject' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 100), // Truncate to 100 chars
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
        return [
            'shortBody' => [
                str_repeat($baseString, 2),
                str_repeat($baseString, 2),
            ],
            'longBody' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 100), // Truncate to 100 chars
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
        return [
            'shortSender' => [
                $baseString,
                $baseString,
            ],
            'longSender' => [
                str_repeat($baseString, 5),
                substr(str_repeat($baseString, 3), 0, 50), // Truncate to 50 chars
            ],
        ];
    }

    public function testGetEmailClient(): void
    {
        $checkValue = sha1(uniqid());

        $subjectPreview = new SubjectPreview();
        $subjectPreview->setSubject($checkValue);

        $emailClient = $subjectPreview->getEmailClient('ol2003');

        self::assertEquals('ol2003', $emailClient->getSlug());

        self::assertStringContainsString($checkValue, $emailClient->getUrl(false));
    }
}
