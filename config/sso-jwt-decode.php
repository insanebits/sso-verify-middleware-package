<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public Key
    |--------------------------------------------------------------------------
    |
    | A path or resource of your public key.
    |
    | E.g. -----BEGIN PUBLIC KEY-----
    |      MIICIjANBgkqhkiG9w0BAQEFAA
    |      vD9pREVDPwttv2oUb8zBIpOEuf
    |      QHoaESWuP+Q918hDyALVAoRgnG
    |      -----END PUBLIC KEY-------
    |
    | E.g. 'file://path/to/public/key'
    |
    */
    'public_key' => env('JWT_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | Required JWT claims in order to has necessary data about user.
    |
    */
    'required_claims' => env('JWT_REQUIRED_CLAIMS', ['exp', 'sso_user_id', 'sso_user_customer_id']),

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Algorithm
    |--------------------------------------------------------------------------
    |
    | The algorithm you are using to generate JWT
    |
    | Asymmetric Algorithms:
    | RS256, RS384 & RS512 / ES256, ES384 & ES512 will use the keys below.
    |
    */
    'algorithm' => env('JWT_ALGORITHM', \Lcobucci\JWT\Signer\Rsa\Sha256::class)
];