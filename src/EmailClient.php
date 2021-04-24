<?php

declare(strict_types=1);

namespace Phlib\LitmusSubjectPreview;

/**
 * EmailClient class
 *
 * @package Phlib\Litmus-Subject-Preview
 * @author    Benjamin Laugueux <benjamin@yzalis.com>
 */
class EmailClient
{
    /**
     * @var string
     */
    private $baseUri = 'https://allclients.litmus.com/s/';

    /**
     * @var $name The email client name
     */
    private $name;

    /**
     * @var $slug The email client identifier
     */
    private $slug;

    /**
     * @var $hasSlug Define if the email client has a toast view
     */
    private $hasToast;

    /**
     * @var $globalSize The width and height of global subject preview
     */
    private $globalSize;

    /**
     * @var $toastSize The width and height of toast subject preview
     */
    private $toastSize;

    /**
     * @var SubjectPreview
     */
    private $subjectPreview;

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
     * @return array
     */
    public static function getAvailableEmailClients()
    {
        $availableEmailClients = [];
        foreach (self::$clientsDatas as $key => $values) {
            $availableEmailClients[] = $key;
        }

        return $availableEmailClients;
    }

    /**
     * Get an object instance (singleton)
     *
     * @param string $slug
     *
     * @return EmailClient
     */
    public static function getInstance($slug)
    {
        if (!isset(self::$clientsDatas[$slug])) {
            throw new Exception(sprintf('The email client "%s" does not exist.', $slug));
        }

        $emailClient = new EmailClient();
        foreach (self::$clientsDatas[$slug] as $key => $value) {
            $emailClient->{'set' . ucfirst($key)}($value);
        }

        return $emailClient;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set hasToast
     *
     * @param string $hasToast
     *
     * @return this
     */
    public function setHasToast($hasToast)
    {
        $this->hasToast = (bool)$hasToast;

        return $hasToast;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set globalSize
     *
     * @param string $globalSize
     *
     * @return this
     */
    public function setGlobalSize($globalSize)
    {
        $this->globalSize = $globalSize;

        return $this;
    }

    /**
     * Set toastSize
     *
     * @param string $toastSize
     *
     * @return this
     */
    public function setToastSize($toastSize)
    {
        $this->toastSize = $toastSize;

        return $this;
    }

    /**
     * Set subjectPreview
     *
     * @param SubjectPreview $subjectPreview
     *
     * @return this
     */
    public function setSubjectPreview(SubjectPreview $subjectPreview)
    {
        $this->subjectPreview = $subjectPreview;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get globalSize
     *
     * @return array
     */
    public function getGlobalSize()
    {
        return $this->globalSize;
    }

    /**
     * Get toastSize
     *
     * @return array
     */
    public function getToastSize()
    {
        return $this->toastSize;
    }

    /**
     * Get hasToast
     *
     * @return boolean
     */
    public function getHasToast()
    {
        return $this->hasToast;
    }

    /**
     * Get the image url
     *
     * @param boolean $toast Return the toast picture url or not. Default is false
     *
     * @return string
     */
    public function getUrl($toast = false)
    {
        // check if there is a toast to show
        if ($toast && !$this->getHasToast()) {
            return null;
        }

        // construct url parameters
        $datas = [
            'c' => $this->getSlug(),
            's' => $this->subjectPreview->getSubject(),
            'p' => $this->subjectPreview->getBody(),
            'f' => $this->subjectPreview->getSender(),
            't' => $toast ? 'toast' : 'subject',
            'rnd' => rand(0, 99999)
        ];

        return $this->baseUri . '?' . http_build_query($datas);
    }
}
