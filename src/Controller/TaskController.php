<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\ValidateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task_add", methods={"POST"})
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidateService $validateService
    ) {
        $task = $serializer->deserialize($request->getContent(), Task::class, JsonEncoder::FORMAT);
        $validateService->validate($task, ['validation_groups' => 'add-task']);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * @Route("/task/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, Task $task)
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json([
            'success' => true,
        ]);
    }
}
