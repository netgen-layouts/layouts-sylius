<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\ChannelMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ChannelType as ParameterType;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(ChannelMapper::class)]
final class ChannelMapperTest extends TestCase
{
    private ChannelMapper $mapper;

    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface>
     */
    private Stub&ChannelRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(ChannelRepositoryInterface::class);

        $this->mapper = new ChannelMapper($this->repositoryStub);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testMapOptions(): void
    {
        $channels = [
            new ChannelStub(1, 'WEBSHOP', 'Webshop'),
            new ChannelStub(2, 'OTHER_SHOP', 'Other shop'),
        ];

        $channelList = [
            'Webshop' => 1,
            'Other shop' => 2,
        ];

        $this->repositoryStub
            ->method('findAll')
            ->willReturn($channels);

        self::assertSame(
            [
                'multiple' => false,
                'choices' => $channelList,
            ],
            $this->mapper->mapOptions(
                ParameterDefinition::fromArray(
                    [
                        'type' => new ParameterType(),
                        'options' => [
                            'multiple' => false,
                        ],
                    ],
                ),
            ),
        );
    }
}
