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
     * The tag loads on every page so Google's own check can find it, which is
     * the whole reason for running consent mode instead of withholding the
     * script.
     */
    public function test_the_tag_is_present_when_configured(): void
    {
        config(['services.google_analytics.id' => 'G-TEST12345']);

        $this->get('/docs')
            ->assertStatus(200)
            ->assertSee('googletagmanager.com/gtag/js?id=G-TEST12345', false);
    }

    /**
     * Consent mode only protects anyone if the denied default is already in
     * the dataLayer when the tag starts. Emitted afterwards, the tag measures
     * first and reads the default second - the ordering is the safeguard, so
     * it is asserted rather than assumed.
     */
    public function test_measurement_is_denied_before_the_tag_loads(): void
    {
        config(['services.google_analytics.id' => 'G-TEST12345']);

        $html = $this->get('/docs')->assertStatus(200)->getContent();

        $defaultAt = strpos($html, "'consent', 'default'");
        $tagAt = strpos($html, 'googletagmanager.com/gtag/js');

        $this->assertNotFalse($defaultAt, 'Lipsește setarea implicită de consimțământ.');
        $this->assertNotFalse($tagAt, 'Lipsește eticheta Google.');
        $this->assertLessThan($tagAt, $defaultAt, 'Implicitul trebuie emis înaintea etichetei.');
        $this->assertStringContainsString("analytics_storage: 'denied'", $html);
    }

    public function test_advertising_signals_are_denied_and_never_asked_for(): void
    {
        config(['services.google_analytics.id' => 'G-TEST12345']);

        $html = $this->get('/docs')->assertStatus(200)->getContent();

        $this->assertStringContainsString("ad_storage: 'denied'", $html);
        $this->assertStringContainsString("ad_user_data: 'denied'", $html);
        $this->assertStringContainsString("ad_personalization: 'denied'", $html);
        $this->assertStringNotContainsString("ad_storage: 'granted'", $html);
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
