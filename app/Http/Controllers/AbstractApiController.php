<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AbstractApiController
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * AbstractApiController constructor.
     *
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->data = [];
        $this->status = HttpResponse::HTTP_OK;
        $this->headers = [];
        $this->response = $response;
    }

    /**
     * Get the data
     *
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Set the data
     *
     * @param mixed $data
     */
    protected function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the Status Code
     *
     * @return int
     */
    protected function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * Set the Status Code
     *
     * @param int $status
     *
     * @return $this
     */
    protected function setStatusCode(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the headers
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the headers
     *
     * @param array $headers
     *
     * @return $this
     */
    protected function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Return the response
     *
     * @return JsonResponse
     */
    protected function respond(): JsonResponse
    {
        return $this->response->json($this->getData(), $this->getStatusCode(), $this->getHeaders());
    }

    /**
     * Respond Ok - 200
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function respondOk($data = []): JsonResponse
    {
        return $this->setData($data)
            ->setStatusCode(HttpResponse::HTTP_OK)
            ->respond();
    }

    /**
     * Respond Created - 201
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    public function respondCreated($data = []): JsonResponse
    {
        return $this->setData($data)
            ->setStatusCode(HttpResponse::HTTP_CREATED)
            ->respond();
    }
}

