<?php

namespace App\Models;

use App\Traits\LastUpdaterTrait;
use App\Traits\ProjectStatusTrait;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Support\Traits\HasJsonFields;

class ProjectCompanyPivot extends Pivot
{
    public $incrementing = true;

    protected $fillable = ['primary_contact_id', 'primary_contacts'];

    protected $casts = ['primary_contacts' => 'json'];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'primary_contact_id');
    }

    public function primaryContacts(){
        return $this->primary_contacts ? collect($this->primary_contacts) : new Collection([]);
    }
}
