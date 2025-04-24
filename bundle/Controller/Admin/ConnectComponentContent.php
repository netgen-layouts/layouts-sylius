<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Controller\Admin;

use Netgen\Layouts\Core\Service\BlockService;
use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\Block\BlockDefinition\Handler\ComponentHandler;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\ItemValue;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Netgen\Layouts\API\Values\Block\Block;

class ConnectComponentContent extends Controller
{
    public function __construct
    (
        private readonly BlockService $blockService,
        private readonly ComponentRepositoryInterface $componentRepository,
    ) {}

    public function __invoke(Request $request, Block $block, string $componentIdentifier, int $componentId): Response
    {
        if (!$block->getDefinition()->getHandler() instanceof ComponentHandler) {
            throw new BadRequestHttpException();
        }

        $component = $this->componentRepository->load($componentIdentifier, $componentId);

        if (!$component instanceof ComponentInterface) {
            throw new BadRequestHttpException();
        }

        $blockUpdateStruct = $this->blockService->newBlockUpdateStruct($block->getLocale());
        $blockUpdateStruct->setParameterValue('content', ItemValue::fromComponent($component)->getValue());

        $this->blockService->updateBlock($block, $blockUpdateStruct);

        return new Response();
    }

    public function checkPermissions(): void
    {
        return; // TODO: fix
    }
}
