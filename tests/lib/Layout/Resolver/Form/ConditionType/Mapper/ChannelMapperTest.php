<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\ChannelMapper;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(ChannelMapper::class)]
final class ChannelMapperTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface>
     */
    private Stub&ChannelRepositoryInterface $channelRepositoryStub;

    private ChannelMapper $mapper;

    protected function setUp(): void
    {
        $this->channelRepositoryStub = self::createStub(ChannelRepositoryInterface::class);

        $this->mapper = new ChannelMapper(
            $this->channelRepositoryStub,
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

        $this->channelRepositoryStub
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
