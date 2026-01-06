<?php

// Quick script to make a user admin
// Usage: php make-admin.php email@example.com

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = $argv[1] ?? null;

if (!$email) {
    echo "Usage: php make-admin.php email@example.com\n";
    exit(1);
}

$user = App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "User with email '$email' not found!\n";
    exit(1);
}

$user->role = 'admin';
$user->save();

echo "✓ User '{$user->first_name} {$user->last_name}' ({$user->email}) is now an admin!\n";
