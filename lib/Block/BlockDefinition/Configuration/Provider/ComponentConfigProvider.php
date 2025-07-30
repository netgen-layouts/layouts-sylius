<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Block\BlockDefinition\Configuration\Provider;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ConfigProviderInterface;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ItemViewType;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType;

use function array_combine;
use function array_map;
use function array_unique;
use function in_array;
use function mb_strtolower;
use function preg_replace;
use function trim;
use function ucwords;

final class ComponentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType[]>
     */
    private array $viewTypes = [];

    /**
     * @param mixed[] $viewConfig
     */
    public function __construct(private array $viewConfig) {}

    public function provideViewTypes(?Block $block = null): array
    {
        if (!$block instanceof Block || !$block->hasParameter('component_type')) {
            return [];
        }

        if ($block->getParameter('component_type')->isEmpty()) {
            return [];
        }

        $componentType = $block->getParameter('component_type')->getValue();

        $this->viewTypes[$block->getId()->toString()] ??= $this->buildViewTypes(
            $this->resolveViewTypes($componentType),
        );

        return $this->viewTypes[$block->getId()->toString()];
    }

    /**
     * @return string[]
     */
    private function resolveViewTypes(string $componentType): array
    {
        $defaultViewConfig = $this->viewConfig['sylius_resource_view']['default'] ?? [];

        $viewTypes = [];
        foreach ($defaultViewConfig as $config) {
            if (!in_array($componentType, (array) ($config['match']['sylius_component\identifier'] ?? []), true)) {
                continue;
            }

            $viewTypes = [...$viewTypes, ...((array) ($config['match']['sylius_resource\view_type'] ?? []))];
        }

        return array_unique($viewTypes);
    }

    /**
     * @param string[] $validViews
     *
     * @return array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType>
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

    /**
     * Returns the human-readable version of the provided string.
     */
    private function humanize(string $text): string
    {
        return ucwords(mb_strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text) ?? '')));
    }
}
