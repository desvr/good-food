<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Exceptions\PaymentException;
use App\Http\Controllers\Controller;
use App\Services\Shop\Payments\Processors\YookassaService;
use Illuminate\Http\Request;
use YooKassa\Client;

class YookassaController extends Controller
{
    protected Client $yookassa_client;

    public function __construct(
        protected OrderDataServiceContract $orderDataService,
        protected YookassaService $yookassaService,
    ) {
        $this->yookassa_client = (new Client())->setAuth(
            config('payments.yookassa.shop_id'),
            config('payments.yookassa.secret_key'),
        );
    }

    public function paymentCreate(Request $request)
    {
        $order_id = (int) $request->get('order_id', 0);
        if (empty($order_id)) {
            throw new PaymentException('Заказ отсутствует');
        }

        $url = $this->yookassaService->getPaymentUrl($order_id);

        return redirect()->away($url);
    }

    /**
     * {POST} Callback handle.
     */
    public function callback()
    {
        $source = @file_get_contents('php://input');

        $this->yookassaService->callback($source);

        return response('OK', 200);
    }
}
