<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use function array_key_exists;
use function is_string;

final class LocaleValidator extends ConstraintValidator
{
    private LocaleProviderInterface $localeProvider;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public function validate($value, Constraint $constraint): void
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

        $locales = $this->localeProvider->getAvailableLocales();

        if (!array_key_exists($value, $locales)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%locale%', (string) $value)
                ->addViolation();
        }
    }
}
