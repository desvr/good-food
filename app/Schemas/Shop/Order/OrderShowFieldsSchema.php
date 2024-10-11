<?php

namespace App\Schemas\Shop\Order;

use App\Contracts\Common\SchemasContract;
use App\Enum\ShippingType;
use App\Exceptions\ShippingException;

class OrderShowFieldsSchema implements SchemasContract
{
    /**
     * Gets filled order view fields schema (used for route 'order.show')
     *
     * @param array $data Input data
     *
     * @return array
     *
     * @throws ShippingException
     */
    public static function getSchema(array $data = []): array
    {
        $shipping_method = ShippingType::getShippingTypeDescription($data['shipping_type']) ?? '';
        if (!empty($data['delivery_area'])) {
            $shipping_method .= ' (' . $data['delivery_area'] . ')';
        }

        $schema = [
            'main' => [
                'Имя получателя' => $data['name'] ?? '',
                'Телефон' => $data['phone'] ?? '',
                'Кол-во персон' => $data['number_persons'] ?? 0,
                'Комментарий' => $data['note'] ?? '',
                'Заказ без звонка' => $data['request_send'] ? '' : 'Да',
            ],
            'shipping' => [
                'Предзаказ на' => $data['preorder_datetime'] ?? 'Отсутствует',
                'Способ доставки' => $shipping_method,
            ],
            'payment' => [
                'Способ оплаты' => 'Картой онлайн',
            ],
            'total_price' => [
                'Общая стоимость' => $data['original_price'] . 'р.' ?? '',
                'Бонусные баллы' => $data['bonus_points'] ?? 0,
                'Промокод' => !empty($data['condition_data']['promotions']['sum_value'])
                    ? $data['condition_data']['promotions']['sum_value'] . ' р.'
                    : 0,
                'Итоговая сумма' => $data['result_price'] . 'р.' ?? '',
            ]
        ];

        if (ShippingType::isDeliveryShipping($data['shipping_type'])) {
            $schema['shipping']['Адрес доставки'] = !empty($data['delivery_address']) ? $data['delivery_address'] : '';
        } elseif (ShippingType::isPickupShipping($data['shipping_type'])) {
            $schema['shipping']['Самовывоз из'] = !empty($data['delivery_address']) ? $data['delivery_address'] : '';
        }

        return $schema;
    }
}
