<?php

namespace App\Tests\Controller;

use App\Entity\Tag;
use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagControllerTest extends AppTestCase
{
    public function testTagShow(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/tag');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals('App\Controller\TagController::getTags', $this->client->getRequest()->attributes->get('_controller'));
    }

    public function testShowTagPosts(): void
    {
        $slug = $this->doctrine->getRepository(Tag::class)->findOneBy(['id' => 1])->getSlug();
        $crawler = $this->client->request(Request::METHOD_GET, "/tag/{$slug}");
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals('App\Controller\TagController::getTagPosts', $this->client->getRequest()->attributes->get('_controller'));
    }
}
