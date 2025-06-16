<?php

namespace Support\Models;


use Illuminate\Database\Eloquent\Model;
use Support\Traits\CreatedUpdatedTrait;
use Support\Traits\CreaterUpdaterTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Support\Services\Image\ImageManipulator;
use Themightysapien\Organization\Traits\BelongsToOrganizationTrait;

class MediaAttachment extends Model
{
    use CreaterUpdaterTrait;
    protected $table = 'attachments';
    protected $fillable = array(
        'media_id', 'created_by', 'attachable_id',
        'attachable_type', 'type', 'title', 'remarks'
    );
    protected $with = array('media');

    /*public $timestamps = false;*/


    /**
     * Polymorphic relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachable()
    {
        return $this->morphTo();
    }


    /**
     * belongs to relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }


    public function getFileUrl()
    {
        return $this->getUrl();
    }

    public function getDynamicUrl()
    {
        return asset("img/" . $this->media->getUrl());
    }


    public function getThumbUrl($thumb = 'w=200&h=200')
    {
        //$size = config('resize.sizes.' . $thumb);
        return $this->getDynamicUrl() . '?' . $thumb;
    }



    public function getPath($thumb = null)
    {
        if (!is_null($thumb)) {
            //$size = config('resize.sizes.' . $thumb);
            return public_path($this->media->path);
        }

        return public_path($this->media->folder . $this->media->filename);
    }

    public function isImage()
    {
        return @substr($this->media->mime_type, 0, 5) == 'image';
    }

    public function resize(ImageManipulator $manipulator)
    {


    }


    public function getDownloadUrl()
    {
        return sysUrl('media/download/' . encryptIt($this->id));
    }

    public function selfDestruct($physicalDelete = false)
    {
        if ($physicalDelete) {

            $this->media->delete();
        }

        return $this->delete();
    }
}
