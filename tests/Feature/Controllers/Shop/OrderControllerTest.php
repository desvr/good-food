<?php

namespace Tests\Feature\Controllers\Shop;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\DTO\CartAddProductOutputDTO;
use App\Enum\OrderStatus;
use App\Enum\ProductType;
use App\Events\CreatedOrderEvent;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Shop\OrderController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\Feature\BaseFeatureTestCase;

class OrderControllerTest extends BaseFeatureTestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function providerNewOrderData(): array
    {
        return [
            [
                [
                    'name' => 'User',
                    'phone' => '71111111111',
                    'shipping_type' => 'delivery',
                    'payment_method' => 'stripe_checkout',
                    'number_persons' => 2,
                    'shipping_type_delivery' => 'Новый город / Верхняя терасса / Нижняя терасса',
                    'delivery_address' => [
                        'street' => 'Ульяновский проспект',
                        'house' => '11',
                        'porch' => '3',
                        'floor' => '4',
                        'flat' => '170',
                    ],
                    'is_preorder' => 'true',
                    'preorder_date' => '12/04/2025',
                    'preorder_time' => '13:00',
                    'note' => 'Комментарий',
                    'no_request_send' => '1',
                ],
                [
                    'active' => 1,
                    'name'   => 'Тестовый товар',
                    'slug'   => 'test-tovar',
                    'type'   => ProductType::PRODUCT->value,
                    'label'  => 'NEW',
                    'price'  => 100,
                ],
                [
                    'user_id'          => 1,
                    'status'           => OrderStatus::OPEN->value,
                    'phone'            => '71111111111',
                    'delivery_area'    => 'Новый город / Верхняя терасса / Нижняя терасса',
                    'delivery_address' => 'Ульяновский проспект, д. 11, подъезд 3, этаж 4, кв. 170',
                ],
            ],
        ];
    }

    /**
     * @covers OrderController::createOrder
     * @route  order.store
     *
     * @dataProvider providerNewOrderData()
     */
    public function testCreateOrderWithEmptyCart($order_data)
    {
        $this->addPayments();
        $this->createAndLoginUser();

        $response = $this->post(route('order.store'), $order_data);

        $response->assertRedirect(route('home'));
    }

    /**
     * @covers OrderController::createOrder
     * @route  order.store
     *
     * @dataProvider providerNewOrderData()
     */
    public function testCreateOrder($order_data, $product_data, $order_result)
    {
        $this->addPayments();

        Event::fake([CreatedOrderEvent::class]);

        $product = Product::create($product_data);
        $cart_service = app(CartServiceContract::class);
        $product_dto = CartAddProductOutputDTO::from($product);
        $cart_service->addProductToCart($product_dto);

        $user = $this->createAndLoginUser();
        $response = $this->actingAs($user)->post(route('order.store'), $order_data);

        $response->assertRedirectContains('https://checkout.stripe.com');

        Event::assertDispatched(CreatedOrderEvent::class);

        $this->assertDatabaseHas((new Order())->getTable(), $order_result);
    }
}
