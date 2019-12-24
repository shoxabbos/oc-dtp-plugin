<?php namespace Itmaker\DtpApp\Classes;

use RainLab\Translate\Classes\Translator;
use Closure;
use Config;
use Request;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $translator = Translator::instance();
        $translator->isConfigured();

        if (!$translator->loadLocaleFromRequest()) {
            if (Config::get('rainlab.translate::prefixDefaultLocale')) {
                $translator->setLocale(Request::header('Active-Locale'));
            } else {
                $translator->setLocale(Request::header('Active-Locale'));
            }
        }

        return $next($request);
    }
}
