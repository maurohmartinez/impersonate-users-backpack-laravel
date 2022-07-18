@if(Session::has(config('impersonate_user.session_key')))
    <a href="{{ route('impersonate_user.start') }}" class="{{ config('impersonate_user.btn_custom_class', 'btn btn-warning') }}">{{ __('impersonate_user.btn_impersonate') }}</a>
@endif