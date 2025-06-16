<?php

namespace Support\Models;


use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'th_media';
    protected $fillable = array('filename', 'original_name', 'mime_type', 'filesize', 'folder');

    public $timestamps = false;

    /**
     * Has one relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachments()
    {
        return $this->hasOne('Support\Models', 'media_id');
    }

    /**
     * one to one relationship with the users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function deleteFromDisk($original = false)
    {
        // $sizes = config('resize.sizes');
        // foreach ($sizes as $thumb => $size) {
        //     @unlink($this->folder . $thumb . $this->filename);
        // }

        if ($original) {
            @\File::deleteDirectory(storage_path('app/cache/.images/' . $this->folder . $this->filename));
            @unlink($this->folder . $this->filename);
        }

    }

    public function selfDestruct()
    {
        //@unlink($this->folder. $this->filename);
        return $this->delete();
    }

}
