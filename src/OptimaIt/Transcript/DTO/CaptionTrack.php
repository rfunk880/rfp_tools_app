<?php

namespace OptimaIt\Transcript\DTO;

use Support\Traits\ArrayToProps;

class CaptionTrack
{
    use ArrayToProps;

    public $name;
    public $baseUrl;
    public $languageCode;
    public $isTranslatable;
    public $trackName;
    public $vssId;
    public $kind;
}
