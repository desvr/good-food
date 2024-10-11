<?php

namespace Tests\Unit\Services\Shop\Orders;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderAddressServiceContract;
use App\Contracts\Shop\Services\Orders\OrderPrepareDataServiceContract;
use App\Services\Shop\Orders\OrderPrepareDataService;
use Darryldecode\Cart\CartCollection;
use Tests\TestCase;

class OrderPrepareDataServiceTest extends TestCase
{
    private OrderPrepareDataServiceContract $orderPrepareDataService;
    private CartServiceContract $cartService;
    private OrderAddressServiceContract $orderAddressService;

    public function setUp(): void
    {
        $this->cartService = $this->createMock(CartServiceContract::class);
        $this->orderAddressService = $this->createMock(OrderAddressServiceContract::class);
        $this->orderPrepareDataService = new OrderPrepareDataService($this->cartService, $this->orderAddressService);
    }

    public function providerPreparePreorderData(): array
    {
        return [
            [
                true,
                '01.01.2024',
                '13:00',
                '01.01.2024 13:00',
            ],
            [
                true,
                '',
                '',
                '',
            ],
            [
                true,
                '01.01.2024',
                '',
                '01.01.2024',
            ],
            [
                true,
                '',
                '13:00',
                '13:00',
            ],
            [
                false,
                '01.01.2024',
                '13:00',
                '',
            ],
            [
                false,
                '',
                '',
                '',
            ],
        ];
    }

    /**
     * @covers OrderPrepareDataService::preparePreorderData
     *
     * @dataProvider providerPreparePreorderData
     *
     * @return void
     */
    public function testPreparePreorderData($isPreorder, $preorderDate, $preorderTime, $result)
    {
        $reflectionMethod = new \ReflectionMethod($this->orderPrepareDataService, 'preparePreorderData');
        $productAndPriceData = $reflectionMethod->invoke($this->orderPrepareDataService, $isPreorder, $preorderDate, $preorderTime);

        $this->assertSame($result, $productAndPriceData);
    }

    public function providerPrepareProductAndPriceData(): array
    {
        return [
            [
                [
                    'total_without_conditions' => 1000,
                    'total' => 500,
                ],
                [
                    1000,
                    500,
                ]
            ],
            [
                [],
                [
                    0,
                    0,
                ]
            ],
        ];
    }

    /**
     * @covers OrderPrepareDataService::prepareProductAndPriceData
     *
     * @dataProvider providerPrepareProductAndPriceData
     *
     * @return void
     */
    public function testPrepareProductAndPriceData($cartContent, $result)
    {
        $this->cartService->expects($this->once())
            ->method('getCartContent')
            ->willReturn(new CartCollection());

        $this->cartService->expects($this->once())
            ->method('getPriceCartContent')
            ->willReturn($cartContent);

        $reflectionMethod = new \ReflectionMethod($this->orderPrepareDataService, 'prepareProductAndPriceData');
        $productAndPriceData = $reflectionMethod->invoke($this->orderPrepareDataService);

        $this->assertSame($result, $productAndPriceData);
    }

    public function providerPrepareConditionData(): array
    {
        return [
            [
                [
                    'sum_value' => 500,
                    'promotions' => [
                        [
                            'name' => 'ТЕСТ500',
                        ],
                    ],
                ],
                [
                    'promotions' => [
                        'sum_value' => 500,
                        'name' => 'ТЕСТ500',
                    ],
                ]
            ],
            [
                [
                    'sum_value' => 1500,
                    'promotions' => [
                        [
                            'name' => 'ТЕСТ500',
                        ],
                        [
                            'name' => 'ТЕСТ1000',
                        ],
                    ],
                ],
                [
                    'promotions' => [
                        'sum_value' => 1500,
                        'name' => 'ТЕСТ1000',
                    ],
                ]
            ],
            [
                [],
                []
            ],
        ];
    }

    /**
     * @covers OrderPrepareDataService::prepareConditionData()
     *
     * @dataProvider providerPrepareConditionData
     *
     * @return void
     */
    public function testPrepareConditionData($applyedPromotions, $result)
    {
        $this->cartService->expects($this->once())
            ->method('getApplyedPromotions')
            ->willReturn($applyedPromotions);

        $reflectionMethod = new \ReflectionMethod($this->orderPrepareDataService, 'prepareConditionData');
        [, $preparedConditionData] = $reflectionMethod->invoke($this->orderPrepareDataService);

        $this->assertSame($result, $preparedConditionData);
    }

    public function providerGenerateReceiptCode(): array
    {
        return [
            [4, 4],
            [6, 6],
        ];
    }

    /**
     * @covers OrderPrepareDataService::generateReceiptCode
     *
     * @dataProvider providerGenerateReceiptCode
     *
     * @return void
     */
    public function testGenerateReceiptCode($receiptCodeLength, $result)
    {
        $reflection = new \ReflectionClass($this->orderPrepareDataService);
        $propertyReceiptCodeLength = $reflection->getProperty('receiptCodeLength');
        $propertyReceiptCodeLength->setValue($this->orderPrepareDataService, $receiptCodeLength);

        $reflectionMethod = new \ReflectionMethod($this->orderPrepareDataService, 'generateReceiptCode');
        $receiptCode = $reflectionMethod->invoke($this->orderPrepareDataService);

        $this->assertSame($result, strlen($receiptCode));
    }
}
