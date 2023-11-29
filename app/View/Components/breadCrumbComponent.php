<?php

namespace App\View\Components;

use Illuminate\View\Component;

class breadCrumbComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $breadCrumbs;
    public function __construct(array $breadCrumbs = [])
    {
        $this->breadCrumbs = $breadCrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $breadCrumbs = $this->breadCrumbs;
        $h1 = $breadCrumbs['h1'] ?? null;
        $bgPages = $breadCrumbs['bcPages'] ?? null;
        return view('components.bread-crumb-component', compact('h1', 'bgPages'));
    }
}
