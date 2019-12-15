<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Dto;

use JsonSerializable;

class JedDto implements JsonSerializable
{
    /** @var string */
    private $domain;

    /** @var string */
    private $language;

    /** @var string */
    private $pluralForms;

    /** @var array */
    private $translations = [];

    public function toArray(): array
    {
        $settings = [
            '' => [
                'domain' => $this->domain,
                'lang' => $this->language,
                'plural_forms' => $this->pluralForms,
            ],
        ];

        /** @noinspection AdditionOperationOnArraysInspection */
        return $settings + $this->translations;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function getPluralForms(): string
    {
        return $this->pluralForms;
    }

    public function setPluralForms(string $pluralForms): void
    {
        $this->pluralForms = $pluralForms;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function setTranslations(array $translations = []): void
    {
        $this->translations = $translations;
    }

    public function addTranslations(string $key, array $translations): void
    {
        $this->translations[$key] = $translations;
    }
}
