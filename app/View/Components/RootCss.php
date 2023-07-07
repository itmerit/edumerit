<?php

namespace App\View\Components;

use App\Models\Theme;
use Illuminate\View\Component;

class RootCss extends Component
{

    public function render()
    {
        $data['color_theme'] = color_theme();
        return view('components.root-css', $data);
    }
}
