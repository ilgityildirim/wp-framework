<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Service\Adapter\Dto;

class MailDto
{
    /** @var string|null */
    private $from;

    /** @var string|null */
    private $fromName;

    /** @var string */
    private $to;

    /** @var string|null */
    private $replyTo;

    /** @var string|null */
    private $replyToName;

    /** @var string */
    private $subject;

    /** @var string */
    private $body;

    /** @var array */
    private $headers = [];

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(?string $fromName): void
    {
        $this->fromName = $fromName;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    public function setReplyTo(?string $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    public function getReplyToName(): ?string
    {
        return $this->replyToName;
    }

    public function setReplyToName(?string $replyToName): void
    {
        $this->replyToName = $replyToName;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function provideFrom(): ?string
    {
        if (!$this->from) {
            return null;
        }

        if (!$this->fromName) {
            return $this->from;
        }

        return sprintf('%s<%s>', $this->fromName, $this->from);
    }

    public function provideReplyTo(): ?string
    {
        if (!$this->replyTo) {
            return null;
        }

        if (!$this->replyToName) {
            return $this->replyTo;
        }

        return sprintf('%s<%s>', $this->replyToName, $this->replyTo);
    }

    public function generateWpMailHeaders(): array
    {
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];

        $from = $this->provideFrom();
        if ($from) {
            $headers[] = 'From: ' . $from;
        }

        $replyTo = $this->provideReplyTo();
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $headers = array_merge($headers, $this->headers);
        return array_unique($headers);
    }
}
