<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Auth::loginUsingId(29);
$request = Illuminate\Http\Request::create('/view-book/20', 'HEAD');
$appResponse = $app->handle($request);
echo 'status:'.$appResponse->getStatusCode();
