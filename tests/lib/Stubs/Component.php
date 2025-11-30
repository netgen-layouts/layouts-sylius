<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Netgen\Layouts\Sylius\Component\AbstractComponent;
use Sylius\Resource\Model\TranslationInterface;

final class Component extends AbstractComponent
{
    public function __construct(
        int $id,
        private string $name,
        bool $enabled = true,
        string $locale = 'en',
    ) {
        parent::__construct();

        $this->id = $id;

        $this->setEnabled($enabled);
        $this->setCurrentLocale($locale);
        $this->setFallbackLocale($locale);
    }

    public static function getIdentifier(): string
    {
        return 'component_stub';
    }

    protected function createTranslation(): TranslationInterface
    {
        return new ComponentTranslation($this->id, $this->name);
    }
}
