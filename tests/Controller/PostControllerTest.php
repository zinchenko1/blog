<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class PostControllerTest extends AppTestCase
{
    public function testShow(): void
    {
        $slug = $this->doctrine->getRepository(Post::class)->findOneBy(['id' => 1])->getSlug();
        $crawler = $this->client->request(Request::METHOD_GET, "/post/{$slug}");
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals('App\Controller\PostController::getPost', $this->client->getRequest()->attributes->get('_controller'));
    }
}
