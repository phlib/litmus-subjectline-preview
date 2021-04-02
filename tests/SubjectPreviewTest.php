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

    public function testSubjectPreview(): void
    {
        static::assertEquals(100, strlen($this->subjectPreview->getSubject()));
        static::assertEquals(100, strlen($this->subjectPreview->getBody()));
        static::assertEquals(50, strlen($this->subjectPreview->getSender()));
    }
}
