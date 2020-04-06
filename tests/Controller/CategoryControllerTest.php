<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Tests\AppTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends AppTestCase
{
    public function testTagShow(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/category');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals('App\Controller\CategoryController::getCategories', $this->client->getRequest()->attributes->get('_controller'));
    }

    public function testShowTagPosts(): void
    {
        $slug = $this->doctrine->getRepository(Category::class)->findOneBy(['id' => 1])->getSlug();
        $crawler = $this->client->request(Request::METHOD_GET, "/category/{$slug}");
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals('App\Controller\CategoryController::getCategoryPosts', $this->client->getRequest()->attributes->get('_controller'));
    }
}
