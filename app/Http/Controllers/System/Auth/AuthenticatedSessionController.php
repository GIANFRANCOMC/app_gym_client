<?php

namespace App\Http\Controllers\System\Auth;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\System\{Company};

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View {

        $data = Utilities::getDefaultViewData();

        $data->company = Company::with(["socialsMedia"])
                                ->first();

        return view("System/auth/login", compact("data"));

    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
