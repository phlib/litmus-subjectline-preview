<?php

declare(strict_types=1);

namespace Phlib\LitmusSubjectPreview;

/**
 * Filtered values for generating email's subject pictures
 *
 * @package Phlib\Litmus-Subject-Preview
 */
class SubjectPreview
{
    /**
     * @var string The email subject
     */
    private $subject;

    /**
     * @var string The email body
     */
    private $body;

    /**
     * @var string The email sender name
     */
    private $sender;

    public function __construct(string $subject, string $body, string $sender)
    {
        $this->subject = $this->clean($subject, 100);
        $this->body = $this->clean($body, 100);
        $this->sender = $this->clean($sender, 50);
    }

    public function withSubject(string $subject): self
    {
        return new static($subject, $this->body, $this->sender);
    }

    public function withBody(string $body): self
    {
        return new static($this->subject, $body, $this->sender);
    }

    public function withSender(string $sender): self
    {
        return new static($this->subject, $this->body, $sender);
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    private function clean(string $text, int $substrSize): string
    {
        // strip out newline characters
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);

        $text = mb_substr($text, 0, $substrSize);

        // Litmus-specific substitutions
        $text = str_replace(['&', '+', '#'], ['$AMP;', '$PLUS;', '$HASH;'], $text);

        return $text;
    }
}
