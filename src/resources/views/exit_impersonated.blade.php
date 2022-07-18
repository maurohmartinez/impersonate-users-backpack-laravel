@if(Session::has(config('impersonate_user.session_key')) && Session::has(config('impersonate_user.session_key') . '_exit_route'))
    <form method="post" action="{{ Session::get(config('impersonate_user.session_key') . '_exit_route') }}">
        @csrf
        <button type="submit" class="btn btn-warning">
            <i class="la la-arrow-left"></i> {{ __('impersonate_user::messages.btn_exit_impersonated') }}
        </button>
    </form>
@endif