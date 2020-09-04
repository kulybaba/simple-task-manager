<?php

namespace App\Controller;

use App\Entity\Project;
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
class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project_create", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidateService $validateService
    ) {
        /** @var Project $project */
        $project = $serializer->deserialize($request->getContent(), Project::class, JsonEncoder::FORMAT);
        $validateService->validate($project, ['validation_groups' => 'add-project']);
        $project->setUser($this->getUser());

        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'project' => $project,
        ]);
    }
}
