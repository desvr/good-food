<?php

namespace Tests\Unit\Services\Shop\Auth;

use App\Contracts\Shop\Senders\SenderContract;
use App\Contracts\Shop\Services\Auth\AuthServiceContract;
use App\Contracts\Shop\Services\Auth\SecureCodeServiceContract;
use App\Services\Shop\Auth\AuthService;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthServiceContract $authService;
    private SecureCodeServiceContract $secureCodeService;
    private SenderContract $sender;

    public function setUp(): void
    {
        $this->secureCodeService = $this->createMock(SecureCodeServiceContract::class);
        $this->sender = $this->createMock(SenderContract::class);

        $this->authService = new AuthService($this->secureCodeService, $this->sender);
    }

    /**
     * @covers AuthService::sendCode
     *
     * @return void
     */
    public function testSendCode()
    {
//        $this->secureCodeService->expects($this->once())
//            ->method('generateCode');

//        $this->secureCodeService->expects($this->once())
//            ->method('saveCode');

//        $this->sender->expects($this->once())
//            ->method('send');

        RateLimiter::shouldReceive('attempt')
            ->once()
            ->andReturn(true);

        $result = $this->authService->sendCode('8 (900) 900-90-90');

        $this->assertTrue($result);
    }

    public function providerVerifyCode(): array
    {
        return [
            [
                '123456',
                '123456',
                true,
            ],
        ];
    }

    /**
     * @covers AuthService::verifyCode
     *
     * @dataProvider providerVerifyCode
     *
     * @return void
     */
    public function testVerifyCode($input_code, $auth_code, $result)
    {
        $this->secureCodeService->expects($this->once())
            ->method('getCode')
            ->willReturn($auth_code);

        $this->secureCodeService->expects($this->once())
            ->method('deleteCode');

        $verify = $this->authService->verifyCode($input_code);

        $this->assertSame($result, $verify);
    }

    public function providerPreparePhoneNumber(): array
    {
        return [
            [
                '8 (900) 900-90-90',
                '79009009090',
            ],
            [
                '89009009090',
                '79009009090',
            ],
            [
                '7(900)900-9090',
                '79009009090',
            ],
        ];
    }

    /**
     * @covers AuthService::preparePhoneNumber
     *
     * @dataProvider providerPreparePhoneNumber
     *
     * @return void
     */
    public function testPreparePhoneNumber($input_phone, $result)
    {
        $prepared_phone = $this->authService::preparePhoneNumber($input_phone);

        $this->assertSame($result, $prepared_phone);
    }
}
