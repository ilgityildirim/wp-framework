<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

trait RedirectAdminTrait
{
    use RedirectTrait;

    public function adminRedirectTo(array $args = []): bool
    {
        return wp_safe_redirect(add_query_arg($args, admin_url() . 'admin.php'));
    }
}
