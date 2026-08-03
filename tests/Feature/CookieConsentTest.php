<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_banner_is_present_on_a_public_page(): void
    {
        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('cookieConsent(', false);
    }

    /**
     * Refusing has to be exactly as easy as accepting, so both live on the
     * first screen rather than one of them hiding behind a settings panel.
     */
    public function test_accepting_and_refusing_are_offered_side_by_side(): void
    {
        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('Accept toate', false)
            ->assertSee('Refuz toate', false)
            ->assertSee('Preferințe', false);
    }

    public function test_the_choice_can_be_reopened_from_the_footer(): void
    {
        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('Setări cookies', false);
    }

    /**
     * Nothing may load before a choice is made, so the tag must never appear
     * as a plain script the browser would fetch on its own.
     */
    public function test_analytics_is_not_embedded_as_a_loading_script(): void
    {
        config(['services.google_analytics.id' => 'G-TEST12345']);

        $response = $this->get('/docs');

        $response->assertStatus(200)
            ->assertDontSee('<script async src="https://www.googletagmanager.com', false);
    }

    public function test_the_analytics_id_is_available_to_the_page_when_configured(): void
    {
        config(['services.google_analytics.id' => 'G-TEST12345']);

        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('G-TEST12345', false);
    }

    public function test_no_analytics_id_leaks_when_none_is_configured(): void
    {
        config(['services.google_analytics.id' => null]);

        $this->get('/docs')
            ->assertStatus(200)
            ->assertDontSee('googletagmanager', false);
    }

    public function test_the_cookie_policy_explains_the_categories_and_how_to_change_them(): void
    {
        $this->get(route('legal.cookies'))
            ->assertStatus(200)
            ->assertSee('strict necesare', false)
            ->assertSee('analiză', false)
            ->assertSee('Setări cookies', false);
    }
}
