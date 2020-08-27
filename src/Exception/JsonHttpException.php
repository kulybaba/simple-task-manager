<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonHttpException extends HttpException
{
    /** @var array $data */
    private array $data;

    /**
     * @param int $statusCode
     * @param string $message
     * @param array $data
     */
    public function __construct(int $statusCode, string $message, array $data = [])
    {
        parent::__construct($statusCode, $message);
        $this->setData($data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
