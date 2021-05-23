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

    public function setSubject(string $subject): self
    {
        $this->subject = $this->clean($subject, 100);

        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $this->clean($body, 100);

        return $this;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $this->clean($sender, 50);

        return $this;
    }

    public function getEmailClient(string $clientSlug): EmailClient
    {
        return EmailClient::getInstance($clientSlug)->setSubjectPreview($this);
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
