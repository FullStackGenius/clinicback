<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use App\Mail\ThankYouForSignup;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;

class RegisteredUserController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function getVerifyYourEmail($token = null)
    {
        $token = Crypt::decrypt($token);
        if (empty($token)) {
            return redirect(route('login'));
        }
        $user =  User::find($token);
        if (empty($user)) {
            return redirect(route('login'));
        }
        $user->email_verified_at = now();
        $user->user_status = 1;
        $user->save();
        $userData = User::find($user->id);
        $this->mailService->safeSend($userData->email,new ThankYouForSignup($userData),'getVerifyYourEmail mail');
        //Mail::to($userData->email)->send(new ThankYouForSignup($userData));
        return redirect(ProjectConstants::FRONTEND_PATH . '/sign-in?verified=1');
    }
}
