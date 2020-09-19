<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebTestCase
{
    /**
     * @dataProvider projectProvider
     */
    public function testCreate($data, $statusCode)
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        $client->request('POST', '/api/project', [], [], [], json_encode($data));
        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider projectProvider
     */
    public function testEdit($data, $statusCode)
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Project $project */
        $project = (static::$container->get(ProjectRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('PUT', "/api/project/{$project->getId()}", [], [], [], json_encode($data));
        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Project $project */
        $project = (static::$container->get(ProjectRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('DELETE', "/api/project/{$project->getId()}");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function projectProvider()
    {
        return [
            [['name' => 'Test Project 1'], Response::HTTP_OK],
            [['name' => null], Response::HTTP_BAD_REQUEST],
        ];
    }
}
