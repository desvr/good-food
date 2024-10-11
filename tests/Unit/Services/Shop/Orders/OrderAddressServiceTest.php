<?php

namespace Tests\Unit\Services\Shop\Orders;

use App\Contracts\Shop\Services\Orders\OrderAddressServiceContract;
use App\Enum\ShippingType;
use App\Services\Shop\Orders\OrderAddressService;
use Tests\TestCase;

class OrderAddressServiceTest extends TestCase
{
    private OrderAddressServiceContract $orderAddressService;

    public function setUp(): void
    {
        $this->orderAddressService = new OrderAddressService();
    }

    public function providerPrepareDeliveryAddressData(): array
    {
        return [
            [
                [],
                '',
            ],
            [
                [
                    'shipping_type' => '',
                    'delivery_address' => [],
                ],
                '',
            ],
            [
                [
                    'shipping_type' => ShippingType::DELIVERY->value,
                    'delivery_address' => [],
                ],
                '',
            ],
            [
                [
                    'shipping_type' => ShippingType::PICKUP->value,
                    'shipping_type_' . ShippingType::PICKUP->value => 'Main pickup address',
                ],
                'Main pickup address',
            ],
            [
                [
                    'shipping_type' => ShippingType::PICKUP->value,
                    'shipping_type_' . ShippingType::PICKUP->value => '',
                ],
                '',
            ],
            [
                [
                    'shipping_type' => ShippingType::DELIVERY->value,
                    'delivery_address' => [
                        'street' => 'Врача Сурова',
                        'house' => '7',
                        'porch' => '1',
                        'floor' => '5',
                        'flat' => '34',
                    ],
                ],
                'Врача Сурова, д. 7, подъезд 1, этаж 5, кв. 34',
            ],
            [
                [
                    'shipping_type' => ShippingType::DELIVERY->value,
                    'delivery_address' => [
                        'street' => 'Врача Сурова',
                        'house' => '7',
                    ],
                ],
                'Врача Сурова, д. 7, , , ',
            ],
            [
                [
                    'shipping_type' => ShippingType::DELIVERY->value,
                    'delivery_address' => [
                        'street' => '',
                        'house' => '',
                        'porch' => '',
                        'floor' => '',
                        'flat' => '',
                    ],
                ],
                '',
            ],
        ];
    }

    /**
     * @covers OrderAddressService::prepareDeliveryAddressData
     *
     * @dataProvider providerPrepareDeliveryAddressData
     *
     * @return void
     */
    public function testPrepareDeliveryAddressData($orderData, $result)
    {
        $deliveryAreaData = $this->orderAddressService->prepareDeliveryAddressData($orderData);

        $this->assertSame($result, $deliveryAreaData);
    }

    public function providerPrepareDeliveryAreaData(): array
    {
        return [
            [
                [],
                '',
            ],
            [
                [
                    'shipping_type' => 'any',
                ],
                '',
            ],
            [
                [
                    'shipping_type' => ShippingType::DELIVERY->value,
                    'shipping_type_' . ShippingType::DELIVERY->value => 'Main delivery address',
                ],
                'Main delivery address',
            ]
        ];
    }

    /**
     * @covers OrderAddressService::prepareDeliveryAreaData
     *
     * @dataProvider providerPrepareDeliveryAreaData
     *
     * @return void
     */
    public function testPrepareDeliveryAreaData($orderData, $result)
    {
        $deliveryAreaData = $this->orderAddressService->prepareDeliveryAreaData($orderData);

        $this->assertSame($result, $deliveryAreaData);
    }
}
