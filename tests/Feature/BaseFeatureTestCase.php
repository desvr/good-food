<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

abstract class BaseFeatureTestCase extends TestCase
{
    /**
     * Method for create and login administrator for "Admin" guard
     */
    protected function createAndLoginAdministrator(
        string $name = 'Admin',
        string $email = 'admin@example.com',
        string $password = 'password'
    ): Administrator
    {
        $admin = new Administrator;
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = $password;
        $admin->save();

        Auth::guard('admin')->login($admin);

        return $admin;
    }

    /**
     * Method for create and login user for "Web" guard
     */
    protected function createAndLoginUser(
        string $name = 'User',
        string $birthday = '1990-01-01',
        int $phone = 71111111111,
        bool $is_phone_verified = true,
    ): User
    {
        $user = new User;
        $user->name = $name;
        $user->birthday = $birthday;
        $user->phone = $phone;
        if ($is_phone_verified) $user->phone_verified_at = now();
        $user->save();

        Auth::guard('web')->login($user);

        return $user;
    }

    /**
     * Method for create and login user for "Web" guard
     */
    protected function addPayments(): void
    {
        $payments_list = config('payments');

        foreach ($payments_list as $settings) {
            $payment = new Payment;
            $payment->name = $settings['db']['name'];
            $payment->method = $settings['db']['method'];
            $payment->processor = $settings['db']['processor'];
            $payment->save();
        }
    }
}
