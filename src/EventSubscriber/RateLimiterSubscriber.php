<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimiterSubscriber implements EventSubscriberInterface
{
    private RateLimiterFactory $anonymousApiLimiter;

    public function __construct(RateLimiterFactory $anonymousApiLimiter)
    {
        $this->anonymousApiLimiter = $anonymousApiLimiter;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        $limiter = $this->anonymousApiLimiter->create($request->getClientIp() . '_' . $routeName);

        if (false === $limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
