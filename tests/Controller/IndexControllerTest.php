<?php

namespace App\Tests\COntroller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends WebTestCase
{
    public function testNotAuth()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Redirecting to /login');
    }

    public function testAuth()
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('title', 'Simple Task Manager');
        $this->assertCount(1, $crawler->filter('.project-card'));
        $this->assertCount(2, $crawler->filter('.task'));
    }
}
