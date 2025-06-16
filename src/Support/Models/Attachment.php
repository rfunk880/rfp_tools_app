<?php

namespace Support\Models;


use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Support\Traits\CreatedUpdatedTrait;
use Support\Traits\CreaterUpdaterTrait;
use Support\Services\Image\ImageManipulator;
use Illuminate\Http\UploadedFile;
use Themightysapien\Organization\Traits\BelongsToOrganizationTrait;

class Attachment extends Model
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
        return asset(str_replace("./", '', $this->media->folder) . $this->media->filename);
    }

    public function getDynamicUrl()
    {
        return asset("img/" . $this->media->folder . $this->media->filename);
    }


    public function getThumbUrl($thumb = 'w=200&h=200')
    {
        //$size = config('resize.sizes.' . $thumb);
        return $this->getDynamicUrl() . '?' . $thumb;
    }

    public function getThumbFromUrl($url, $thumb = 'thumb', $thumbSeparator = '/')
    {
        $folder = str_replace(config('resize.storage_link_folder') . '/', '', $this->media->folder);
        return $url . $folder . $thumb . $thumbSeparator . $this->media->filename;
    }

    public function getPath($thumb = null)
    {
        if (!is_null($thumb)) {
            //$size = config('resize.sizes.' . $thumb);
            return public_path($this->media->folder . $thumb . $this->media->filename);
        }

        return public_path($this->media->folder . $this->media->filename);
    }

    public function isImage()
    {
        return @substr($this->media->mime_type, 0, 5) == 'image';
    }

    public function resize(ImageManipulator $manipulator)
    {

        if (\File::exists($this->media->folder . $this->media->filename)) {
            $this->media->deleteFromDisk();
            $manipulator->resize($this->media->filename, $this->media->folder);
        }
    }


    public static function pathToUploadedFile($path, $name = null, $public = false)
    {
        // dd($name);
        $name = $name ? $name : File::name($path);
        $extension = File::extension($path);
        $originalName = $name;
        $mimeType = File::mimeType($path);
        // $size = File::size($path);
        $error = null;
        $test = $public;
        $object = new UploadedFile($path, $originalName, $mimeType, $error, $test);
        return $object;
    }


    public function getDownloadUrl()
    {
        return sysUrl('media/download/' . encryptIt($this->id));
    }


    public function selfDestruct($physicalDelete = false)
    {
        if ($physicalDelete) {
            $this->media->deleteFromDisk(true);
            $this->media->selfDestruct();
        }
        /*foreach($this->sizes as $size){
            @unlink('./uploads/attachments/' .$size[0].'X'.$size[1]. $this->filename);
        }

        @unlink('./uploads/attachments/' . $this->filename);
        @unlink('./uploads/documents/' . $this->filename);*/
        return $this->delete();
    }
}