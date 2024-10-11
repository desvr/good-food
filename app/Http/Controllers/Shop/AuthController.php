<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Services\Auth\AuthServiceContract;
use App\Http\Controllers\Controller;
use Cookie;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginPage()
    {
        $app_logo = config('app.logo');

        return view('shop.pages.auth.login', compact('app_logo'));
    }

    public function registerPage()
    {
        $app_logo = config('app.logo');

        return view('shop.pages.auth.register', compact('app_logo'));
    }

    /**
     * {POST} {Ajax} Send Code to phone.
     *
     * @param Request $request
     */
    public function sendCode(Request $request, AuthServiceContract $auth_service)
    {
        $phone_number = $request->has('phone') ? $request->phone : '';

        try {
            $result = $auth_service->sendCode($phone_number);
        } catch (\Exception $e) {
            return ['failed' => $e->getMessage()];
        }

        return response()->json($result);
    }

    /**
     * {GET} {AJAX} Get enter code modal.
     *
     * @param Request $request
     */
    public function getEnterCodeModal(Request $request)
    {
        return view('shop.components.auth.modals.enter_code');
    }

    /**
     * {POST} {AJAX} Verify Code.
     *
     * @param Request $request
     */
    public function verifyCode(Request $request, AuthServiceContract $auth_service)
    {
        if (!$request->has('code')) {
            return response()->json(['failed' => 'Введите полученный код.']);
        }
        $input_code = $request->code;

        if (
            !$request->has('purpose')
            || !$request->has('data')
        ) {
            return response()->json(['failed' => 'Недостаточно информации, попробуйте еще раз.']);
        }
        /** @var string $purpose Purpose of use verify code: `login` or `register` */
        $purpose = $request->purpose;
        $data = $request->data;

        try {
            $input_code = $auth_service->convertCodeFromArray($input_code);
            if ($auth_service->verifyCode($input_code)) {
                $auth_service->$purpose($data);
            }
        } catch (\Exception $e) {
            return ['failed' => $e->getMessage()];
        }

        return response()->json(['redirect' => route('home')]);
    }

    /**
     * {POST} {AJAX} Logout user.
     *
     * @param Request $request
     */
    public function logout(Request $request, AuthServiceContract $auth_service)
    {
        $auth_service->logout();

        return redirect()->route('home')->withCookie(Cookie::forget('cart_id'));
    }
}
