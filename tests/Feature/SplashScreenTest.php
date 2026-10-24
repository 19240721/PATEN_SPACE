<?php

namespace Tests\Feature;

use Tests\TestCase;

class SplashScreenTest extends TestCase
{
    public function test_logo_asset_exists_in_public_images(): void
    {
        $this->assertFileExists(public_path('images/logo-paten-space.png'));
        $this->assertFileExists(public_path('images/logo-paten-space-full.png'));
    }

    public function test_root_url_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_splash_screen_and_elements(): void
    {
        $response = $this->get('/login');
        $response->assertOk();

        // Verify splash screen container and components
        $response->assertSee('id="paten-splash"', false);
        $response->assertSee('paten-splash-screen', false);
        $response->assertSee('images/logo-paten-space.png', false);
        $response->assertSee('PATEN', false);
        $response->assertSee('SPACE', false);
        $response->assertSee('KECAMATAN JATISARI', false);
        $response->assertSee('Pelayanan Administrasi Terpadu', false);

        // Verify login form is preserved and ready underneath
        $response->assertSee('id="email"', false);
        $response->assertSee('id="password"', false);
        $response->assertSee('id="captcha"', false);
        $response->assertSee('id="login-captcha-image"', false);
        $response->assertSee('login-submit', false);
        $response->assertSee('action="'.route('login.store').'"', false);
    }
}

