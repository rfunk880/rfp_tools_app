<?php

namespace Support\Contracts;

interface MailService
{
    public function send($view, $data = []);
}
