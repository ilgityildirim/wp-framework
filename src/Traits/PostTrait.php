<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

trait PostTrait
{
    public function isPost(): bool
    {
        // PHP might not actively set $_POST global in some env
        return 'POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD') && $_POST;
    }

    public function isValidAdmin(?string $action = null, ?string $key = null): bool
    {
        return $this->isPost()
            && (bool) check_admin_referer($this->providePostAction($action), $key ?? '_wpnonce')
        ;
    }

    public function isValid(?string $action = null, ?string $key = null): bool
    {
        return $this->isPost() && (bool) wp_verify_nonce($_POST[$key] ?? '', $this->providePostAction($action));
    }

    /**
     * @param string|null $action
     *
     * @return string|int
     */
    private function providePostAction(?string $action = null)
    {
        if (null === $action) {
            return -1;
        }
        return $action;
    }
}
