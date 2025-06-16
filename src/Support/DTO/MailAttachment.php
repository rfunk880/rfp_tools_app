<?php

namespace Support\DTO;

class MailAttachment
{
    public $path;
    public $name;
    public $mime;
    public $contents;


    public function __construct($path = null, $name = null, $contents = null, $mime = null)
    {
        $this->path = $path;
        $this->name = $name;
        $this->mime = $mime;
        $this->contents = $contents;
    }
}
