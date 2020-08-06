# Very short description of the package

A Package to verify and handle SSO JWT

## Installation

You can install the package via composer:

```bash
"require": {
    ...
    "hostinger/sso-jwt-handler": "dev-master"
},
"repositories":[
    {
        "type": "vcs",
        "url": "https://github.com/hostinger/sso-jwt-handler.git"
    }
],
```

Next, you must install the service provider:

```bash
// config/app.php
'providers' => [
    ...
    \Hostinger\SsoJwtDecode\SsoJwtDecodeServiceProvider::class,
];
```

Next, the \Spatie\Authorize\Middleware\Authorize::class-middleware must be registered in the kernel:
```bash
//app/Http/Kernel.php

protected $routeMiddleware = [
  ...
  'sso-verify-jwt' => \Hostinger\SsoJwtDecode\SsoJwtMiddleware::class,
];
```

Next, You can publish the config-file with:
      
```php 
artisan vendor:publish --provider="\Hostinger\SsoJwtDecode\SsoJwtDecodeServiceProvider::class"
```


## Usage

``` php
Route::group(['namespace' => 'Unversioned', 'middleware' => ['sso-verify-jwt']], static function () {
     Route::get('ping', 'PingController@index');
});
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


### Security

If you discover any security related issues, please email rytis@hostinger.com instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.