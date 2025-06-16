<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $fillable = ['company_id', 'name', 'phone', 'cell', 'email',
     'company_id', 'is_primary', 'notes', 'title', 'location'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePrimary($q)
    {
        return $q->where('is_primary', 1);
    }

    public function selfDestruct()
    {
        $this->tags()->detach();
        return $this->delete();
    }
}
