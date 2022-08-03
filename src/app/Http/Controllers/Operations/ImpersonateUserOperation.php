<?php

namespace MHMartinez\ImpersonateUser\app\Http\Controllers\Operations;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use MHMartinez\ImpersonateUser\app\Interfaces\ImpersonateInterface;
use Prologue\Alerts\Facades\Alert;

trait ImpersonateUserOperation
{
    protected function setupImpersonateUserRoutes(string $segment, string $routeName, string $controller)
    {
        Route::post($segment.'/impersonate-user/{id}', [
            'as'        => $routeName.'.impersonateUser',
            'uses'      => $controller.'@impersonateUser',
            'operation' => 'impersonateUser',
        ]);

        Route::post($segment.'/exit-impersonated-user', [
            'as'        => $routeName.'.exitImpersonatedUser',
            'uses'      => $controller.'@exitImpersonatedUser',
            'operation' => 'exitImpersonatedUser',
        ])
            ->withoutMiddleware(config('impersonate_user.admin_middleware'));
    }

    protected function setupImpersonateUserDefaults()
    {
        $this->crud->operation(['impersonateUser', 'exitImpersonatedUser'], function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
            $this->crud->setupDefaultSaveActions();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'impersonateUser', 'view', 'impersonate_user::impersonate', 'end');
        });

        $this->crud->operation(['show', 'edit'], function () {
            $this->crud->addButton('top', 'impersonateUser', 'view', 'impersonate_user::impersonate', 'end');
        });
    }

    public function impersonateUser(int $id): RedirectResponse
    {
        $request = $this->crud->validateRequest();

        // Is already impersonating?
        if (Session::has(config('impersonate_user.session_key'))) {
            Alert::add('error', __('impersonate_user::messages.already_impersonating'))->flash();

            return Redirect::back();
        }

        // Validate impersonator
        if (!$this->canAdminImpersonateOthers()) {
            Alert::add('error', __('impersonate_user::messages.cannot_impersonate'))->flash();

            return Redirect::back();
        }

        // Is trying to impersonate himself?
        $impersontatorId = backpack_user()->id;
        if ($id === $impersontatorId) {
            Alert::add('error', __('impersonate_user::messages.impersonating_yourself', ['username' => backpack_user()->name]))->flash();

            return Redirect::back();
        }

        // Validate impersonable
        if (!$this->canUserBeImpersonated($id)) {
            Alert::add('error', __('impersonate_user::messages.user_not_impersonable'))->flash();

            return Redirect::back();
        }

        // Login
        Auth::guard(config('impersonate_user.base_guard'))->loginUsingId($id, false);

        // Remember impersonator session
        Session::put(config('impersonate_user.session_key'), $impersontatorId);
        Session::put(config('impersonate_user.session_key') . '_last_url', $request->headers->get('referer'));
        Session::put(config('impersonate_user.session_key') . '_exit_route', $this->crud->route . '/exit-impersonated-user');

        // Feedback
        Alert::add('success', __('impersonate_user::messages.impersonating_as', ['username' => backpack_user()->name]))->flash();

        // Redirect
        return Redirect::to(backpack_url());
    }

    public function exitImpersonatedUser(): RedirectResponse
    {
        // Not impersonating?
        if (!Session::has(config('impersonate_user.session_key'))) {
            Alert::add('error', __('impersonate_user::messages.not_impersonating'))->flash();

            return Redirect::back();
        }

        // Login impersonator back
        Auth::guard(config('impersonate_user.base_guard'))->loginUsingId(intval(Session::get(config('impersonate_user.session_key'))));

        // Remove impersonator session
        Session::forget(config('impersonate_user.session_key'));
        $redirect = Session::get(config('impersonate_user.session_key') . '_last_url');
        Session::forget(config('impersonate_user.session_key') . '_last_url');
        Session::forget(config('impersonate_user.session_key') . '_exit_route');

        // Feedback
        Alert::add('success', __('impersonate_user::messages.back_after_impersonating', ['username' => backpack_user()->name]))->flash();

        // Redirect
        return Redirect::to($redirect);
    }

    protected function canAdminImpersonateOthers(): bool
    {
        return !backpack_user() instanceof ImpersonateInterface || backpack_user()->canImpersonateOthers();
    }

    protected function canUserBeImpersonated(int $id): bool
    {
        $user = config('backpack.base.user_model_fqn')::find($id);

        return $user instanceof ImpersonateInterface ? $user->canBeImpersonated() : true;
    }
}
