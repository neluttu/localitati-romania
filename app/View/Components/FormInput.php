<?php
declare(strict_types=1);
namespace App\View\Components;

use Closure;
use Illuminate\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{
    public string $name;
    public ?string $label;
    public string $type;
    public $value;

    public function __construct(
        string $name,
        ?string $label = null,
        string $type = 'text',
        $value = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.form-input');
    }
}
