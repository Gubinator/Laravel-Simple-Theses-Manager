<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        if (Session::has('locale') && array_key_exists(Session::get('locale'), Config::get('languages'))) {
            App::setLocale(Session::get('locale'));
        } else {
            // Default to 'en' if no language is set in session
            App::setLocale('en');
        }

        return $next($request);
    }
}
