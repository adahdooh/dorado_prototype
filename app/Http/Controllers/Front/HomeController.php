<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('home');
    }
}