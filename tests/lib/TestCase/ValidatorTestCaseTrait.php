<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\TestCase;

use Netgen\Layouts\Sylius\Tests\Validator\ValidatorFactory;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorTestCaseTrait
{
    /**
     * @param \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<covariant \Sylius\Resource\Model\ResourceInterface>|null $repository
     */
    private function createValidator(
        ?RepositoryInterface $repository = null,
    ): ValidatorInterface {
        $repository ??= self::createStub(RepositoryInterface::class);

        return Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(
                new ValidatorFactory($repository),
            )
            ->getValidator();
    }
}
