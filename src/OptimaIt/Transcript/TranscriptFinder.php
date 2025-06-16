<?php

namespace OptimaIt\Transcript;

use Exception;
use Illuminate\Support\Facades\Http;
use OptimaIt\Transcript\DTO\CaptionTrack;
use OptimaIt\Transcript\DTO\LanguageTrack;
use OptimaIt\Transcript\DTO\LanguagesCollection;
use OptimaIt\Transcript\Exceptions\VideoUnavailable;
use OptimaIt\Transcript\Exceptions\TranscriptsDisabled;
use OptimaIt\Transcript\Exceptions\NoTranscriptAvailable;

class TranscriptFinder
{
  const WATCH_URL = 'https://www.youtube.com/watch?v={video_id}';
  public function __construct(public $videoId)
  {
  }

  public function find()
  {
    $response = Http::acceptJson()
      ->get(str_replace("{video_id}", $this->videoId, self::WATCH_URL));


    $content = explode('"captions":', $response->body());

    if (count($content) < 2) {
      if (strpos($this->videoId, 'http://') !== false || strpos($this->videoId, 'http://') !== false) :
        throw new Exception("Invalid Video Id : " . $this->videoId);
      elseif (strpos($this->videoId, 'class="g-recaptcha"')) :
        throw new  Exception('Too many redirects '.$this->videoId);
      elseif (strpos($this->videoId, 'class="g-recaptcha"')) :
        throw new  VideoUnavailable('Too many redirects '.$this->videoId);
      else :
        throw new  TranscriptsDisabled($this->videoId);
      endif;
    }


    $json = str_replace("\n", '', explode(',"videoDetails', $content[1]));
    $json = json_decode(@$json[0]);

    if ($json && isset($json->playerCaptionsTracklistRenderer) && isset($json->playerCaptionsTracklistRenderer->captionTracks)) {
      // dd( @$json->playerCaptionsTracklistRenderer->translationLanguages);
      return new LanguageTrack(
        CaptionTrack::LazyFromArray((array) @$json->playerCaptionsTracklistRenderer->captionTracks[0]),
        new LanguagesCollection($json->playerCaptionsTracklistRenderer->translationLanguages)
      );
    }
    throw new NoTranscriptAvailable($this->videoId);
  }
}
