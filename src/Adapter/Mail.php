<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Service\Adapter;

use App\Service\Adapter\Dto\MailDto;

class Mail
{
    public function send(MailDto $dto): bool
    {
        return wp_mail(
            $dto->getTo(),
            $dto->getSubject(),
            $dto->getBody(),
            $dto->generateWpMailHeaders()
        );
    }
}
