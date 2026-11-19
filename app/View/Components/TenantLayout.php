<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TenantLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $backUrl = null,
        public bool $hideBottomNav = false,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.tenant');
    }
}
