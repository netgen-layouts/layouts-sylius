<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Controller\Admin;

use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Core\Service\BlockService;
use Netgen\Layouts\Sylius\Block\BlockDefinition\Handler\ComponentHandler;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ConnectComponent extends Controller
{
    public function __construct(
        private BlockService $blockService,
        private ComponentRepositoryInterface $componentRepository,
    ) {}

    public function __invoke(Request $request, Block $block, string $componentIdentifier, int $componentId): Response
    {
        if (!$block->getDefinition()->getHandler() instanceof ComponentHandler) {
            throw new BadRequestHttpException();
        }

        $componentId = new ComponentId($componentIdentifier, $componentId);

        $component = $this->componentRepository->load($componentId);

        if (!$component instanceof ComponentInterface) {
            throw new BadRequestHttpException();
        }

        $blockUpdateStruct = $this->blockService->newBlockUpdateStruct($block->getLocale());
        $blockUpdateStruct->setParameterValue('content', (string) ComponentId::fromComponent($component));

        $this->blockService->updateBlock($block, $blockUpdateStruct);

        return new Response();
    }

    public function checkPermissions(): void
    {
        if ($this->isGranted('ROLE_NGLAYOUTS_EDITOR')) {
            return;
        }

        if ($this->isGranted('nglayouts:ui:access')) {
            return;
        }

        $exception = $this->createAccessDeniedException();
        $exception->setAttributes('nglayouts:ui:access');

        throw $exception;
    }
}
