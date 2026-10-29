<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = ['/', '/lkpd-apbd', '/input-apbd', '/input-lkpd', '/input-lkpd/create'];

foreach ($urls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    $response = $kernel->handle($request);
    echo "URL '{$url}': Status Code " . $response->getStatusCode() . " (Length: " . strlen($response->getContent()) . ")\n";
}

$kernel->terminate($request, $response);
