<?php

namespace App\Notifications;

use App\Models\Classwork;
use App\Notifications\Channels\HadaraSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class NewClassworkNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Classwork $classwork)
    {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */


    public function via(object $notifiable): array
    {
        //Channels : Email, Database, Broadcast(pusher), Vonage (sms), Slack
        $via = [
            'database',
            FcmChannel::class,
            //HadaraSmsChannel::class,
            //'mail',
            //'broadcast',
            //'vonage',
        ];
//        if ($notifiable->receive_mail_notification){
//            $via[] = 'mail';
//        }
//        if ($notifiable->receive_push_notification){
//            $via[] = 'broadcast';
//        }
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $classwork = $this->classwork;
        $content = __(':name posted a new :type : :title', [
            'name' => $classwork->user->name,
            'type' => __($classwork->type->value),
            'title' => $classwork->title,
        ]);

        return (new MailMessage)
                    ->subject(__('New :type',[
                        'type' =>$classwork->type->value,
                    ]))
                    ->greeting(__('Hi :name',[
                        'name' => $notifiable->name
                    ]))
                    ->line($content)
                    ->action(__('Go to classwork'), route('classrooms.classworks.show',[$classwork->classroom_id,$classwork->id]))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable):DatabaseMessage
    {
        return new DatabaseMessage($this->createMessage());
    }

    public function toBroadcast(object $notifiable):BroadcastMessage
    {
        return new BroadcastMessage($this->createMessage());
    }

    public function toFcm($notifiable)
    {
        $content = __(':name posted a new :type : :title', [
            'name' => $this->classwork->user->name,
            'type' => __($this->classwork->type->value),
            'title' => $this->classwork->title,
        ]);
        return FcmMessage::create()
            ->setData([
                'classwork_id' => "{$this->classwork->id}",
                'user_id' => "{$this->classwork->user_id}",
                ])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('New Classwork')
                ->setBody('Your account has been activated.')
                ->setImage('http://example.com/url-to-image-here.png'))
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')));
    }

    protected function createMessage():array
    {
        $classwork = $this->classwork;
        $content = __(':name posted a new :type : :title', [
            'name' => $classwork->user->name,
            'type' => __($classwork->type->value),
            'title' => $classwork->title,
        ]);

        return [
            'title' => __('New :type', [
                'type' => $classwork->type->value,
            ]),
            'body' => $content,
            'image' => '',
            'link' => route('classrooms.classworks.show', [$classwork->classroom->id, $classwork->id]),
            'classwork' => $classwork->id,
        ];
    }

    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content(__('New Classwork Created!'));
    }

    public function toHadara(): string
    {
        return (__('New Classwork Created!'));
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
