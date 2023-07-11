<?php

namespace App\View\Components;

use Illuminate\View\Component;

class dropDownActionComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $routeList;
    public function __construct(array $routeList = [])
    {
        $this->routeList = $routeList;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $routeList = $this->routeList;            
        return view('components.drop-down-action-component', compact('routeList'));
    }
}
