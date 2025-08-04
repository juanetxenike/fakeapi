<?php

namespace App\Filament\Auth\Actions;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomLogin extends Action
{
    public static function make(string $name = null): static
    {
        return parent::make($name)
            ->action(fn (Request $request) => static::handle($request));
    }

    public static function handle(Request $request)
    {
        Log::info('Filament Custom Login attempt', [
            'request' => $request->all(),
            'session' => session()->all(),
            'user' => Auth::user(),
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Log::info('Filament Custom Login success', [
                'user' => Auth::user(),
            ]);
            return redirect()->intended('/admin');
        }

        Log::warning('Filament Custom Login failed', [
            'credentials' => $credentials,
        ]);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
