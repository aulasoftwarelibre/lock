<?php

declare(strict_types=1);

namespace App\Controller\Security;

use RuntimeException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="logout")
 */
class LogoutController
{
    public function __invoke(): void
    {
        throw new RuntimeException('This method should not be called.');
    }
}
