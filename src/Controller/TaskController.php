<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/add/{id}", name="task_add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, Project $project)
    {
        /** @var Task $task */
        $task = $serializer->deserialize($request->getContent(), Task::class, JsonEncoder::FORMAT);
        $task->setProject($project);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            'success' => true,
        ]);
    }
}
