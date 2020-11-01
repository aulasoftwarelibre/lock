<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Message\SendNewActivationCodeMessage;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function sendQrCodeToUser(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (! $entity instanceof User) {
            return;
        }

        $this->messageBus->dispatch(
            new SendNewActivationCodeMessage($entity->getUsername())
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [AfterEntityPersistedEvent::class => 'sendQrCodeToUser'];
    }
}
