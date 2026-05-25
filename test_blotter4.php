<?php
// Test the actual HTTP request to blotter-data with full middleware stack
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// First: create a session and login
$user = null;
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = App\Models\User::where('email', 'admin@bms.gov.ph')->first();
if (!$user) { echo "No admin user found\n"; exit; }

// Create a request WITH a session (simulate logged-in state)
$request = Illuminate\Http\Request::create('/appointments/blotter-data', 'GET', [
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
]);

$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

// Manually auth the request
auth()->setUser($user);
$request->setUserResolver(function () use ($user) { return $user; });

// Set session
$session = $app->make('session.store');
$session->put('login_web_' . sha1(Illuminate\Auth\SessionGuard::class), $user->getAuthIdentifier());
$request->setLaravelSession($session);

try {
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    echo "Status: $status\n";
    if ($status === 200) {
        $data = json_decode($content, true);
        if ($data) {
            echo "recordsTotal: " . ($data['recordsTotal'] ?? 'N/A') . "\n";
            echo "recordsFiltered: " . ($data['recordsFiltered'] ?? 'N/A') . "\n";
            echo "data count: " . count($data['data'] ?? []) . "\n";
        } else {
            echo "Non-JSON response (first 300): " . substr($content, 0, 300) . "\n";
        }
    } else {
        echo "Error response (first 500): " . substr($content, 0, 500) . "\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "In: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
