@component('mail::message')
# Introduction

The body of your message.

<p> hello {{$user->name }} !</p>

@component('mail::button', ['url' => 'http://localhost:8000/en/dashboard','color'=>'success'])
Button Text
@endcomponent

<p> Your reset code is : {{$user->code}} </p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
