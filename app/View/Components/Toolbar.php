<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toolbar extends Component
{
    public array $breadcrumbs;
    public string $title;
    public array $actions;

    /**
     * @param array $breadcrumbs [['label' => 'Home', 'url' => route('dashboard')], ...]
     * @param string $title
     * @param array $actions [['label'=>'Go Back', 'url'=>..., 'class'=>'btn-primary'], ...]
     */
    public function __construct(array $breadcrumbs = [], string $title = '', array $actions = [])
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->title = $title;
        $this->actions = $actions;
    }

    public function render(): \Illuminate\Contracts\View\Factory|View|\Illuminate\Contracts\Support\Htmlable|Closure|string|\Illuminate\View\View
    {
        return view('components.toolbar');
    }
}
