<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$case = App\sCase::first();
echo $case ? ($case->id." " . $case->locked . "\n") : 'no case';
?>
