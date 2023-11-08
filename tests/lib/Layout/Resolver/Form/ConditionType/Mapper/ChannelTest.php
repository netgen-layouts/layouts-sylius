<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(Channel::class)]
final class ChannelTest extends TestCase
{
    private ChannelRepositoryInterface&MockObject $channelRepository;

    private Channel $mapper;

    protected function setUp(): void
    {
        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);

        $this->mapper = new Channel(
            $this->channelRepository,
        );
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        $channels = [
            new ChannelStub(1, 'WEBSHOP', 'Webshop'),
            new ChannelStub(2, 'OTHER_SHOP', 'Other shop'),
        ];

        $channelList = [
            'Webshop' => 1,
            'Other shop' => 2,
        ];

        $this->channelRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($channels);

        self::assertSame(
            [
                'choices' => $channelList,
                'choice_translation_domain' => false,
                'multiple' => true,
                'expanded' => true,
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
