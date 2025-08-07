<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HandleLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SendResetLinkRequest;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminSendResetLinkMail;


class AdminAuthenticationController extends Controller
{
    //
     public function login()
    {
        return view('admin.auth.login');
    }

   public function handleLogin(HandleLoginRequest $request)
{
    $request->authenticate(); // no parameter needed

    return redirect()->route('admin.dashboard');
}

public function logout(Request $request): RedirectResponse
    {

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
    public function forgotPassword()
    {
        return view('admin.auth.forgot-password');
    }
     public function sendResetLink(SendResetLinkRequest $request)
    {
        $token = Str::random(64);

        $admin = Admin::where('email', $request->input('email'))->first();
        $admin->remember_token = $token;
        $admin->save();

        Mail::to($request->input('email'))->send(new AdminSendResetLinkMail($token, $request->input('email')));


        return redirect()->back()->with('success', __('admin.A mail has been sent to your email address please check!'));

    }
     public function resetPassword($token)
    {
        return view('admin.auth.reset-password', compact('token'));
    }
}
