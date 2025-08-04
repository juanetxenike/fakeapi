<?php

namespace App\Http\Controllers\Filament\Auth;

use Filament\Http\Controllers\Auth\LoginController as BaseLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends BaseLoginController
{
    public function store(Request $request)
    {
        Log::info('Filament Login attempt', [
            'request' => $request->all(),
            'session' => session()->all(),
            'user' => auth()->user(),
        ]);
        return parent::store($request);
    }
}
