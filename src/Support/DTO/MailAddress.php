<?php

namespace Support\DTO;

class MailAddress
{
  public $email;
  public $name;
  public function __construct($email = null, $name = null)
  {
    $this->email = $email;
    $this->name = $name;
  }

  public function toArray()
  {
    return [
      'email' => $this->email,
      'name' => $this->name
    ];
  }
}
