<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Internship;

$internship = Internship::first();
echo "Internship data:\n";
foreach ($internship->toArray() as $key => $value) {
    echo "{$key}: " . (is_null($value) ? 'NULL' : $value) . "\n";
}
