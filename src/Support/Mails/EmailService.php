<?php

namespace Support\Mails;

use Support\DTO\MailAddress;
use Illuminate\Support\Facades\Mail;
use Support\Contracts\MailService;
use Support\DTO\MailAttachment;

class EmailService implements MailService
{
    private $attachments = [];
    private $inlineAttachments = [];
    private $to;
    private $from = null;
    private $cc = [];
    private $bcc = [];
    private $subject;
    private $data = [];
    private $view;

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param mixed $attachments
     */
    public function addAttachment(MailAttachment $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    public function addInlineAttachment(MailAttachment $attachment)
    {
        $this->inlineAttachments[] = $attachment;
        return $this;
    }

    public function getInlineAttachments()
    {
        return $this->inlineAttachments;
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }


    public function addBcc($bcc)
    {
        $this->bcc[] = $bcc;
        return $this;
    }
    /**
     * @param mixed $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;
    }


    public function addCc($cc)
    {
        $this->cc[] = $cc;
        return $this;
    }
    /**
     * @param mixed $cc
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param MailAddress $from
     */
    public function from(MailAddress $sender)
    {
        $this->from = $sender;
        return $this;
    }


    /**
     * @return MailAddress
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function to(MailAddress $to)
    {
        $this->to = $to;
        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getData()
    {
        return $this->data;
    }

    public function send($view, $data = [])
    {
        $this->data = $data;
        $this->view = $view;
        $mail = Mail::to($this->getTo()->email, $this->getTo()->name);
        $bcc = $this->getBcc();
        if (!empty($bcc)) {
            foreach ($bcc as $b) {
                if ($b instanceof MailAddress) {
                    $mail->bcc($b->email, $b->name);
                } else {
                    $mail->bcc($b);
                }
            }
        }

        $cc = $this->getCc();
        if (!empty($cc)) {
            $mail->cc($cc);
        }
        return $mail->send(new GlobalMail($this));
    }
}
