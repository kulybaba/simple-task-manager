<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    /**
     * @dataProvider taskProvider
     */
    public function testAdd($data, $statusCode)
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Project $project */
        $project = (static::$container->get(ProjectRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('POST', '/api/task', [], [], [], json_encode(array_merge($data, ['projectId' => $project->getId()])));
        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider taskProvider
     */
    public function testEdit($data, $statusCode)
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Task $task */
        $task = (static::$container->get(TaskRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('PUT', "/api/task/{$task->getId()}", [], [], [], json_encode($data));
        $this->assertEquals($statusCode, $client->getResponse()->getStatusCode());
    }

    public function testCheckUncheck()
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Task $task */
        $task = (static::$container->get(TaskRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('PUT', "/api/task/{$task->getId()}/completion");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();
        $user = (static::$container->get(UserRepository::class))->findOneBy(['email' => 'test@mail.com']);
        $client->loginUser($user);
        /** @var Task $task */
        $task = (static::$container->get(TaskRepository::class))->findOneBy([], ['id' => 'DESC']);
        $client->request('DELETE', "/api/task/{$task->getId()}");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function taskProvider()
    {
        return [
            [['text' => 'Test Task 1'], Response::HTTP_OK],
            [['text' => null], Response::HTTP_BAD_REQUEST],
        ];
    }
}
