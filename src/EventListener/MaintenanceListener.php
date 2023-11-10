<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

#[AsEventListener(event: 'kernel.request', priority: 9999)]
class MaintenanceListener
{
    public function __construct(
        private readonly Environment $twig,
        #[Autowire(param: 'env(bool:APP_MAINTENANCE)')]
        private readonly bool $isMaintenance,
    ) {}

    public function __invoke(RequestEvent $event)
    {
        if ($this->isMaintenance) {
            $response = new Response();
            if ($event->isMainRequest()) {
                $response->setContent($this->twig->render('maintenance.html.twig'));
            }

            $event->setResponse($response);
        }
    }
}
