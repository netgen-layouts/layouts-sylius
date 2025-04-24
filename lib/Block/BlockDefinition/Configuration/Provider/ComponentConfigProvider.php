<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Block\BlockDefinition\Configuration\Provider;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ConfigProviderInterface;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ItemViewType;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType;

class ComponentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType[]>
     */
    private array $viewTypes = [];

    /**
     * @param mixed[] $viewConfig
     */
    public function __construct
    (
        private readonly array $viewConfig,
    ) {}

    public function provideViewTypes(?Block $block = null): array
    {
        if (!$block instanceof Block || !$block->hasParameter('component_type_identifier')) {
            return [];
        }

        if ($block->getParameter('component_type_identifier')->isEmpty()) {
            return [];
        }

        $componentTypeIdentifier = $block->getParameter('component_type_identifier')->getValue();

        $this->viewTypes[$block->getId()->toString()] ??= $this->buildViewTypes($this->resolveViewTypes($componentTypeIdentifier));

        return $this->viewTypes[$block->getId()->toString()];
    }

    /**
     * @return string[]
     */
    private function resolveViewTypes(string $componentTypeIdentifier): array
    {
        $syliusResourceDefaultViewConfig = $this->viewConfig['sylius_resource_view']['default'] ?? [];

        $viewTypes = [];
        foreach ($syliusResourceDefaultViewConfig as $config) {
            if (!in_array($componentTypeIdentifier, $this->getConfiguredComponentIdentifiers($config), true)) {
                continue;
            }

            $viewTypes = array_merge($viewTypes, $this->getConfiguredSyliusResourceViewTypes($config));
        }

        return array_unique($viewTypes);
    }

    /**
     * @return string[]
     */
    private function getConfiguredComponentIdentifiers(array $config): array
    {
        $value = $config['match']['sylius_component\identifier'] ?? [];

        return is_array($value) ? $value : [$value];
    }

    /**
     * @return string[]
     */
    private function getConfiguredSyliusResourceViewTypes(array $config): array
    {
        $value = $config['match']['sylius_resource\view_type'] ?? [];

        return is_array($value) ? $value : [$value];
    }

    /**
     * Returns the human-readable version of the provided string.
     */
    private function humanize(string $text): string
    {
        return ucwords(mb_strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text) ?? '')));
    }

    /**
     * @param string[] $validViews
     * @return array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType[]>
     */
    private function buildViewTypes(array $validViews): array
    {
        return array_combine(
            $validViews,
            array_map(
                fn (string $view) => ViewType::fromArray(
                    [
                        'identifier' => $view,
                        'name' => $this->humanize($view),
                        'itemViewTypes' => [
                            'standard' => ItemViewType::fromArray(
                                [
                                    'identifier' => 'standard',
                                    'name' => 'Standard',
                                ],
                            ),
                        ],
                        'validParameters' => null,
                    ],
                ),
                $validViews,
            ),
        );
    }
}
