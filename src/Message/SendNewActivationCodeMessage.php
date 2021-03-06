<?php

declare(strict_types=1);

namespace App\Message;

final class SendNewActivationCodeMessage
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
