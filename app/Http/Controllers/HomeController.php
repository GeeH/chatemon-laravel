<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class HomeController extends Controller
{
    public function __invoke(Request $request, Logger $logger)
    {
        if ($request->has('new')) {
            $this->startNewCombat($logger);
        }
        return view('tailwind');
    }
}
