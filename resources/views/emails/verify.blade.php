<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
{{ $pin }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
