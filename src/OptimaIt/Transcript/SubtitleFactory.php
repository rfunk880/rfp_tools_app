<?php

namespace OptimaIt\Transcript;

use Exception;
use Done\Subtitles\Subtitles;

class SubtitleFactory
{
    public static function ArrayFromYoutube($array)
    {
        $subtitles = new Subtitles();
        foreach ($array as $k => $caption) {
            /* "start": "0.12",
                "dur": "4.04",
                "lines": "welcome to this generative AI mini" */

            $subtitles->add((float) $caption['start'], ((float) $caption['start'] + (float) $caption['dur']), html_entity_decode($caption['lines']));
        }


        return $subtitles;
    }
}
