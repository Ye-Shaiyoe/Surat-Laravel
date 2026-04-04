<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

<<<<<<< HEAD

=======
>>>>>>> ae1b02b (Add full Laravel project fresh)
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
<<<<<<< HEAD
    
        public function store(Request $request): RedirectResponse {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'admin_code'    => ['nullable', 'string'], // tambah field secret code
        ]);

        // Cek secret code
        $role = 'user';
        if ($request->filled('admin_code')) {
            if ($request->admin_code !== config('app.admin_secret_code')) {
                return back()->withErrors(['admin_code' => 'Kode admin tidak valid.']);
            }
            $role = 'admin';
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
=======
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
>>>>>>> ae1b02b (Add full Laravel project fresh)
        ]);

        event(new Registered($user));

        Auth::login($user);

<<<<<<< HEAD
        // Redirect berdasarkan role
        return $user->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('dashboard'));
=======
        return redirect(route('dashboard', absolute: false));
>>>>>>> ae1b02b (Add full Laravel project fresh)
    }
}
