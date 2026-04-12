<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MasyarakatFormLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $backUrl = null,
    ) {}

    public function render(): View
    {
        return view('layouts.masyarakat-form', [
            'pageTitle' => $this->title ?? 'SIGAP-AIR',
            'backUrl' => $this->backUrl ?? route('masyarakat.dashboard'),
        ]);
    }
}
