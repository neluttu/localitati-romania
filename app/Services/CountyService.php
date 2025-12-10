<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\County;
use Illuminate\Support\Collection;
use App\Repositories\CountyRepository;

class CountyService
{
    public function __construct(
        protected CountyRepository $counties
    ) {
    }

    public function all(): Collection
    {
        return $this->counties->all();
    }

    public function resolve(string $county): County
    {
        return $this->counties->findByIdOrAbbr($county);
    }
}
