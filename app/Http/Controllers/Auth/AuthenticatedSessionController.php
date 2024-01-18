<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $company_id   = env('COMPANY_ID', null);
        $company_info = null;
        $companies    = [];

        if(Utilities::validateVariable($company_id)) {

            $company_info = Company::where('id', $company_id)
                                   ->first();

            $companies = $company_info ? [$company_info] : [];

        }

        if(count($companies) === 0 || !Utilities::validateVariable($company_id)) {

            $companies = Company::orderBy('commercial_name')
                                ->get();

        }

        return view('auth.login', ["companies" => $companies, "company_info" => $company_info]);
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
