<?php

namespace App\View\Components\System;

use Illuminate\View\Component;
use Illuminate\View\View;

class SystemGuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('System.layouts.guests.auth');
    }
}
