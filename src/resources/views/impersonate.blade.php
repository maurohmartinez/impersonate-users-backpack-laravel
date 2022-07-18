@if(!Session::has(config('impersonate_user.session_key')))
    <form class="d-inline-block" method="post" action="{{ url($crud->route . '/impersonate-user/' . $entry->getKey()) }}">
        @csrf
        <button type="submit" class="btn btn-link"><i class="la la-unlock"></i> {{ __('impersonate_user::messages.btn_impersonate') }} {{ $entry->getKey() }}</button>
    </form>
@elseif(Session::has(config('impersonate_user.session_key')) && backpack_user()->id === $entry->getKey())
    <form class="d-inline-block" method="post" action="{{ url($crud->route . '/exit-impersonated-user') }}">
        @csrf
        <button type="submit" class="{{ config('impersonate_user.btn_custom_class') . ' ' . config('impersonate_user.btn_exit_custom_class') }}"><i class="la la-lock {{ config('impersonate_user.btn_exit_custom_class') }}"></i> {{ __('impersonate_user::messages.btn_exit_impersonated') }}</button>
    </form>
@endif