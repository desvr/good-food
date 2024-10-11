<?php

namespace Tests\Unit\Services\Shop\Orders;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderProductServiceContract;
use App\Services\Shop\Orders\OrderProductService;
use Tests\TestCase;

class OrderProductServiceTest extends TestCase
{
    private OrderProductServiceContract $orderProductService;
    private CartServiceContract $cartService;

    public function setUp(): void
    {
        $this->cartService = $this->createMock(CartServiceContract::class);
        $this->orderProductService = new OrderProductService($this->cartService);
    }

    public function providerSaveOrderProducts(): array
    {
        return [
            [
                0,
                'Заказ не создан!'
            ],
        ];
    }

    /**
     * @covers OrderProductService::saveOrderProducts
     *
     * @dataProvider providerSaveOrderProducts
     *
     * @return void
     */
    public function testSaveOrderProductsFailure($orderId, $message)
    {
        try {
            $this->orderProductService->saveOrderProducts($orderId);
        } catch (\Exception $e) {
            $this->assertSame($message, $e->getMessage());
        }
    }
}
