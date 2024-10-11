<?php

namespace Tests\Unit\Services\Shop\Auth;

use App\Services\Shop\Auth\SecureCodeService;
use Tests\TestCase;

class SecureCodeServiceTest extends TestCase
{
    private SecureCodeService $secureCodeService;

    public function setUp(): void
    {
        $this->secureCodeService = new SecureCodeService();
    }

    public function providerGenerateCode(): array
    {
        return [
            [4, 4],
            [6, 6],
        ];
    }

    /**
     * @covers SecureCodeService::generateCode
     *
     * @dataProvider providerGenerateCode
     *
     * @return void
     */
    public function testGenerateCode($codeLength, $result)
    {
        $reflection = new \ReflectionClass($this->secureCodeService);
        $propertyCodeLength = $reflection->getProperty('codeLength');
        $propertyCodeLength->setValue($this->secureCodeService, $codeLength);

        $code = $this->secureCodeService->generateCode();

        $this->assertSame($result, strlen($code));
    }

    /**
     * @covers SecureCodeService::generateCode
     *
     * @return void
     */
    public function testGenerateCodeMode()
    {
        $reflection = new \ReflectionClass($this->secureCodeService);
        $propertyTestMode = $reflection->getProperty('testMode');
        $propertyTestMode->setValue($this->secureCodeService, true);

        $code = $this->secureCodeService->generateCode();

        $this->assertSame('000000', $code);
    }
}
