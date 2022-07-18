<?php

namespace MHMartinez\ImpersonateUser\app\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Prologue\Alerts\Facades\Alert;

class ImpersonateUser extends Controller
{
    public function impersonateUser(Request $request): RedirectResponse
    {
        if (! backpack_user()->can(config('impersonate_user.permission_key'))) {
            Alert::add('error', __('impersonate_user::cannot_impersonate'))->flash();

            return Redirect::back();
        }

        if (Session::has(config('impersonate_user.session_key'))) {
            Alert::add('error', __('impersonate_user::already_impersonating'))->flash();

            return Redirect::back();
        }

        $originalUserId = backpack_user()->id;
        if (intval($request->input('id')) === $originalUserId) {
            Alert::add('error', __('impersonate_user::impersonating_yourself', ['username' => backpack_user()->name]))->flash();

            return Redirect::back();
        }

        // remember
        Session::put(config('impersonate_user.session_key'), $originalUserId);
        Session::put(config('impersonate_user.session_key') . '_last_url', $request->headers->get('referer'));

        // login
        Auth::guard(config('impersonate_user.base_guard'))->loginUsingId(intval($request->input('id')));

        // feedback
        Alert::add('success', __('impersonate_user::impersonating_as', ['username' => backpack_user()->name]))->flash();

        // redirect
        return Redirect::route(config('impersonate_user.redirect_after_initiation'));
    }

    public function stopImpersonateUser(): RedirectResponse
    {
        // Login
        Auth::guard(config('impersonate_user.base_guard'))->loginUsingId(intval(Session::get(config('impersonate_user.session_key'))));

        // Remember original session
        Session::forget(config('impersonate_user.session_key'));
        $redirect = Session::get(config('impersonate_user.session_key') . '_last_url');
        Session::forget(config('impersonate_user.session_key') . '_last_url');

        // Feedback
        Alert::add('success', __('impersonate_user::back_after_impersonating', ['username' => backpack_user()->name]))->flash();

        // redirect
        return Redirect::to($redirect);
    }
}