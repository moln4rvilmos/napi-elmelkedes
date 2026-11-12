<?php
// counter.php — egyszerű, saját látogatószámláló (a vilix.hu / KEM REKUSOK mintájára).
// Nem tárol IP-címet és semmilyen azonosítót; csak darabszámokat.
// Belépési látogatást számol (mint a vilix.hu és a kem-rekusok.hu): csak akkor nő, ha a látogató kívülről érkezett
// (az oldal a document.referrer-t küldi ?r= paraméterben; a telepített alkalmazás indítása is ilyen). Süti nincs.
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

$honnan = strtolower((string)parse_url((string)($_GET['r'] ?? ''), PHP_URL_HOST));
$belso = $honnan !== '' && ($honnan === 'vilix.hu' || str_ends_with($honnan, '.vilix.hu'));
$robot = (bool)preg_match('/bot|crawl|spider|slurp|preview|monitor|curl|wget|python|headless/i', (string)($_SERVER['HTTP_USER_AGENT'] ?? ''));
if (isset($_GET['r']) && !$belso && !$robot) {
    $data['total'] = (int)$data['total'] + 1;
    $data['daily'][$today] = (int)($data['daily'][$today] ?? 0) + 1;
    $data['weekly'][$week] = (int)($data['weekly'][$week] ?? 0) + 1;
    // régi bejegyzések kitakarítása (csak az utolsó ~60 nap / 10 hét kell)
    $data['daily']  = array_slice($data['daily'], -60, null, true);
    $data['weekly'] = array_slice($data['weekly'], -10, null, true);
    if ($fp) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
    }
}
if ($fp) { flock($fp, LOCK_UN); fclose($fp); }
if (isset($_COOKIE['visited'])) setcookie('visited', '', time() - 3600, '/');   // a régi süti törlése

echo json_encode([
    'total'  => (int)$data['total'],
    'daily'  => (int)($data['daily'][$today] ?? 0),
    'weekly' => (int)($data['weekly'][$week] ?? 0),
]);
