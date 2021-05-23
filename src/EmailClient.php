<?php

declare(strict_types=1);

namespace Phlib\LitmusSubjectPreview;

/**
 * @package Phlib\Litmus-Subject-Preview
 */
class EmailClient
{
    /**
     * @var string
     */
    private $baseUri = 'https://allclients.litmus.com/s/';

    /**
     * @var string The email client name
     */
    private $name;

    /**
     * @var string The email client identifier
     */
    private $slug;

    /**
     * @var bool Define if the email client has a toast view
     */
    private $hasToast;

    /**
     * @var array The width and height of global subject preview
     */
    private $globalSize;

    /**
     * @var array The width and height of toast subject preview
     */
    private $toastSize;

    /**
     * @var array $clientsDatas List of mail client datas
     */
    private static $clientsDatas = [
        'ol2003' => [
            'name' => 'Outlook 2003',
            'slug' => 'ol2003',
            'hasToast' => true,
            'globalSize' => ['width' => 841, 'height' => 128],
            'toastSize' => ['width' => 329, 'height' => 74],
        ],
        'ol2007' => [
            'name' => 'Outlook 2007',
            'slug' => 'ol2007',
            'hasToast' => true,
            'globalSize' => ['width' => 662, 'height' => 169],
            'toastSize' => ['width' => 329, 'height' => 74],
        ],
        'ol2010' => [
            'name' => 'Outlook 2010',
            'slug' => 'ol2010',
            'hasToast' => true,
            'globalSize' => ['width' => 579, 'height' => 128],
            'toastSize' => ['width' => 329, 'height' => 74],
        ],
        'hotmail' => [
            'name' => 'Hotmail',
            'slug' => 'hotmail',
            'hasToast' => false,
            'globalSize' => ['width' => 687, 'height' => 110],
        ],
        'gmail' => [
            'name' => 'Gmail',
            'slug' => 'gmail',
            'hasToast' => false,
            'globalSize' => ['width' => 803, 'height' => 83],
        ],
        'yahoo' => [
            'name' => 'Yahoo',
            'slug' => 'yahoo',
            'hasToast' => false,
            'globalSize' => ['width' => 601, 'height' => 104],
        ],
    ];

    /**
     * Get available email client slug list
     *
     * @return string[]
     */
    public static function getAvailableEmailClients(): array
    {
        $availableEmailClients = [];
        foreach (self::$clientsDatas as $key => $values) {
            $availableEmailClients[] = $key;
        }

        return $availableEmailClients;
    }

    public static function getInstance(string $slug): EmailClient
    {
        if (!isset(self::$clientsDatas[$slug])) {
            throw new \DomainException(sprintf('The email client "%s" does not exist.', $slug));
        }

        $emailClient = new EmailClient();
        foreach (self::$clientsDatas[$slug] as $key => $value) {
            $emailClient->{'set' . ucfirst($key)}($value);
        }

        return $emailClient;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setHasToast(bool $hasToast): self
    {
        $this->hasToast = (bool)$hasToast;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setGlobalSize(array $globalSize): self
    {
        $this->globalSize = $globalSize;

        return $this;
    }

    public function setToastSize(array $toastSize): self
    {
        $this->toastSize = $toastSize;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getGlobalSize(): array
    {
        return $this->globalSize;
    }

    public function getToastSize(): ?array
    {
        return $this->toastSize;
    }

    public function getHasToast(): bool
    {
        return $this->hasToast;
    }

    public function getInboxUrl(SubjectPreview $subject): string
    {
        return $this->getUrl($subject, false);
    }

    public function getToastUrl(SubjectPreview $subject): string
    {
        if (!$this->getHasToast()) {
            throw new \DomainException(sprintf('Toast not supported for "%s"', $this->getSlug()));
        }
        return $this->getUrl($subject, true);
    }

    private function getUrl(SubjectPreview $subject, bool $toast = false): string
    {
        $data = [
            'c' => $this->getSlug(),
            's' => $subject->getSubject(),
            'p' => $subject->getBody(),
            'f' => $subject->getSender(),
            't' => $toast ? 'toast' : 'subject',
            'rnd' => rand(0, 99999)
        ];

        return $this->baseUri . '?' . http_build_query($data);
    }
}
