<?php

namespace App\Services;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
        private $token;

        public function __construct(TokenStorageInterface $token)
        {
                $this->token = $token;
        }

        public static function getSubscribedEvents() // implements EventSubscriberInterface => mettre la function déclaré dedans ici
        {
                return [
                        KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
                ];
        }

        public function getAuthenticatedUser(ViewEvent $event)
        {
                $entity = $event->getControllerResult(); // récupère l'entité qui a déclenché l'événement
                $method = $event->getRequest()->getMethod(); // récupère la méthode invoquée dans l'événement
                $adherent = $this->token->getToken()->getUser(); // récupère l'utilisateur actuellement connecté
                if ($entity instanceof Pret && $method === Request::METHOD_POST) { // si Entité Pret et méthode POST
                        $entity->setAdherent($adherent); // alors on lie notre adherent co à notre Pret
                }
                return;
        }
}