<?php

namespace AnujRNair\AnujNairBundle\Listeners\Error;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Templating\EngineInterface;

class ExceptionHandler
{

    private $template;

    function __construct(EngineInterface $template)
    {
        $this->template = $template;
    }

    /**
     * @param GetResponseEvent $event
     * @param HttpException $exception
     * @param string $format
     */
    public function handle(GetResponseEvent $event, $exception, $format = 'html')
    {
        $message = 'I\'m not sure what happened';
        $statusCode = 500;
        if ($exception instanceof HttpException) {
            $message = $exception->getMessage();
            $statusCode = $exception->getStatusCode();
        }

        $baseDirectory = 'AnujNairBundle:Error:';
        try {
            $renderedView = $this->template->render("{$baseDirectory}error{$exception->getStatusCode()}.$format.twig", [
                'statusCode' => $statusCode,
                'message'    => $message
            ]);
        } catch (\Exception $e) {
            $renderedView = $this->template->render("{$baseDirectory}error.html.twig", [
                'statusCode' => $statusCode,
                'message'    => $message
            ]);
        }
        $response = Response::create($renderedView, $statusCode);
        $event->stopPropagation();
        $event->setResponse($response);
    }

    public function redirect(GetResponseEvent $event, $url)
    {
        $response = RedirectResponse::create($url);
        $event->stopPropagation();
        $event->setResponse($response);
    }

}