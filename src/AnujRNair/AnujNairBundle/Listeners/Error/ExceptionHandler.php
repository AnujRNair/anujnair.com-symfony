<?php

namespace AnujRNair\AnujNairBundle\Listeners\Error;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;

class ExceptionHandler
{

    private $template;

    function __construct(EngineInterface $template)
    {
        $this->template = $template;
    }

    /**
     * Handle an exception and display the correct error message. Firstly check
     * for a errorXXX.format.twig file, otherwise default to error.html.twig
     * @param GetResponseEvent $event
     * @param HttpException $exception
     * @param string $format
     */
    public function handle(GetResponseEvent $event, $exception, $format = 'html')
    {
        $message = $exception->getMessage();
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof AccessDeniedException) {
            $statusCode = $exception->getCode();
        } else {
            $statusCode = 500;
        }

        $baseDirectory = 'AnujNairBundle:Error:';
        try {
            $renderedView = $this->template->render("{$baseDirectory}error{$statusCode}.$format.twig", [
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

}