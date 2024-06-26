<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_string;

final class LocaleValidator extends ConstraintValidator
{
    /**
     * @param \Sylius\Component\Resource\Repository\RepositoryInterface<\Sylius\Component\Resource\Model\ResourceInterface> $localeRepository
     */
    public function __construct(private RepositoryInterface $localeRepository) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Locale) {
            throw new UnexpectedTypeException($constraint, Locale::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $locale = $this->localeRepository->findOneBy(['code' => $value]);
        if (!$locale instanceof LocaleInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%locale%', $value)
                ->addViolation();
        }
    }
}
