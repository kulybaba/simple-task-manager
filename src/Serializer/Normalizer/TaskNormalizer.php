<?php

namespace App\Serializer\Normalizer;

use App\Entity\Task;
use App\Repository\ProjectRepository;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class TaskNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    /** @var ObjectNormalizer $normalizer */
    private $normalizer;

    /** @var ProjectRepository $projectRepository */
    private $projectRepository;

    /**
     * @param ObjectNormalizer $normalizer
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ObjectNormalizer $normalizer, ProjectRepository $projectRepository)
    {
        $this->normalizer = $normalizer;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param Task $object
     * @param null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = array()): array
    {
        return [
            'id' => $object->getId(),
            'text' => $object->getText(),
            'position' => $object->getPosition(),
        ];
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return Task|array|object
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $task = new Task();

        if (isset($data['text'])) {
            $task->setText($data['text']);
        }

        if (isset($data['projectId'])) {
            $task->setProject($this->projectRepository->findOneBy(['id' => $data['projectId']]));
        }

        return $task;
    }

    /**
     * @param mixed $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Task;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return Task::class === $type;
    }

    /**
     * @return bool
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
