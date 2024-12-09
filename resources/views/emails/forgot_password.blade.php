<x-mail::message>
# Introduction

Hi {{ $user->name }},

We received a request to reset your password. Click the button below to reset it:

<x-mail::button :url="$url">
    {{ $token }}
</x-mail::button>

If you did not request a password reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
