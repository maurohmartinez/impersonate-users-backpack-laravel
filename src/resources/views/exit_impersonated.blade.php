@if(Session::has(config('impersonate_user.session_key')) && Session::has(config('impersonate_user.session_key') . '_exit_route'))
    <form method="post" action="{{ Session::get(config('impersonate_user.session_key') . '_exit_route') }}">
        @csrf
        <button type="submit" class="{{ $class ?? 'btn btn-warning' }}">
            <i class="la la-arrow-left"></i> {{ $label ?? 'Back to my user' }}
        </button>
    </form>
@endif