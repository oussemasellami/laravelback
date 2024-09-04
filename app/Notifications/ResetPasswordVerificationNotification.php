<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Otp;
use Illuminate\Support\Facades\Cache;
class ResetPasswordVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $otp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message='Use the below code for reserting your password'; 
        $this->subject='Password Reseting';
        $this->fromEmail = 'leer.app.tn@gmail.com';
        $this->mailer='smtp';
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $otp = $this->otp->generate($notifiable->email,6,60);  
         // Store the OTP token in the cache
         Cache::put('otp:' . $notifiable->id, $otp->token, 60);
        // echo($otp->token);
        return (new MailMessage)
        ->from('leer.app.tn@gmail.com', 'Leer')
        ->mailer('smtp')
        ->subject($this->subject)
        ->greeting('Hello '. $notifiable->username)
        ->line($this->message)
        ->line('code: '.$otp->token)
        ->salutation('Best regards, Leer');
        
        
        
    }
     

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
