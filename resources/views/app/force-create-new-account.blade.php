<p>You're redirected to this page for the reason that, your account's last reservation maybe a year ago or older.</p>
<p>Please create a new account.</p>

<form method="POST" action="{{ route('logout.and.register') }}">
    @csrf

    <x-dropdown-link :href="route('logout.and.register')" onclick="event.preventDefault();
                            this.closest('form').submit();">
        {{ __('Create New Account') }}
    </x-dropdown-link>
</form>