<?php

namespace App\Listeners;

use App\Events\MessageGenerated;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Support\DTO\MailAddress;
use Support\Mails\EmailService;

class SendMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageGenerated $event): void
    {
        $messageModel = $event->message;

        if(!$messageModel->project){
            return;
        }
        $emailService = Message::EmailServiceFactory($messageModel);

        foreach (Message::EmailServiceReceiver($messageModel) as $bcc) {
            
            $emailService->to($bcc)->send($messageModel->getTemplatePath(), [
                'model' => $messageModel,
                'project' => $messageModel->project
            ]);
        }
    }
}
