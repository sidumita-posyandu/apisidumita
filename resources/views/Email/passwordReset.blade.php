@component('mail::message')
# Hello

A request has been received to change the password for your Sidumita Account

@component('mail::button', ['url' => env('BASE_URL_LINK').'resetPassword?token='.$token.'&'.'email='.$email])
Reset Password
@endcomponent

Thanks,<br>
Sidumita
@endcomponent
