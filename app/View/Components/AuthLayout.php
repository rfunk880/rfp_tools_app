<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthLayout extends Component
{

    public function render()
    {
        return view('layouts.auth');
    }
}
