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

class TranscriptGenerator
{
  public LanguageTrack $languageTrack;
  public function __construct(public TranscriptFinder $finder)
  {
    $this->languageTrack = $finder->find();
  }


  public function generate()
  {
    // dd($this->languageTrack->captionTrack->baseUrl);
    if (!isset($this->languageTrack->captionTrack->baseUrl) || is_null($this->languageTrack->captionTrack->baseUrl)) {
      throw new NoTranscriptAvailable($this->finder->videoId);
    }
    $response = Http::withHeaders([
      'Accept-Language' => 'en-US'
    ])->acceptJson()->get($this->languageTrack->captionTrack->baseUrl);
  
    // return $response->body();

    $xml = new TranscriptParser($response->body());
   
    return json_encode($xml);
    // return $json;
  }
}
