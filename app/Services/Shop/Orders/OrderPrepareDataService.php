<?php

namespace App\Services\Shop\Orders;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderAddressServiceContract;
use App\Contracts\Shop\Services\Orders\OrderPrepareDataServiceContract;
use App\DTO\OrderCreateInputDTO;
use App\Exceptions\OrderException;
use Illuminate\Support\Facades\Auth;

class OrderPrepareDataService implements OrderPrepareDataServiceContract
{
    private int $receiptCodeLength = 4;

    public function __construct(
        private readonly CartServiceContract $cart_service,
        private readonly OrderAddressServiceContract $order_address_service,
    ) {}

    /**
     * Prepare dataset for order
     *
     * @var OrderCreateInputDTO $order_data Order data
     *
     * @return array
     */
    public function prepareOrderDataset(OrderCreateInputDTO $order_data): array
    {
        $order_data = $order_data->toArray();

        $this->prepareUserId($order_data);

        $order_data['delivery_area'] = $this->order_address_service->prepareDeliveryAreaData($order_data);
        $order_data['delivery_address'] = $this->order_address_service->prepareDeliveryAddressData($order_data);
        $order_data['preorder_datetime'] = $this->preparePreorderDatetime($order_data);
        $order_data['request_send'] = $this->ckeckNoRequestSend($order_data);
        [$order_data['original_price'], $order_data['result_price']] = $this->prepareProductAndPriceData();
        [$order_data['condition_id'], $order_data['condition_data']] = $this->prepareConditionData();
        $order_data['receipt_code'] = $this->generateReceiptCode();

        return $order_data;
    }

    /**
     * Prepare user ID for order
     *
     * @var array $order_data Order data
     *
     * @return void
     *
     * @throws OrderException
     */
    private function prepareUserId(array &$order_data): void
    {
        if (Auth::guest()) {
            throw new OrderException('Для формирования заказа необходимо зарегистрироваться.');
        }

        $order_data['user_id'] = Auth::id();
    }

    /**
     * Prepare preorder data
     *
     * @var bool   $is_preorder   Is preorder
     * @var string $preorder_date Preorder date
     * @var string $preorder_time Preorder time
     *
     * @return string
     */
    private function preparePreorderData(bool $is_preorder, string $preorder_date, string $preorder_time): string
    {
        if (!$is_preorder) {
            return '';
        }

        return trim($preorder_date . ' ' . $preorder_time);
    }

    /**
     * Prepare product and price data
     *
     * @return array
     * @throws \Exception
     */
    private function prepareProductAndPriceData(): array
    {
        $cart_collection = $this->cart_service->getCartContent();
        $cart_price = $this->cart_service->getPriceCartContent($cart_collection);

        $original_price = $cart_price['total_without_conditions'] ?? 0;
        $result_price = $cart_price['total'] ?? 0;

        return [$original_price, $result_price];
    }

    /**
     * Prepare condition (promotion) data
     *
     * @return array
     */
    private function prepareConditionData(): array
    {
        $promotion_data = [];

        $cart_applyed_promotions = $this->cart_service->getApplyedPromotions();
        if (!empty($cart_applyed_promotions['promotions'])) {
            $promotion_data['promotions']['sum_value'] = $cart_applyed_promotions['sum_value'] ?? 0;

            foreach ($cart_applyed_promotions['promotions'] as $applyed_promotion) {
                $promotion = $applyed_promotion['name'];
            }

            $promotion_data['promotions']['name'] = $promotion;
        }

        /** TODO: После реализации промокодов добавить возвращение condition_id вместо 999 */
        return [999, $promotion_data];
    }

    /**
     * Generate receipt secret code
     *
     * @return int
     */
    private function generateReceiptCode(): int
    {
        return random_int(pow(10, $this->receiptCodeLength - 1), pow(10, $this->receiptCodeLength) - 1);
    }

    /**
     * Prepare preorder datetime if exist
     *
     * @var array $order_data Order data
     *
     * @return null|string
     */
    private function preparePreorderDatetime(array $order_data): ?string
    {
        return !empty($order_data['is_preorder'])
            ? $this->preparePreorderData($order_data['is_preorder'], $order_data['preorder_date'], $order_data['preorder_time'])
            : null;
    }

    /**
     * Check flag "No request send", checked on checkout page
     *
     * @var array $order_data Order data
     *
     * @return bool
     */
    private function ckeckNoRequestSend(array $order_data): bool
    {
        return empty($order_data['no_request_send']);
    }
}
