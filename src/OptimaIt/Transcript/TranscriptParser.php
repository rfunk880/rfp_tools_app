<?php

namespace OptimaIt\Transcript;

use Exception;
use JsonSerializable;
use Illuminate\Support\Facades\Http;
use OptimaIt\Transcript\DTO\CaptionTrack;
use OptimaIt\Transcript\DTO\LanguageTrack;
use OptimaIt\Transcript\DTO\LanguagesCollection;
use OptimaIt\Transcript\Exceptions\VideoUnavailable;
use OptimaIt\Transcript\Exceptions\TranscriptsDisabled;
use OptimaIt\Transcript\Exceptions\NoTranscriptAvailable;
use SimpleXMLElement;

class TranscriptParser extends SimpleXMLElement implements JsonSerializable
{
    /**
     * SimpleXMLElement JSON serialization
     *
     * @return null|string
     *
     * @link http://php.net/JsonSerializable.jsonSerialize
     * @see JsonSerializable::jsonSerialize
     */
    function jsonSerialize()
    {
        if (count($this)) {
            // serialize children if there are children
            foreach ($this as $tag => $child) {
              // dd($child);
                // child is a single-named element -or- child are multiple elements with the same name - needs array
                if (count($child) > 1) {
                    $child = [$child->children()->getName() => iterator_to_array($child, false)];
                }
                $array[$tag][] = $child;
            }
        } else {
            // serialize attributes and text for a leaf-elements
            foreach ($this->attributes() as $name => $value) {
                $array["$name"] = (string) $value;
            }
            $array["lines"] = (string) $this;
        }

        if ($this->xpath('/*') == array($this)) {
            // the root element needs to be named
            $array = [$this->getName() => $array];
        }

        return $array;
    }
}

