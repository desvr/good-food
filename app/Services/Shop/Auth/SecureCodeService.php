<?php

namespace App\Services\Shop\Auth;

use App\Contracts\Shop\Services\Auth\SecureCodeServiceContract;
use App\Exceptions\SecureCodeException;

class SecureCodeService implements SecureCodeServiceContract
{
    protected int $codeLength = 6;
    protected string $codeKey = 'auth_code';

    public function __construct(
        private bool $testMode = false,
    ) {}

    /**
     * Generate secret auth code
     *
     * @return string
     *
     * @throws SecureCodeException
     */
    public function generateCode(): string
    {
        try {
            $code = $this->testMode
                ? '000000'
                : (string) random_int(pow(10, $this->codeLength - 1), pow(10, $this->codeLength) - 1);
        } catch (\Exception $e){
            throw new SecureCodeException('Произошла ошибка в сервисе генерации кода.');
        }

        return $code;
    }

    /**
     * Check exists secure auth code in Session
     *
     * @return string
     */
    public function getCode(): string
    {
        return session($this->codeKey, '');
    }

    /**
     * Delete secure auth code from Session
     *
     * @return void
     */
    public function deleteCode(): void
    {
        session([$this->codeKey => '']);
    }

    /**
     * Save secure auth code in Session
     *
     * @param string $code Secure code
     *
     * @return void
     */
    public function saveCode(string $code): void
    {
        session([$this->codeKey => $code]);
    }
}
