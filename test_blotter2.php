<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test raw query
    $results = App\Models\BlotterCase::where('source', 'portal')->get();
    echo 'Query OK. Found: ' . $results->count() . ' records' . PHP_EOL;

    // Test with columns
    $results2 = App\Models\BlotterCase::select('blotter_cases.*')->where('source', 'portal')->orderBy('created_at', 'desc')->limit(5)->get();
    echo 'Ordered query OK. Found: ' . $results2->count() . PHP_EOL;

    // Check table columns
    $cols = Illuminate\Support\Facades\Schema::getColumnListing('blotter_cases');
    echo 'Columns: ' . implode(', ', $cols) . PHP_EOL;

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
