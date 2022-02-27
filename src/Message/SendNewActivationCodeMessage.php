<?php

declare(strict_types=1);

namespace App\Message;

final class SendNewActivationCodeMessage
{
    public function __construct(private readonly string $username)
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
