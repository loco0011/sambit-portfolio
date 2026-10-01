<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use function Laravel\Prompts\password;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:user {email} {--name=Admin}', function (string $email) {
    $secret = password(label: 'Password (min 10 characters)', required: true, validate: fn (string $v) => strlen($v) < 10 ? 'Use at least 10 characters.' : null);

    $user = User::updateOrCreate(['email' => $email], ['name' => $this->option('name'), 'password' => $secret]);

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated')." admin {$email}. Sign in at ".url('/admin'));
})->purpose('Create an admin panel user, or reset their password');
