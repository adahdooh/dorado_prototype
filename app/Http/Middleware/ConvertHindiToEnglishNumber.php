<?php

namespace App\Http\Middleware;

use Closure;

class ConvertHindiToEnglishNumber
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
        if($request->isMethod('post')){
            foreach ($request->all() as $key => $item  ) {
                $request->merge([$key=>$this->convertHindiToEnglishNumber($item)]);
            }
        }
        return $next($request);
    }

    function convertHindiToEnglishNumber($string) {
        return strtr($string, array('۰'=>'0', '۱'=>'1', '۲'=>'2', '۳'=>'3', '۴'=>'4', '۵'=>'5', '۶'=>'6', '۷'=>'7', '۸'=>'8', '۹'=>'9', '٠'=>'0', '١'=>'1', '٢'=>'2', '٣'=>'3', '٤'=>'4', '٥'=>'5', '٦'=>'6', '٧'=>'7', '٨'=>'8', '٩'=>'9'));
    }
}
