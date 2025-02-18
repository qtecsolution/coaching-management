<x-mail::message>
Hi <strong>{{ $user->name }}</strong>,
You are receiving this email because we received a password reset request for your account. If you did not request, no further action is required. If you did request a password reset, you may reset your password using the following link:

<x-mail::button :url="$url">
    Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
