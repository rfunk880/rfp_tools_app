<?php

namespace App\Models;

use Exception;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Support\DTO\MailAddress;
use Support\DTO\MailAttachment;
use Support\Mails\EmailService;
use Support\Traits\CreaterUpdaterTrait;
use Support\Traits\HasJsonFields;

class Message extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use CreaterUpdaterTrait;
    use HasJsonFields;

    const TYPE_RFP = 0;
    const TYPE_ADDENDUM = 1;
    const TYPE_NOTICE = 2;

    protected $table = 'messages';

    protected $fillable = ['project_id', 'subject', 'content', 'type'];

    public static $typeLabel = [
        self::TYPE_RFP => '<span class="badge badge-pill bg-primary">RFP</span>',
        self::TYPE_ADDENDUM => '<span class="badge badge-pill bg-warning">ADDENDUM</span>',
        self::TYPE_NOTICE => '<span class="badge badge-pill bg-danger">NOTICE</span>',
    ];

    protected $casts = [
        'metadata' => 'json'
    ];


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('attachments');
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function recepients()
    {
        return $this->belongsToMany(Contact::class, 'message_recepients', 'message_id', 'contact_id');
    }

    public function ccUsers()
    {
        return $this->belongsToMany(User::class, 'message_cc', 'message_id', 'user_id');
    }


    public function scopeFilter($q, $params = [])
    {
        return $q
        ->leftJoin('message_recepients', 'messages.id', '=', 'message_recepients.message_id')
    ->leftJoin('contacts', 'message_recepients.contact_id', '=', 'contacts.id')
    ->select('messages.*')
    ->whereNotNull('contacts.id')
    ->groupBy('messages.id')
        ->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                
                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('subject', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('contacts.email', 'LIKE',  '%'.$keyword.'%');
                    
                });
            }

            if (@$params['project_id'] != '') {
                $q->where('project_id', $params['project_id']);
            }
            if (@$params['type'] != '' && @$params['type'] != 'All') {
                $q->where('type', $params['type']);
            }

            if (@$params['date_from'] != '') {
                $q->whereDate('date_from', '>=', $params['date_from']);
            }

            if (@$params['date_to'] != '') {
                $q->whereDate('date_to', '<=', $params['date_to']);
            }
            if (@$params['sender_id'] != '') {
                $q->whereHas('ccUsers', function ($q) use ($params) {
                    return $q->where('user_id', $params['sender_id']);
                });
            }
            if (@$params['receiver_id'] != ''/*  || @$params['keyword'] != '' */) {
                $q->whereHas('recepients', function ($q) use ($params) {
                   /*  if (@$params['keyword'] != '') {
                        $keyword = urldecode($params['keyword']);
                        $q->where('email', 'LIKE', '%'.$keyword.'%');
                    } */
                    return $q->where('contact_id', $params['receiver_id']);
                });
            }
        });
    }


    public static function EmailServiceFactory(Message $message)
    {
        $service = new EmailService;
        $type = strip_tags(@self::$typeLabel[$message->type]);
        $service->subject($message->subject . ' SWFIC ' . $type/* .' '.@$message->proejct->name */);
        // $service->to(new MailAddress(config('mail.admin.address'), config('mail.admin.name')));
        foreach ($message->getMedia('attachments') as $attachment) {
            $service->addAttachment(new MailAttachment($attachment->getPath(), $attachment->name));
        }
        return $service;
    }

    public static function EmailServiceReceiver(Message $message)
    {
        $receivers = [];
        foreach ($message->recepients as $k => $contact) {
            $receivers[] = new MailAddress($contact->email, $contact->name);
        }
        if (@$message->metadata['extra_emails']) {
            foreach ($message->metadata['extra_emails'] as $email) {
                $receivers[] = new MailAddress($email);
            }
        }
        return $receivers;
    }

    public function getTemplatePath()
    {
        $label = strtolower(strip_tags(@self::$typeLabel[$this->type]));
        if (!$label) {
            // throw new Exception("Invalid message template");
            $label = 'notice';
        }
        return 'emails.message.template-' . $label;
        // return view(, $data);
    }


    public function selfDestruct()
    {
        return $this->delete();
    }
}
