@component('mail::message')
# Hello

A request has been received to change the password for your Sidumita Account

@component('mail::button', ['url' => 'http://127.0.0.1:8000/resetPassword?token='.$token.'&'.'email='.$email])
Reset Password
@endcomponent

Thanks,<br>
Sidumita
@endcomponent
