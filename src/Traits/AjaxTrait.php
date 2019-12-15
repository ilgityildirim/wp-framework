<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

trait AjaxTrait
{
    public function isAjax(): bool
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    public function isSecureAjax(?string $action = null, ?string $key = null, bool $die = false): bool
    {
        $_action = $action;
        if (null === $_action) {
            $_action = -1;
        }

        $_key = $key;
        if (null === $_key) {
            $_key = false;
        }

        return $this->isAjax() && (bool) check_ajax_referer($_action, $_key, $die);
    }
}
