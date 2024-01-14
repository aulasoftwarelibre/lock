<?php

declare(strict_types=1);

namespace App\Message;

final class ParseQrCodeMessage
{
    public function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
