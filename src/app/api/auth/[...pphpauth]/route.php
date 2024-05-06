<?php

use Lib\Auth\Auth;
use Lib\Auth\GithubProvider;
use Lib\Auth\GoogleProvider;

$auth = new Auth();

if ($auth->isAuthenticated()) {
    redirect('/dashboard');
    exit;
}

$auth->authProviders(
    new GithubProvider(
        $_ENV['AUTH_GITHUB_CLIENT_ID'],
        $_ENV['AUTH_GITHUB_CLIENT_SECRET']
    ),
    new GoogleProvider(
        $_ENV['AUTH_GOOGLE_CLIENT_ID'],
        $_ENV['AUTH_GOOGLE_CLIENT_SECRET'],
        "http://localhost:3000/api/auth/callback/google"
    )
);

redirect('/dashboard');
