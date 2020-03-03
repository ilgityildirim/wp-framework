<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter;

use Translations;
use NOOP_Translations;
use Translation_Entry;
use TripleBits\WpFramework\Adapter\Dto\JedDto;

class Translation
{
    public const DEFAULT_LANGUAGE = 'en';

    public const DEFAULT_PLURAL_FORMS = 'nplurals=2; plural=n != 1;';

    /** @var string */
    private $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    public function toJed(): JedDto
    {
        $translations = $this->provideTranslations();

        $jed = new JedDto;
        $jed->setDomain($this->domain);
        $jed->setLanguage($this->getLanguage($translations->headers));
        $jed->setPluralForms($this->getPluralForms($translations->headers));

        /**
         * @var string $key
         * @var Translation_Entry $value
         */
        foreach($translations->entries as $key => $value) {
            $jed->addTranslations($key, $value->translations);
        }

        return $jed;
    }

    /**
     * @return Translations|NOOP_Translations
     */
    private function provideTranslations()
    {
        return get_translations_for_domain($this->domain);
    }

    private function getLanguage(array $headers = []): string
    {
        if (isset($headers['Language']) && $headers['Language']) {
            return strtolower($headers['Language']);
        }

        return self::DEFAULT_LANGUAGE;
    }

    private function getPluralForms(array $headers = []): string
    {
        if (isset($headers['Plural-Forms']) && $headers['Plural-Forms']) {
            return (string) $headers['Plural-Forms'];
        }

        return self::DEFAULT_PLURAL_FORMS;
    }
}
