<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function legalRoutes(): array
    {
        return [
            'termeni' => ['legal.terms'],
            'confidentialitate' => ['legal.privacy'],
            'cookies' => ['legal.cookies'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('legalRoutes')]
    public function test_a_guest_can_read_the_legal_pages(string $routeName): void
    {
        $this->get(route($routeName))->assertStatus(200);
    }

    public function test_the_terms_page_names_the_service(): void
    {
        $this->get(route('legal.terms'))
            ->assertStatus(200)
            ->assertSee('Termeni și condiții', false);
    }

    /**
     * The limits on availability only hold up because the service is a free,
     * unfunded side project, so the terms have to say so plainly rather than
     * disclaim uptime out of nowhere.
     */
    public function test_the_terms_state_the_project_is_personal_and_non_profit(): void
    {
        $this->get(route('legal.terms'))
            ->assertStatus(200)
            ->assertSee('proiect personal, non-profit', false)
            ->assertSee('în funcție de disponibilitate', false)
            ->assertSee('planul de găzduire', false);
    }

    public function test_the_privacy_page_lists_what_is_collected(): void
    {
        $this->get(route('legal.privacy'))
            ->assertStatus(200)
            ->assertSee('Politica de confidențialitate', false)
            ->assertSee('adresa de email', false);
    }

    public function test_the_cookies_page_explains_the_session_cookies(): void
    {
        $this->get(route('legal.cookies'))
            ->assertStatus(200)
            ->assertSee('Politica de cookies', false)
            ->assertSee('strict necesare', false);
    }
}
