<?php

namespace App\EventSubscriber;

use App\Repository\ConferenceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private ConferenceRepository $conferenceRepository
    ) {}

    public function onKernelController(ControllerEvent $event): void
    {
        // Sprawdzamy, czy to główny request (nie sub-requesty np. z fragmentów ESI)
        if (!$event->isMainRequest()) {
            return;
        }


        $this->twig->addGlobal('conferences', $this->conferenceRepository->findAll());
        $this->twig->addGlobal('popular_conferences', $this->conferenceRepository->findWithMostComments(5));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
