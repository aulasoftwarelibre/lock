<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ParseQrCodeMessage;
use App\Repository\SecretRepository;
use chillerlan\QRCode\QRCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;
use Vich\UploaderBundle\Storage\StorageInterface;

#[AsMessageHandler]
final class ParseQrCodeMessageHandler
{
    public function __construct(
        private readonly StorageInterface $storage,
        private readonly SecretRepository $secretRepository,
    ) {
    }

    public function __invoke(ParseQrCodeMessage $message): void
    {
        $id     = $message->getId();
        $entity = $this->secretRepository->find($id);

        if (! $entity) {
            return;
        }

        $path = $this->storage->resolvePath($entity, 'imageFile');

        if (! $path) {
            $entity->setSecret(null);

            return;
        }

        try {
            $result = (new QRCode())->readFromFile($path);
            $secret = Request::create($result->data)->query->get('secret', '');
            $entity->setSecret($secret);
        } catch (Throwable) {
            $entity->setSecret(null);
        }
    }
}
