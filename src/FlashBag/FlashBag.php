<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\FlashBag;

class FlashBag
{
    public const NAME = 'flashBag';

    public const INFO = 'info';
    public const WARNING = 'warning';
    public const ERROR = 'error';
    public const SUCCESS = 'success';

    public const AVAILABLE_TYPES = [
        self::INFO,
        self::SUCCESS,
        self::WARNING,
        self::ERROR,
    ];

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $this->initialize();
    }

    public function get(?string $type = null): array
    {
        $messages = $type ? $_SESSION[self::NAME][$type] : $_SESSION[self::NAME];

        if ($type) {
            $_SESSION[self::NAME][$type] = [];
        } else {
            $_SESSION[self::NAME] = [];
        }

        return $messages;
    }

    public function success(string $message): void
    {
        $this->add($message, self::SUCCESS);
    }

    public function error(string $message): void
    {
        $this->add($message, self::ERROR);
    }

    public function warning(string $message): void
    {
        $this->add($message, self::WARNING);
    }

    public function info(string $message): void
    {
        $this->add($message, self::INFO);
    }

    private function add(string $message, string $type = self::INFO): void
    {
        if (in_array($message, $_SESSION[self::NAME][$type], true)) {
            return;
        }

        $_SESSION[self::NAME][$type][] = $message;
    }

    private function initialize(): void
    {
        if (!isset($_SESSION[self::NAME])) {
            $_SESSION[self::NAME] = [];
        }

        foreach (self::AVAILABLE_TYPES as $type) {
            if (!isset($_SESSION[self::NAME][$type])) {
                $_SESSION[self::NAME][$type] = [];
            }
        }
    }
}
