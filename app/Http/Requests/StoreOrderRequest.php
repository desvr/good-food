<?php

namespace App\Http\Requests;

use App\Enum\ShippingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:127',
            'phone' => 'required|string|max:24',
            'shipping_type' => 'required|string|max:32|' . Rule::in(ShippingType::getShippingTypeList()),
            'payment_method' => 'required|string|exists:payments,method|max:64',
            'number_persons' => 'required|numeric|max:3',
            'shipping_type_pickup' => 'nullable|string',
            'shipping_type_delivery' => 'nullable|string',
            'delivery_address' => 'nullable|array',
            'is_preorder' => 'nullable|string',
            'preorder_date' => 'nullable|string',
            'preorder_time' => 'nullable|string',
            'note' => 'nullable|string',
            'no_request_send' => 'nullable|string',
        ];
    }
}
