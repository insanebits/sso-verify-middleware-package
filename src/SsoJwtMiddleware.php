<?php

namespace Hostinger\SsoJwtDecode;

use Closure;

/**
 * Class SsoJwtMiddleware
 * @package Hostinger\SsoJwtDecode
 */
class SsoJwtMiddleware
{
    /**
     * @var SsoJwtDecode $ssoJwtDecode
     */
    private $ssoJwtDecode;

    /**
     * @param SsoJwtDecode $ssoJwtDecode
     *
     * @return void
     */
    public function __construct(SsoJwtDecode $ssoJwtDecode)
    {
        $this->ssoJwtDecode = $ssoJwtDecode;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->ssoJwtDecode->validate();

        return $next($request);
    }
}