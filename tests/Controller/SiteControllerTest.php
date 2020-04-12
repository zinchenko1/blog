<?php

namespace App\Tests\Controller;

use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteControllerTest extends AppTestCase
{
    public function testHomeShow(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/');
        $response = $this->client->getResponse();
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertContains('Blog', $crawler->filter('title')->text());
        self::assertSelectorTextContains('html h1', 'All posts');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('App\Controller\SiteController::index', $this->client->getRequest()->attributes->get('_controller'));
    }
}
