<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'member')->first();
$c = Livewire\Livewire::actingAs($user)->test(\App\Livewire\Member\Pinjaman\Create::class);
echo "HTML OUTPUT LENGTH: " . strlen($c->html()) . "\n\n";
echo substr($c->html(), -800);
