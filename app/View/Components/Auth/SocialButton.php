<?php

namespace App\View\Components\Auth;

use Illuminate\View\Component;

class SocialButton extends Component
{
    public string $provider;
    public string $context;

    public function __construct(string $provider, string $context = 'login')
    {
        $this->provider = $provider;
        $this->context = $context;
    }

    public function render()
    {
        return view('components.auth.social-button');
    }
}
