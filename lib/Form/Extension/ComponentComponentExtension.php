<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Form\Extension;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Sylius\Block\BlockDefinition\Handler\ComponentHandler;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class ComponentComponentExtension extends AbstractTypeExtension
{
    public function getExtendedType(): string
    {
        return ContentBrowserType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ContentBrowserType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $block = $view->parent?->parent?->vars['block'] ?? null;
        if (!$block instanceof Block) {
            return;
        }

        if (!$block->getDefinition()->getHandler() instanceof ComponentHandler) {
            return;
        }

        $view->vars['block_prefixes'][] = 'sylius_component_component';
    }
}
