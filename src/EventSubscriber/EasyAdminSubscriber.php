<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Message\SendNewActivationCodeMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function sendQrCodeToUser(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        dump($entity);

        if (!$entity instanceof User) {
            return;
        }

        $this->messageBus->dispatch(
            new SendNewActivationCodeMessage($entity->getUsername())
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => 'sendQrCodeToUser',
        ];
    }
}
