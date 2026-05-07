<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VisitCounterSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $session = $event->getRequest()->getSession();

        $visitedUrls = $session->get('visited_urls', []);
        $currentUrl = $event->getRequest()->getPathInfo();

        if (!in_array($currentUrl, $visitedUrls, true)) {
            $visitedUrls[] = $currentUrl;
            $session->set('visited_urls', $visitedUrls);
        }

        $session->set('visit_count', count($visitedUrls));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
