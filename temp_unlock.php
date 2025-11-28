<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
\Auth::loginUsingId(1);
$controller = new App\Http\Controllers\CaseController();
$controller->unlockCase(1);
echo App\sCase::find(1)->locked . "\n";
?>
