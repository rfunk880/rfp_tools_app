<?php

namespace OptimaIt\Transcript\DTO;

use Support\Traits\ArrayToProps;

class LanguageTrack
{
    use ArrayToProps;

    public function __construct(public CaptionTrack $captionTrack, public LanguagesCollection $languages)
    {
    }
}
