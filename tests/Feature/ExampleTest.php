<?php

// The home page is the staff dashboard, so guests are sent to the login page.
// (Replaces the Laravel starter test, which expected a public welcome page.)

it('sends guests to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});

it('shows the login page', function () {
    $this->get(route('login'))->assertOk()->assertSee('Sign In');
});
