<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        '/telegram',
        'telegram',
    ];
};