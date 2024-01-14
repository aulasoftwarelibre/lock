<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Secret;
use App\Entity\User;
use App\Message\ParseQrCodeMessage;
use App\Message\SendNewActivationCodeMessage;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function sendQrCodeToUser(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (! $entity instanceof User) {
            return;
        }

        $this->messageBus->dispatch(
            new SendNewActivationCodeMessage($entity->getUsername()),
        );
    }

    public function parseQrCode(AfterEntityUpdatedEvent|AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (! $entity instanceof Secret) {
            return;
        }

        $this->messageBus->dispatch(
            new ParseQrCodeMessage((int) $entity->getId()),
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => [
                ['sendQrCodeToUser'],
                ['parseQrCode'],
            ],
            AfterEntityUpdatedEvent::class => 'parseQrCode',
        ];
    }
}
