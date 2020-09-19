<?php

namespace App\Service;

use App\Exception\JsonHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{
    /** @var ValidatorInterface $validator */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     * @param array $groups
     */
    public function validate($object, array $groups = [])
    {
        $errors = $this->validator->validate($object, null, $groups);

        if ($errors->count()) {
            $validationErrors = [];
            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                if ($propertyPath === 'plainPassword') {
                    $propertyPath = 'password';
                }
                $validationErrors[$propertyPath] = $error->getMessage();
            }

            throw new JsonHttpException(400, 'Bad request', $validationErrors);
        }
    }
}
