<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Repositories\OrderRepositoryContract;
use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Contracts\Shop\Services\Orders\OrderServiceContract;
use App\Contracts\Shop\Services\Payments\PaymentServiceContract;
use App\DTO\OrderCreateInputDTO;
use App\Enum\OrderDataType;
use App\Enum\OrderStatus;
use App\Enum\ShippingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Schemas\Shop\Order\OrderShowFieldsSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(OrderRepositoryContract $orderRepository)
    {
        $user_id = Auth::id();
        $orders_data = $orderRepository->getOrderDataByUser($user_id);
        $order_status_list = OrderStatus::getStatusListDescription();
        $shipping_type_list = ShippingType::getShippingTypeListDescription();

        return view('shop.pages.orders.orders_list', compact(
            'orders_data',
            'order_status_list',
            'shipping_type_list',
        ));
    }

    public function show(
        int $order_id,
        OrderRepositoryContract $orderRepository,
        OrderDataServiceContract $orderDataService
    ) {
        $order_data = $orderRepository->getOrderData($order_id);
        $order_history = $orderDataService->getOrderDataList('type', [$order_id], [OrderDataType::HISTORY->value]);
        $order_status_list = OrderStatus::getStatusListDescription();
        $order_schema = OrderShowFieldsSchema::getSchema($order_data);

        return view('shop.pages.orders.order', compact(
            'order_data',
            'order_status_list',
            'order_schema',
            'order_history',
        ));
    }

    /**
     * {POST} Create order.
     *
     * @param StoreOrderRequest $request
     * @param OrderServiceContract $orderService
     * @param PaymentServiceContract $paymentService
     *
     * @return RedirectResponse
     */
    public function store(
        StoreOrderRequest $request,
        OrderServiceContract $orderService,
        PaymentServiceContract $paymentService
    ) {
        $order_data = OrderCreateInputDTO::from($request->validated());

        try {
            $created_order = $orderService->createOrder($order_data);
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with([
                    'status' => 'danger',
                    'status_message' => $e->getMessage(),
                ]);
        }

        $payment_route = $paymentService->getPaymentUrl($order_data->payment_method ?? '', $created_order->id);
        if (!empty($payment_route)) {
            return redirect()->away($payment_route);
        }

        return redirect()->route('order.show', ['order_id' => $created_order->id])
            ->with([
                'status' => 'success',
                'status_message' => 'Заказ успешно создан!'
            ]);
    }
}
