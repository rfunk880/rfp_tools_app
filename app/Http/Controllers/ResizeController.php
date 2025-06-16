<?php
namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Traits\UploaderTrait;
use League\Glide\ServerFactory;
use App\Exceptions\ApplicationException;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;

class ResizeController extends Controller
{
    public function image(Filesystem $filesystem, $path)
    {
        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => config('filesystems.disks.media.root'),
            'cache' => storage_path("app/cache"),
            'cache_path_prefix' => '.images',
            'base_url' => 'img',
        ]);
        return $server->getImageResponse($path, request()->all());
    }
}