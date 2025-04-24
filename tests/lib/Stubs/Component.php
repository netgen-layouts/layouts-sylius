<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Netgen\Layouts\Sylius\Component\AbstractComponent;
use Sylius\Resource\Model\TranslationInterface;

final class Component extends AbstractComponent
{
    private string $name;

    public function __construct(
        int $id,
        string $name,
        bool $enabled = true,
        string $currentLocale = 'en',
    ) {
        parent::__construct();

        $this->id = $id;
        $this->name = $name;
        $this->enabled = $enabled;
        $this->currentLocale = $currentLocale;
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
