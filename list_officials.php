<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$officials = App\Models\Official::orderBy('id')->get();
foreach($officials as $o){
    echo $o->id.' | '.$o->first_name.' '.$o->last_name.' | '.$o->position.' | '.($o->committee??'—').' | '.($o->is_active?'active':'inactive').PHP_EOL;
}
