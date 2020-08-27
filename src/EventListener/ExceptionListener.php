<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * ExceptionListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     * @return ExceptionEvent
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof JsonHttpException) {
            $errorData = [
                'errors' => [
                    'code' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                    'fields' => $exception->getData()
                ]
            ];

            if ($data = $exception->getData()) {
                if (isset($data['code'])) {
                    $errorData['error']['code'] = $data['code'];
                }
            }

            $response = new JsonResponse($errorData);
            $event->setResponse($response);
            $this->logger->error($exception->getMessage(), $errorData);

            return $event;
        }

        return $event;
    }
}
