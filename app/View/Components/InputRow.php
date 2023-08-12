<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputRow extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $label;
    public $type;

    public function __construct(string $type, string $name, string $label)
    {
        $this->type  = $type;
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
        return view('components.input-row');
    }
}
