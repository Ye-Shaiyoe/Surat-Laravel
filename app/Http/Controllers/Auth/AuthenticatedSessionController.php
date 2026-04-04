<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
<<<<<<< HEAD
use App\Models\User;
=======

>>>>>>> ae1b02b (Add full Laravel project fresh)
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
<<<<<<< HEAD
{
    $request->authenticate();
    $request->session()->regenerate();

    /** @var User $user */
    $user = Auth::user();

    return $user->isAdmin()
        ? redirect()->intended(route('admin.dashboard'))
        : redirect()->intended(route('dashboard'));
}
        
=======
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
>>>>>>> ae1b02b (Add full Laravel project fresh)

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
