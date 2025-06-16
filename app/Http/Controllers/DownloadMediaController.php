<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadMediaController
{
    public function show($id)
    {
        $mediaItem = Media::findOrFail(decryptIt($id));
        return response()->download($mediaItem->getPath(), $mediaItem->name);
    }
}
