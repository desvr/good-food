<?php

namespace App\Contracts\Shop\Services\Auth;

interface SecureCodeServiceContract
{
    /**
     * Generate and save secret auth code
     *
     * @return string
     */
    public function generateCode(): string;

    /**
     * Check exists secure auth code in Session
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Delete secure auth code from Session
     *
     * @return void
     */
    public function deleteCode(): void;

    /**
     * Save secure auth code in Session
     *
     * @param string $code Secure code
     *
     * @return void
     */
    public function saveCode(string $code): void;
}
