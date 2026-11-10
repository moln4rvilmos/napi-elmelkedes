<?php
// counter.php — egyszerű, saját látogatószámláló (a vilix.hu / KEM REKUSOK mintájára).
// Nem tárol IP-címet és semmilyen azonosítót; csak darabszámokat.
// Egy napig érvényes süti ('visited') akadályozza, hogy ugyanaz a látogató többször számítson.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$dir  = __DIR__ . '/data';
$file = $dir . '/counter.json';
if (!is_dir($dir)) @mkdir($dir, 0755, true);

$fp = @fopen($file, 'c+');
$data = ['total' => 0, 'daily' => [], 'weekly' => []];
if ($fp) {
    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $decoded = $raw ? json_decode($raw, true) : null;
    if (is_array($decoded)) $data = array_merge($data, $decoded);
}

$today = date('Y-m-d');
$week  = date('oW');

if (!isset($_COOKIE['visited'])) {
    $data['total'] = (int)$data['total'] + 1;
    $data['daily'][$today] = (int)($data['daily'][$today] ?? 0) + 1;
    $data['weekly'][$week] = (int)($data['weekly'][$week] ?? 0) + 1;
    // régi bejegyzések kitakarítása (csak az utolsó ~60 nap / 10 hét kell)
    $data['daily']  = array_slice($data['daily'], -60, null, true);
    $data['weekly'] = array_slice($data['weekly'], -10, null, true);
    setcookie('visited', '1', ['expires' => time() + 86400, 'path' => '/', 'samesite' => 'Lax']);
    if ($fp) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
    }
}
if ($fp) { flock($fp, LOCK_UN); fclose($fp); }

echo json_encode([
    'total'  => (int)$data['total'],
    'daily'  => (int)($data['daily'][$today] ?? 0),
    'weekly' => (int)($data['weekly'][$week] ?? 0),
]);
