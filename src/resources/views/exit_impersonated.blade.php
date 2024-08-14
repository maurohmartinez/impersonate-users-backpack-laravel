@if(Session::has(config('impersonate_user.session_key')) && Session::has(config('impersonate_user.session_key') . '_exit_route'))
    <form method="post" action="{{ url(Session::get(config('impersonate_user.session_key') . '_exit_route')) }}">
        @csrf
        <button type="submit" class="{{ $class ?? 'btn btn-warning me-2' }}">
            <i class="la la-arrow-left"></i> {{ $label ?? 'Back to my user' }}
        </button>
    </form>
@endif
