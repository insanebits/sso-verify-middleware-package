<?php

namespace Hostinger\SsoJwtDecode\Tests;

use Hostinger\SsoJwtDecode\SsoJwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Orchestra\Testbench\TestCase;

/**
 * Class BaseTest
 * @package Hostinger\SsoJwtDecode\Tests
 */
abstract class BaseTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpRoutes();
    }

    /**
     * @return void
     */
    private function setUpRoutes()
    {
        Route::any('/test', ['middleware' => SsoJwtMiddleware::class, function () {
                return 'Guarded resource';
            }
        ]);
    }

    /**
     * @param array $additionalClaims
     * @param int|null $expirationTime
     *
     * @return string
     */
    protected function buildJWT(array $additionalClaims = [], $expirationTime = null)
    {
        $privateKey = new Key($this->getPrivateJWTKey());

        $expirationTime = $expirationTime ?: time() + 3600;

        $JWTBuilder = (new Builder())
            ->issuedBy('http://example.com') // Configures the issuer (iss claim)
            ->permittedFor('http://example.org') // Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->expiresAt($expirationTime); // Configures the expiration time of the token (exp claim)

        foreach ($additionalClaims as $name => $value) {
            $JWTBuilder->withClaim($name, $value);
        }

        return (string)$JWTBuilder->getToken(new Sha256(), $privateKey);
    }

    /**
     * @return string
     */
    private function getPrivateJWTKey()
    {
        return '-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----';
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sso-jwt-decode.algorithm', \Lcobucci\JWT\Signer\Rsa\Sha256::class);
        $app['config']->set('sso-jwt-decode.public_key', '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAxtsTyTde3TiafbCVCObR
vD9pREVDPwttv2oUb8zBIpOEufgn7lJRG51MYZjSrB1BAX9XQyKjdEn/OYZy117R
QHoaESWuP+Q918hDyALVAoRgnGIDMzufUo3bEfIQXbu0gJLiInyIR3lgHRGMJ6Or
0pDbRvBtqqaTz39xjqX6XiLmXyKDbU1wmlUJTzJJbBKwrYaoT7fZVAwEStwZTGHc
/UtAJ+zcmzDKQ6n3MK5UkNxGEgf0q1siSvUuuRJAJtHM5e8Vs02LsUEEOlhUCoYx
zyRDRHZBDGGMNUfK8n/S0TwiuhUehBv2rkp6jBj+YnuwPjw8aPqe888hMIWWl94p
2Z3jgUFH5q5zsSmhNFoKdfON+TdoLAuOGU4x6wfeqyUbzIjIY1z91tQV/1CM3nXh
tQtzZ+jkCGSFx5rrWi+nos+eIjrc5bRJidoWxWJuMSeNhPoWbPZ8g9Rognn+kNuX
QoCM+mx1lD6RRVWJaasQE+JQo2JVp7TsE2HQj04knWpuP1QkwMWv/rLBWYvZPKX1
fs4Qc+WBOFsR4rQdrWrALiRM7Jf7HkUWjgqZdAij3xJ58ahtc7rKJZFPTMx73aEd
XAPnmYodyaJxNvgZ695pTCjbflzSEPhx98BgQ/MjxU7Rf/mTanBC8KTlh8qWer6o
AlcZ0H0qvpjxfRe+6B7UgmcCAwEAAQ==
-----END PUBLIC KEY-----');
        $app['config']->set('sso-jwt-decode.required_claims', ['exp', 'sso_user_id', 'sso_user_customer_id']);
    }

    /**
     * @param Application $app
     * @param array       $claims
     */
    protected function overrideRequiredClaims(Application $app, array $claims)
    {
        $app['config']->set('sso-jwt-decode.required_claims', $claims);
    }

    /**
     * @param Application $app
     * @param string      $claims
     */
    protected function overridePublicKey(Application $app, $claims)
    {
        $app['config']->set('sso-jwt-decode.public_key', $claims);
    }

    /**
     * @param Application $app
     * @param string      $claims
     */
    protected function overrideAlgorithm(Application $app, $claims)
    {
        $app['config']->set('sso-jwt-decode.algorithm', $claims);
    }
}
