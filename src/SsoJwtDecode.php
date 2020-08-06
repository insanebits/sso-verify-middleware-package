<?php

namespace Hostinger\SsoJwtDecode;

use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class SsoJwtDecode
 * @package Hostinger\SsoJwtDecode
 */
class SsoJwtDecode
{
    /** @var string EXCEPTION_CHALLENGE */
    private const EXCEPTION_CHALLENGE = 'jwt-auth';

    /**
     * @var Request $request
     */
    private $request;

    /**
     * @param Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function validate(): void
    {
        $JWT = $this->parseJWT($this->getBearerToken());

        $algorithm = $this->getJWTAlgorithm();

        if (!$JWT->verify(new $algorithm(), $this->getJWTPublicKey())) {
            throw new UnauthorizedHttpException(self::EXCEPTION_CHALLENGE, 'Bearer token is invalid');
        }

        $this->validateRequiredClaims($JWT);

        $this->validateJWTExpiration($JWT);
    }

    /**
     * @return array
     */
    public function getJWTClaims(): array
    {
        $this->validate();

        return $this->parseJWT($this->getBearerToken())->getClaims();
    }

    /**
     * @return string
     */
    private function getBearerToken(): string
    {
        $bearerToken = $this->request->bearerToken();

        if (!$bearerToken) {
            throw new UnauthorizedHttpException(self::EXCEPTION_CHALLENGE, 'Bearer token not provided');
        }

        return $bearerToken;
    }

    /**
     * @param string $jwt
     *
     * @return Token
     */
    private function parseJWT(string $jwt): Token
    {
        return (new Parser())->parse($jwt);
    }

    /**
     * @param Token $token
     *
     * @return void
     */
    private function validateRequiredClaims(Token $token): void
    {
        foreach ($this->getJWTRequiredClaims() as $claim) {
            if (!$token->hasClaim($claim)){
                throw new UnauthorizedHttpException(self::EXCEPTION_CHALLENGE, 'JWT missing required claims');
            }
        }
    }

    /**
     * @param Token $JWT
     *
     * @return  void
     */
    private function validateJWTExpiration(Token $JWT): void
    {
        if ($JWT->isExpired()) {
            throw new UnauthorizedHttpException(self::EXCEPTION_CHALLENGE, 'JWT token is expired');
        }
    }

    /**
     * @return array
     */
    private function getJWTRequiredClaims(): array
    {
        return config('sso-jwt-decode.required_claims');
    }

    /**
     * @return string
     */
    private function getJWTAlgorithm(): string
    {
        return config('sso-jwt-decode.algorithm');
    }

    /**
     * @return string
     */
    private function getJWTPublicKey(): string
    {
        return config('sso-jwt-decode.public_key');
    }
}
