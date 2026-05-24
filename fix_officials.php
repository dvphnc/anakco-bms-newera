<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$updates = [
    'Robert S. Romano'          => 'Peace & Order',
    'Salvador L. Enriquez'      => 'Transport & Communication',
    'Euler A. Moreno'           => 'Infrastructure',
    'Joel A. Tamayo'            => 'BDRRM',
    'Freddie C. Marcial'        => 'Livelihood',
    'Twinkle B. Pineda-Corpuz'  => 'Health',
    'Alfredo L. Sicat'          => 'Education',
    'Medel R. Sulpico'          => 'Environment',
];

foreach ($updates as $name => $committee) {
    $rows = App\Models\Official::where('full_name', $name)->update(['committee' => $committee]);
    echo ($rows ? '✓' : '✗') . " {$name} → {$committee}" . PHP_EOL;
}

echo PHP_EOL . "Done. Current state:" . PHP_EOL;
foreach (App\Models\Official::orderBy('id')->get() as $o) {
    echo $o->id . ' | ' . ($o->full_name ?? '—') . ' | ' . $o->position . ' | ' . ($o->committee ?? '—') . PHP_EOL;
}
