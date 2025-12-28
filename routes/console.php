<?php
# app/routes/console.php
# Cara penggunaan baru: php artisan send-mail {email}

use Illuminate\Support\Facades\Artisan;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('send-mail {email}', function (string $email) {
    
    $emailMessage = (new MailtrapEmail())
        ->from(new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))) 
        ->to(new Address($email)) 
        ->subject('Permintaan Reset Password')
        ->category('Reset Password Test')
        ->text('Halo! Gunakan email ini untuk mencoba fitur reset password pada aplikasi Eventify.')
    ;

    // Menggunakan API TOKEN dari .env
    $response = MailtrapClient::initSendingEmails(
        apiKey: env('MAILTRAP_API_TOKEN')
    )->send($emailMessage);

    $this->info("Email sedang dikirim ke: " . $email);
    var_dump(ResponseHelper::toArray($response));

})->purpose('Mengirim email percobaan ke alamat tertentu');