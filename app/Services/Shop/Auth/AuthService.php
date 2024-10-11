<?php

namespace App\Services\Shop\Auth;

use App\Contracts\Shop\Senders\SenderContract;
use App\Contracts\Shop\Services\Auth\AuthServiceContract;
use App\Contracts\Shop\Services\Auth\SecureCodeServiceContract;
use App\Exceptions\AuthException;
use App\Models\User;
use App\Senders\SmsAero\SmsAeroMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthService implements AuthServiceContract
{
    private string $message = 'Ваш код авторизации: [code].';

    public function __construct(
        protected SecureCodeServiceContract $secureCodeService,
        /** @var $sender SmsAeroMessage */
        protected SenderContract $sender,
    ) {}

    /**
     * Send secret auth code
     *
     * @param string $phone_number Phone number
     *
     * @return bool
     *
     * @throws AuthException
     */
    public function sendCode(string $phone_number): bool
    {
        $phone_number = static::preparePhoneNumber($phone_number);

        $sended = RateLimiter::attempt(
            'send-message:' . $phone_number,
            1,
            function() use ($phone_number) {
                $code = $this->secureCodeService->generateCode();
                $this->secureCodeService->saveCode($code);

                $message = str_replace('[code]', $code, $this->message);
                $this->sender->send(
                    [$phone_number],
                    $message
                );
            },
            300
        );

        if (!$sended) {
            $seconds = RateLimiter::availableIn('send-message:' . $phone_number);
            throw new AuthException('Превышено количество попыток. Повторите через ' . $seconds . ' секунд.');
        }

        return true;
    }

    /**
     * Verify secret auth code
     *
     * @param string $input_code Input code
     *
     * @return bool
     *
     * @throws AuthException
     */
    public function verifyCode(string $input_code): bool
    {
        $auth_code = $this->secureCodeService->getCode();
        if (empty($auth_code)) {
            throw new AuthException('Произошла ошибка, попробуйте повторно отправить код.');
        }

        $veryfied = RateLimiter::attempt(
            'verify-code:' . $auth_code,
            3,
            function() use ($input_code, $auth_code) {
                if ($input_code !== $auth_code) {
                    RateLimiter::hit('verify-code:' . $auth_code);
                    throw new AuthException('Код не совпадает.');
                }
            }
        );

        if (!$veryfied) {
            $seconds = RateLimiter::availableIn('verify-code:' . $auth_code);
            throw new AuthException('Превышено количество попыток. Повторите через ' . $seconds . ' секунд.');
        }

        $this->secureCodeService->deleteCode();

        return true;
    }

    /**
     * Login user (used for `login` purpose)
     *
     * @param array $data Data
     *
     * @return void
     *
     * @throws AuthException
     */
    public function login(array $data): void
    {
        $phone_number = static::preparePhoneNumber($data['phone']);
        $user = User::query()->where('phone', '=', $phone_number)->first();
        if (empty($user)) {
            throw new AuthException('Пользователь не найден, возможно вы не зарегистрированы.');
        }

        Auth::guard('web')->login($user);

        $user_data = $user->toArray();
        session(['user_data' => $user_data]);
    }

    /**
     * Register user (used for `register` purpose)
     *
     * @param array $data Data
     *
     * @return void
     *
     * @throws AuthException
     */
    public function register(array $data): void
    {
        $phone_number = static::preparePhoneNumber($data['phone']);
        if (User::query()->where('phone', '=', $phone_number)->first()) {
            throw new AuthException('Пользователь с таким номером телефона уже зарегистрирован, пожалуйста авторизуйтесь.');
        }

        $name = $data['name'];
        $birthday = $data['birthday'];
        $user = User::create([
            'name' => $name,
            'phone' => $phone_number,
            'birthday' => $birthday,
            'phone_verified_at' => now(),
            'remember_token' => Str::random(10),
            'telegram_token' => Str::ulid()->toBase32(),
        ]);
        if (empty($user)) {
            throw new AuthException('Не удалось зарегистрировать пользователя.');
        }

        Auth::guard('web')->login($user);

        $user_data = $user->toArray();
        session(['user_data' => $user_data]);
    }

    /**
     * Logout current user
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('web')->logout();
        session()->forget('user_data');
    }

    /**
     * Prepare input phone number fot requested format
     *
     * @param string $phone_number Phone number
     *
     * @return string Verified phone number
     *
     * @throws AuthException
     */
    public static function preparePhoneNumber(string $phone_number): string
    {
        if (empty($phone_number)) {
            throw new AuthException('Не указан номер телефона.');
        }

        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        if ($phone_number[0] !== '7') {
            $phone_number[0] = '7';
        }

        if (strlen($phone_number) !== 11) {
            throw new AuthException('Указан некорректный номер телефона.');
        }

        return $phone_number;
    }

    /**
     * Convert code from an array to a string
     *
     * @param array|string $code Secure code
     *
     * @return string
     */
    public function convertCodeFromArray(array|string $code): string
    {
        if (is_array($code)) {
            $code = implode('', $code);
        }

        return $code;
    }
}
