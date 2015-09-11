<?php

namespace AnujRNair\AnujNairBundle\Listeners\Error;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{

    /** @var ExceptionHandler $exceptionHandler */
    private $exceptionHandler;

    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /** @var HttpException $exception */
        $exception = $event->getException();
        $request = $event->getRequest();

        if ($exception instanceof NotFoundHttpException) {
            // 404
            $this->exceptionHandler->handle($event, $exception, $request->getRequestFormat());
        } else if ($exception instanceof AccessDeniedHttpException) {
            // 403
            $this->exceptionHandler->handle($event, $exception, $request->getRequestFormat());
        } else {
            // 500 & everything else
            $this->exceptionHandler->handle($event, $exception, $request->getRequestFormat());
        }
    }

}
