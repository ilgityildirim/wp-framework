<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

trait RedirectTrait
{
    public function redirectToRef(): bool
    {
        return wp_safe_redirect(wp_get_referer());
    }

    public function redirectTo($url): bool
    {
        return wp_safe_redirect($url);
    }
}
