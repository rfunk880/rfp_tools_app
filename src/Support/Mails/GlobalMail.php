<?php

namespace Support\Mails;

use Support\DTO\MailAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Support\DTO\MailAttachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GlobalMail extends Mailable
{
    use Queueable, SerializesModels;

    private $service;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        $this->service = $emailService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!is_null($this->service->getFrom()) && $this->service->getFrom() instanceof MailAddress) {
            $this->from($this->service->getFrom()->email, $this->service->getFrom()->name);
        }

        if(!empty($this->service->getBcc())){
            $this->bcc($this->service->getBcc());
        }

        if(!empty($this->service->getCc())){
            $this->cc($this->service->getCc());
        }
        
        $attachments = $this->service->getAttachments();
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                if ($file instanceof MailAttachment) {
                    $this->attach($file->path, [
                        'as' => $file->name
                    ]);
                }
            }
        }

        

        foreach ($this->service->getInlineAttachments() as $inlineAttachment) {
            if ($inlineAttachment instanceof MailAttachment) {
                $this->attachData($inlineAttachment->contents, $inlineAttachment->name);
            }
        }

        return $this
            ->subject($this->service->getSubject())
            ->markdown($this->service->getView())->with($this->service->getData());
    }
}
