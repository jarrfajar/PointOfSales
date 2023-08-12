<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSelectCol extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $label;

    public function __construct(string $name, string $label)
    {
        $this->name  = $name;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-select-col');
    }
}
