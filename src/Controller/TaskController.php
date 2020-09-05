<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
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
     * @Route("/task/{id}", requirements={"id"="\d+"}, name="task_edit", methods={"PUT"})
     */
    public function edit(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidateService $validateService,
        Task $task
    ) {
        /** @var Task $taskWithText */
        $taskWithText = $serializer->deserialize($request->getContent(), Task::class, JsonEncoder::FORMAT);
        $validateService->validate($taskWithText, ['validation_groups' => 'edit-task']);

        $task->setText($taskWithText->getText());
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * @Route("/task/{id}", requirements={"id"="\d+"}, name="task_delete", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $entityManager, Task $task)
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json([
            'success' => true,
        ]);
    }

    /**
     * @Route("/task/{id}/completion", requirements={"id"="\d+"}, name="task_completion", methods={"POST"})
     */
    public function completion(EntityManagerInterface $entityManager, Task $task)
    {
        $task->setCompleted(!$task->isCompleted());
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'completed' => $task->isCompleted(),
        ]);
    }

    /**
     * @Route("/task/sort", name="task_sort", methods={"POST"})
     */
    public function sort(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository)
    {
        $i = 1;
        $data = json_decode($request->getContent(), true);
        foreach ($data['tasks'] as $id) {
            /** @var Task $task */
            $task = $taskRepository->findOneBy(['id' => $id]);
            $task->setPosition($i);

            $entityManager->persist($task);
            $entityManager->flush();
            $i++;
        }

        return $this->json([
            'success' => true,
        ]);
    }
}
