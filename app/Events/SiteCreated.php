<?php

namespace App\Events;

use App\Models\Site;
use Illuminate\Foundation\Events\Dispatchable;

class SiteCreated
{
    use Dispatchable;

    public function __construct(
        public Site $site
    ) {}
}
