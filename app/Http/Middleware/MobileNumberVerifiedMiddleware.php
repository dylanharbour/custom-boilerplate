<?php

namespace App\Http\Middleware;

use Closure;

class MobileNumberVerifiedMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            ! access()->user()
            || ! access()->user()->isMobileNumberVerified()
        ) {
            return redirect()
                ->route('frontend.confirm.mobile.show')
                ->withFlashWarning(trans('auth.mobile_number_not_verified_error'));
        }

        return $next($request);
    }
}
