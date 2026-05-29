<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'member')->first();
$c = Livewire\Livewire::actingAs($user)->test(\App\Livewire\Member\Pinjaman\Create::class);
echo $c->html();
 require 'vendor/autoload.php'; \ = require_once 'bootstrap/app.php'; \ = \->make(Illuminate\Contracts\Console\Kernel::class); \->bootstrap(); \ = Livewire\Livewire::actingAs(\App\Models\User::where('role', 'member')->first())->test(\App\Livewire\Member\Pinjaman\Create::class); echo substr(\->html(), -600);
