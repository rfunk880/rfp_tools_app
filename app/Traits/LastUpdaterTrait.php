<?php
namespace App\Traits;

use DateTime;
use App\Models\User;
use App\Models\UserType;

trait LastUpdaterTrait
{
    public function lastUpdatedBy()
    {
        return $this->updater ? $this->updater : $this->creator;
    }

    public function lastUpdatedAt($format = 'd M Y, g:i a')
    {
        return $this->updated_at ? $this->updated_at->format($format) : $this->created_at->format($format);
    }
}
