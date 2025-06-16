<?php

namespace Support\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class SpatieMedia extends BaseMedia
{

    public function getRelativePath()
    {
        $disk = config('media-library.disk_name');
        $root = config('filesystems.disks.' . $disk . '.root');

        return $root ? str_replace($root, '', $this->getPath()) : $this->getPath();
    }

    public function getThumbUrl($w = 100, $h = 100)
    {
        return route('dynamic.image', $this->getRelativePath()) . '?' . http_build_query([
            'h' => $h,
            'w' => $w
        ]);
    }
}
