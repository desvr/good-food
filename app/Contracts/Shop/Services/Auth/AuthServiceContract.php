<?php

namespace App\Contracts\Shop\Services\Auth;

interface AuthServiceContract
{
    /**
     * Send secret auth code
     *
     * @param string $phone_number Phone number
     *
     * @return bool
     */
    public function sendCode(string $phone_number): bool;

    /**
     * Send secret auth code
     *
     * @param string $input_code Input code
     *
     * @return bool
     */
    public function verifyCode(string $input_code): bool;

    /**
     * Login user
     *
     * @param array $data Data
     *
     * @return void
     */
    public function login(array $data): void;

    /**
     * Register user
     *
     * @param array $data Data
     *
     * @return void
     */
    public function register(array $data): void;

    /**
     * Logout current user
     *
     * @return void
     */
    public function logout(): void;

    /**
     * Prepare input phone number fot requested format
     *
     * @param string $phone_number Phone number
     *
     * @return string Verified phone number
     */
    public static function preparePhoneNumber(string $phone_number): string;

    /**
     * Convert code from an array to a string
     *
     * @param array|string $code Secure code
     *
     * @return string
     */
    public function convertCodeFromArray(array|string $code): string;
}
