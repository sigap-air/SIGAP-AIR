<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$zonas = App\Models\Zona::all();
$zonaBoundaries = $zonas->map(function($z) {
    return [
        'id'           => $z->id,
        'nama_zona'    => $z->nama_zona,
        'kode_zona'    => $z->kode_zona,
        'geo_boundary' => $z->geo_boundary,
    ];
})->values();

echo json_encode($zonaBoundaries, JSON_PRETTY_PRINT);
