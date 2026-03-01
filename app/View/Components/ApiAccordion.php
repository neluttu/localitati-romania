<?php
declare(strict_types=1);
namespace App\View\Components;

use Illuminate\View\Component;

class ApiAccordion extends Component
{
    public function __construct(
        public string $method = 'GET',
        public string $url = '',
    ) {
    }

    public function render()
    {
        return view('components.api-accordion');
    }
}
