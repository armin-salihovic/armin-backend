<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResolvePublicLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('locale', config('translatable.fallback_locale', 'en'));
        $supportedLocales = config('translatable.locales', ['en']);

        if (! is_string($locale) || ! in_array($locale, $supportedLocales, true)) {
            return response()->json([
                'message' => 'Unsupported locale.',
                'errors' => [
                    'locale' => ['The selected locale is invalid.'],
                ],
            ], 400);
        }

        $previousLocale = app()->getLocale();
        app()->setLocale($locale);
        $request->attributes->set('locale', $locale);

        try {
            return $next($request);
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
