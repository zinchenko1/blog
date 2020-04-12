<?php

namespace App\Tests\Controller;

use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends AppTestCase
{
    public function testUnauthenticated(): void
    {
        $this->client->request(Request::METHOD_GET, '/admin/?action=list&entity=Post');
        $this->assertEquals(Response::HTTP_FOUND,  $this->client->getResponse()->getStatusCode());
    }

    public function testIsLoginined()
    {
        $this->logIn('admin@example.com', 'test');
        $this->client->request(Request::METHOD_GET, '/admin/?action=list&entity=Post');
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
