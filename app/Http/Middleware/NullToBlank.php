<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;

class NullToBlank
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
        $input = $request->input();

        array_walk_recursive($input, function(&$value) {

            if (is_string($value)) {
                $value = StringHelper::trimNull($value);
            }
        });

        $request->replace($input);

        return $next($request);
    }
}
