<?php

namespace App\Tests\Controller\Admin;

use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class AdminControllerTest extends AppTestCase
{
    public function testAddPostIsAvailable(): void
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?entity=Post&action=new');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testAddCategoryIsAvailable(): void
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?entity=Category&action=new');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testAddTagIsAvailable(): void
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?entity=Tag&action=new');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testAddAuthorIsAvailable(): void
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?entity=Author&action=new');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testAddAdminIsAvailable(): void
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?entity=Admin&action=new');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
