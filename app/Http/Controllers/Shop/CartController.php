<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\DTO\CartAddProductInputDTO;
use App\Enum\ShippingType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Log;

class CartController extends Controller
{
    /**
     * {GET} Cart page.
     */
    public function index()
    {
        return view('shop.pages.cart');
    }

    /**
     * {GET} Checkout page.
     */
    public function checkout()
    {
        $shipping_type_list = ShippingType::getShippingTypeListDescription();
        $user = Auth::user();

        return view('shop.pages.checkout', compact(['shipping_type_list', 'user']));
    }

    /**
     * {POST} {Ajax} Added product to cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     */
    public function addProduct(Request $request, CartServiceContract $cartService)
    {
        if (
            empty($request->variation_data)
            && empty($request->product_id)
        ) {
            return response();
        }

        $is_modal = $request->get('is_modal') ?? false;

        $entity_data = $cartService->convertsAddingProductFromVariation(
            CartAddProductInputDTO::from($request->toArray())
        );

        try {
            $cart_total_price = $cartService->addProductToCart($entity_data);

            if (!empty($is_modal) && $is_modal === 'true') {
                $view_regulator_blade = 'shop.components.products.modals.button_product_cart_regulator_big';
            } else {
                $view_regulator_blade = 'shop.components.products.button_product_cart_regulator_medium';
            }

            return response()->json([
                'product_quantity_regulator' => Blade::render(
                    $view_regulator_blade,
                    [
                        'product_id' => $entity_data->id,
                        'product_quantity_cart' => $cartService->getProductQuantityCart($entity_data->id)
                    ],
                ),
                'cart_total_price' => $cart_total_price
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response();
    }

    /**
     * {POST} Increased product in the cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     * @param int                 $product_id
     */
    public function increaseProduct(Request $request, CartServiceContract $cartService, int $product_id = 0)
    {
        if (
            empty($product_id)
            && !empty($request->get('product_id'))
        ) {
            $product_id = $request->get('product_id');
            $is_modal = $request->get('is_modal') ?? false;
        }

        try {
            $cartService->increaseProductInCart($product_id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if ($request->ajax()) {
            if (!empty($is_modal) && $is_modal === 'true') {
                $view_regulator_blade = 'shop.components.products.modals.button_product_cart_regulator_big';
            } else {
                $view_regulator_blade = 'shop.components.products.button_product_cart_regulator_medium';
            }

            return response()->json([
                'product_quantity_regulator' => Blade::render(
                    $view_regulator_blade,
                    [
                        'product_id' => $product_id,
                        'product_quantity_cart' => $cartService->getProductQuantityCart($product_id)
                    ],
                ),
                'cart_total_price' => $cartService->getPriceCartContent($cartService->getSortedCartContent(), true)['total'],
                'product_add_button' => false,
            ]);
        }

        return back();
    }

    /**
     * {POST} Descreased product in the cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     * @param int                 $product_id
     */
    public function decreaseProduct(Request $request, CartServiceContract $cartService, int $product_id = 0)
    {
        if (
            empty($product_id)
            && !empty($request->get('product_id'))
        ) {
            $product_id = $request->get('product_id');
            $is_modal = $request->get('is_modal') ?? false;
        }

        try {
            $cartService->decreaseProductInCart($product_id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if ($request->ajax()) {
            $product_quantity_cart = $cartService->getProductQuantityCart($product_id);

            $result_data = [];
            if ($product_quantity_cart > 0) {
                if (!empty($is_modal) && $is_modal === 'true') {
                    $view_regulator_blade = 'shop.components.products.modals.button_product_cart_regulator_big';
                } else {
                    $view_regulator_blade = 'shop.components.products.button_product_cart_regulator_medium';
                }

                $result_data['product_quantity_regulator'] = Blade::render(
                    $view_regulator_blade,
                    [
                        'product_id' => $product_id,
                        'product_quantity_cart' => $product_quantity_cart
                    ],
                );
                $result_data['product_add_button'] = false;
            } else {
                if (!empty($is_modal) && $is_modal === 'true') {
                    $view_regulator_blade = 'shop.components.products.modals.button_add_product_cart_big';
                } else {
                    $view_regulator_blade = 'shop.components.products.button_add_product_cart_medium';
                }

                $result_data['product_quantity_regulator'] = Blade::render($view_regulator_blade, ['product_id' => $product_id]);
                $result_data['product_add_button'] = true;
            }

            $result_data['cart_total_price'] = $cartService->getPriceCartContent($cartService->getSortedCartContent(), true)['total'];

            return response()->json($result_data);
        }

        return back();
    }

    /**
     * {POST} Removed product from the cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     * @param int                 $product_id
     */
    public function removeProduct(Request $request, CartServiceContract $cartService, int $product_id = 0)
    {
        if (
            empty($product_id)
            && !empty($request->get('product_id'))
        ) {
            $product_id = $request->get('product_id');
            $is_modal = $request->get('is_modal') ?? false;
        }

        try {
            $cartService->removeProductFromCart($product_id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if ($request->ajax()) {
            $result_data = [];
            if (!empty($is_modal) && $is_modal === 'true') {
                $view_regulator_blade = 'shop.components.products.modals.button_add_product_cart_big';
            } else {
                $view_regulator_blade = 'shop.components.products.button_add_product_cart_medium';
            }

            $result_data['product_quantity_regulator'] = Blade::render($view_regulator_blade, ['product_id' => $product_id]);
            $result_data['cart_total_price'] = $cartService->getPriceCartContent($cartService->getSortedCartContent(), true)['total'];
            $result_data['product_add_button'] = true;

            return response()->json($result_data);
        }

        return back();
    }

    /**
     * {POST} Clear the cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     */
    public function clearCart(Request $request, CartServiceContract $cartService)
    {
        if (!isset($_COOKIE['cart_id'])) {
            return back();
        }

        try {
            $cartService->clearCart();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return back();
    }

    /**
     * {POST} {Ajax} Apply promotion on cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     */
    public function applyPromotion(Request $request, CartServiceContract $cartService)
    {
        if (empty($request['promotion_code'])) {
            return back();
        }

        try {
            $cartService->applyPromotion($request['promotion_code']);

            return response()->json(['applyed_promotion_code' => $request['promotion_code']]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response();
    }

    /**
     * {POST} {Ajax} Remove promotion from cart.
     *
     * @param Request             $request
     * @param CartServiceContract $cartService
     */
    public function removePromotion(Request $request, CartServiceContract $cartService)
    {
        if (empty($request['promotion_name'])) {
            return back();
        }

        try {
            $cartService->removePromotion($request['promotion_name']);

            return response()->json(['removed_promotion_name' => $request['promotion_name']]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return response();
    }
}
