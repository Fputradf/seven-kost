<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SevenLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public bool $transparentNavbar = false,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.seven');
    }
}
