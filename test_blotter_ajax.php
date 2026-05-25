<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/appointments/blotter-data', 'GET', [
    'draw' => 1, 'start' => 0, 'length' => 5,
    'search' => ['value' => '', 'regex' => false],
    'order' => [['column' => 3, 'dir' => 'desc']],
    'columns' => [
        ['data' => 'complainant_col', 'name' => 'complainant_name', 'searchable' => true, 'orderable' => false, 'search' => ['value'=>'','regex'=>false]],
        ['data' => 'type_col',        'name' => 'incident_type',    'searchable' => true, 'orderable' => false, 'search' => ['value'=>'','regex'=>false]],
        ['data' => 'incident_col',    'name' => 'incident_location','searchable' => true, 'orderable' => false, 'search' => ['value'=>'','regex'=>false]],
        ['data' => 'submitted_col',   'name' => 'created_at',       'searchable' => true, 'orderable' => true,  'search' => ['value'=>'','regex'=>false]],
        ['data' => 'status_col',      'name' => 'status',           'searchable' => true, 'orderable' => true,  'search' => ['value'=>'','regex'=>false]],
        ['data' => 'actions',         'name' => 'actions',          'searchable' => false,'orderable' => false, 'search' => ['value'=>'','regex'=>false]],
    ],
]);
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');
try {
    $response = $kernel->handle($request);
    echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
    echo substr($response->getContent(), 0, 1000) . PHP_EOL;
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
