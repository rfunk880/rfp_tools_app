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

class Calllog extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use CreaterUpdaterTrait;

    const TYPE_RFP = 0;
    const TYPE_ADDENDUM = 1;
    const TYPE_NOTICE = 2;

    protected $table = 'calllogs';

    protected $fillable = ['project_id', 'subject', 'content', 'type'];

    public static $typeLabel = [
        self::TYPE_RFP => '<span class="badge badge-pill bg-primary">RFP</span>',
        self::TYPE_ADDENDUM => '<span class="badge badge-pill bg-warning">ADDENDUM</span>',
        self::TYPE_NOTICE => '<span class="badge badge-pill bg-danger">NOTICE</span>',
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
        return $this->belongsToMany(Contact::class, 'calllog_recepients', 'calllog_id', 'contact_id');
    }

    // public function ccUsers()
    // {
    //     return $this->belongsToMany(User::class, 'calllog_cc', 'calllog_id', 'user_id');
    // }


    public function scopeFilter($q, $params = [])
    {
        return $q->where(function ($q) use ($params) {
            if (@$params['keyword'] != '') {
                $keyword = urldecode($params['keyword']);
                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('subject', 'LIKE', '%' . $keyword . '%');
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
            if (@$params['receiver_id'] != '') {
                $q->whereHas('recepients', function ($q) use ($params) {
                    return $q->where('contact_id', $params['receiver_id']);
                });
            }
        });
    }


    public static function EmailServiceFactory(Calllog $calllog)
    {
        $service = new EmailService;
        $type = strip_tags(@self::$typeLabel[$calllog->type]);

        $service->subject($calllog->subject. ' SWFIC '.$type/* .' '.@$calllog->proejct->name */);
        // $service->to(new MailAddress(config('mail.admin.address'), config('mail.admin.name')));
        foreach ($calllog->recepients as $k => $contact) {
            $service->addBcc(new MailAddress($contact->email, $contact->name));
        }

        foreach ($calllog->ccUsers as $user) {
            $service->addCc($user->email);
        }

        foreach ($calllog->getMedia('attachments') as $attachment) {
            $service->addAttachment(new MailAttachment($attachment->getPath(), $attachment->name));
        }

        return $service;
    }

    public function getTemplatePath()
    {
        $label = strtolower(strip_tags(@self::$typeLabel[$this->type]));
        if (!$label) {
            // throw new Exception("Invalid calllog template");
            $label = 'notice';
        }

        return 'emails.calllog.template-' . $label;
        // return view(, $data);
    }


    public function selfDestruct()
    {
        return $this->delete();
    }
}
