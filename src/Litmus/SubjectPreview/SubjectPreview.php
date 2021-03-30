<?php

namespace Litmus\SubjectPreview;

use Litmus\SubjectPreview\EmailClient;

/**
 * GenerateSubjectPreview : generate email's subject pictures
 *
 * @package Phlib\Litmus-Subject-Preview
 * @author    Benjamin Laugueux <benjamin@yzalis.com>
 */
class SubjectPreview
{
    /**
     * @var string $subject The email subject
     */
    private $subject;

    /**
     * @var string $body The email body
     */
    private $body;

    /**
     * @var string $sender The email sender name
     */
    private $sender;

    /**
     * @var string $endPoint The endpoint
     */
    private $endPoint = 'https://allclients.litmus.com/s/';

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return this
     */
    public function setSubject($subject)
    {
        $this->subject = $this->clean($subject, 100);

        return $this;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return this
     */
    public function setBody($body)
    {
        $this->body = $this->clean($body, 100);

        return $this;
    }

    /**
     * Set sender
     *
     * @param string $sender
     *
     * @return this
     */
    public function setSender($sender)
    {
        $this->sender = $this->clean($sender, 50);

        return $this;
    }

    /**
     * Get an EmailClient instance
     *
     * @param string $clientSlug
     *
     * @return EmailClient
     */
    public function getEmailClient($clientSlug)
    {
        return EmailClient::getInstance($clientSlug)->setSubjectPreview($this);
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get sender
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Get endPoint
     *
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * Clean and strip the text
     *
     * @param string  $text
     * @param integer $substrSize
     *
     * @return string
     */
    private function clean($text, $substrSize)
    {
        $text = substr($text, 0, $substrSize);
        $text = str_replace(['&', '+', '#'], ['$AMP;', '$PLUS;', '$HASH;'], $text);

        return $text;
    }
}
