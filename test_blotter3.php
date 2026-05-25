<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate exactly what DataTables sends
$params = [
    'draw'   => '1',
    'start'  => '0',
    'length' => '15',
    'search' => ['value' => '', 'regex' => 'false'],
    'order'  => [['column' => '3', 'dir' => 'desc']],
    'columns' => [
        ['data' => 'complainant_col', 'name' => 'complainant_name', 'searchable' => 'true', 'orderable' => 'false', 'search' => ['value'=>'','regex'=>'false']],
        ['data' => 'type_col',        'name' => 'incident_type',    'searchable' => 'true', 'orderable' => 'false', 'search' => ['value'=>'','regex'=>'false']],
        ['data' => 'incident_col',    'name' => 'incident_location','searchable' => 'true', 'orderable' => 'false', 'search' => ['value'=>'','regex'=>'false']],
        ['data' => 'submitted_col',   'name' => 'created_at',       'searchable' => 'true', 'orderable' => 'true',  'search' => ['value'=>'','regex'=>'false']],
        ['data' => 'status_col',      'name' => 'status',           'searchable' => 'true', 'orderable' => 'true',  'search' => ['value'=>'','regex'=>'false']],
        ['data' => 'actions',         'name' => 'actions',          'searchable' => 'false','orderable' => 'false', 'search' => ['value'=>'','regex'=>'false']],
    ],
    'status' => '',
];

try {
    $request = new Illuminate\Http\Request($params);
    $controller = new App\Http\Controllers\AppointmentController();

    // Mock auth user
    $user = App\Models\User::first();
    if ($user) {
        auth()->setUser($user);
        echo 'Authenticated as: ' . $user->email . PHP_EOL;
    }

    $response = $controller->blotterAppointments($request);
    echo 'Response type: ' . get_class($response) . PHP_EOL;
    $content = $response->getContent();
    $decoded = json_decode($content, true);
    if ($decoded) {
        echo 'JSON OK. recordsTotal: ' . ($decoded['recordsTotal'] ?? 'N/A') . PHP_EOL;
        echo 'recordsFiltered: ' . ($decoded['recordsFiltered'] ?? 'N/A') . PHP_EOL;
        echo 'data count: ' . count($decoded['data'] ?? []) . PHP_EOL;
    } else {
        echo 'JSON decode failed. Raw (first 500): ' . substr($content, 0, 500) . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    echo substr($e->getTraceAsString(), 0, 800) . PHP_EOL;
}
