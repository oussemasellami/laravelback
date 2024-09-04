<?php

namespace App\Notifications;

// use Ichtrojan\Otp\Otp as Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Otp;
class EmailVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message='Use the below code for verification process'; 
        $this->subject='Verification Needed';
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
