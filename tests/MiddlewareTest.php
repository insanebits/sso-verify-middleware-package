<?php

namespace Hostinger\SsoJwtDecode\Tests;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class MiddlewareTest
 * @package Hostinger\SsoJwtDecode\Tests
 */
class MiddlewareTest extends BaseTest
{
    /**
     * @test
     *
     * @return void
     */
    public function it_throws_unauthorized_exception_if_no_bearer_token_provided()
    {
        $this->withoutExceptionHandling();

        $this->expectException(UnauthorizedHttpException::class);

        $this->call('GET', '/test');
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_throws_unauthorized_exception_with_missing_claims()
    {
        $this->withoutExceptionHandling();

        $this->expectException(UnauthorizedHttpException::class);

        $this->expectExceptionMessage('JWT missing required claims');

        $this->json('GET', '/test', [], ['Authorization' => 'Bearer ' . $this->buildJWT()]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_throws_unauthorized_exception_expired_jwt()
    {
        $this->withoutExceptionHandling();

        $this->overrideRequiredClaims($this->app, []);

        $this->expectException(UnauthorizedHttpException::class);

        $this->expectExceptionMessage('JWT token is expired');

        $this->json('GET', '/test', [], ['Authorization' => 'Bearer ' . $this->buildJWT([], 1)]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_throws_exception_on_invalid_public_key()
    {
        $this->withoutExceptionHandling();

        $this->overridePublicKey($this->app, 'invalid');

        $this->expectException(InvalidArgumentException::class);

        $this->json('GET', '/test', [], ['Authorization' => 'Bearer ' . $this->buildJWT([])]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_throws_exception_on_invalid_jwt()
    {
        $this->withoutExceptionHandling();

        $this->overrideRequiredClaims($this->app, []);

        $this->expectException(UnauthorizedHttpException::class);

        $this->expectExceptionMessage('Bearer token is invalid');

        $this->json('GET', '/test', [], ['Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.
        eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.
        SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c']);
    }
}
