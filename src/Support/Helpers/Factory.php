<?php

namespace Support\Helpers;


class Factory
{

    public static $SHARED_COLLECTIONS = [];
    public static $MEMORY_ITEMS = [];

    public static function UploadService($fileHandler, $path)
    {
        return new \Support\Services\UploadService($fileHandler, $path);
    }

    public static function NewAttachment($data = array())
    {
        /*print_r($data);
        die();*/
        // dd($data);
        $media = new \Support\Models\Media($data);
        $media->save();
        return new \Support\Models\Attachment(array(
            'media_id' => $media->id,
            'type' => @$data['type'],
            'title' => @$data['title'],
            // 'remarks' => @$data['remarks'],
            'created_by' => \Auth::id() ? \Auth::id() : 0
        ));
    }
}
