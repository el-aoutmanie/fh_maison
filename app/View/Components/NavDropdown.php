<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class NavDropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $active = false,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.nav-dropdown');
    }
}
