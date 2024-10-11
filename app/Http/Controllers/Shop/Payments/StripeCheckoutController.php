<?php

namespace App\Http\Controllers\Shop\Payments;

use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Exceptions\PaymentException;
use App\Http\Controllers\Controller;
use App\Services\Shop\Payments\Processors\StripeCheckoutService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\StripeClient;

class StripeCheckoutController extends Controller
{
    protected StripeClient $stripe_client;

    public function __construct(
        protected OrderDataServiceContract $orderDataService,
        protected StripeCheckoutService $stripeCheckoutService,
    ) {
        $this->stripe_client = new StripeClient(config('payments.stripe_checkout.secret_key'));
    }

    public function checkoutSessionCreate(Request $request)
    {
        $order_id = (int) $request->get('order_id', 0);
        if (empty($order_id)) {
            throw new PaymentException('Заказ отсутствует');
        }

        $url = $this->stripeCheckoutService->getPaymentUrl($order_id);

        return redirect()->away($url);
    }

    public function success(Request $request)
    {
        $session_id = $request->get('session_id', '');
        if (empty($session_id)) {
            throw new PaymentException('Отсутствует session ID');
        }

        [$route, $params] = $this->stripeCheckoutService->success($session_id);

        return redirect()->route($route, $params);
    }

    public function cancel(Request $request)
    {
        $session_id = $request->get('session_id', '');
        if (empty($session_id)) {
            throw new PaymentException('Отсутствует session ID');
        }

        [$route, $params] = $this->stripeCheckoutService->cancel($session_id);

        return redirect()->route($route, $params);
    }

    public function webhookInstall(): void
    {
        $this->stripeCheckoutService->webhookInstall();
    }

    /**
     * {POST} Webhook handle.
     */
    public function webhookHandle(): Response
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        [$content, $status] = $this->stripeCheckoutService->webhookHandle($payload, $sig_header);

        return response($content, $status);
    }
}
