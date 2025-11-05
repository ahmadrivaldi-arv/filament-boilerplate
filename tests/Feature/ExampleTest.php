<?php

it('returns a successful response from login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200)
        ->assertSee('login');
});
