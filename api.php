<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$DATA_FILE = __DIR__ . '/data.json';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

// Inicializar data.json si no existe
if (!file_exists($DATA_FILE)) {
    $default = [
        'config' => [
            'companyName' => 'RECORSA',
            'logo' => 'https://showroom.recorsa.net/logo.png',
            'favicon' => '',
            'whatsapp' => '50766750686',
            'address' => 'Panamá, República de Panamá',
            'horario' => "Lun - Vie: 8:00 - 18:00\nSáb: 9:00 - 13:00",
            'catalogName' => 'Showroom Digital B2B',
            'title' => 'Equipos Logísticos e Industriales',
            'subtitle' => 'Soluciones de importación y stock disponible en Panamá',
            'metaTitle' => 'RECORSA - Showroom y Equipos Industriales en Panamá',
            'metaDescription' => 'Consulta equipos logísticos, montacargas e importaciones directas con stock real en Panamá.',
            'heroImage' => 'https://showroom.recorsa.net/logo.png',
            'primaryColor' => '#1e40af',
            'secondaryColor' => '#f97316',
            'latitude' => 8.9824,
            'longitude' => -79.5199,
            'telegramToken' => '',
            'telegramChatId' => '',
            'email' => '',
            'phone' => '',
            'website' => '',
            'ruc' => '',
            'legalText' => 'Precios sujetos a cambio sin previo aviso. Cotizaciones válidas por 30 días. Garantía de fábrica de 12 meses según términos.',
            'itbmsNote' => '*Precios no incluyen ITBMS.'
        ],
        'credentials' => ['username' => 'admin', 'password' => 'admin123'],
        'payments' => [],
        'sections' => [],
        'socialNetworks' => [],
        'projects' => [],
        'analytics' => [],
        'coupons' => []
    ];
    file_put_contents($DATA_FILE, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode($default, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($DATA_FILE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }

    // Asegurar que existan las nuevas claves si se actualiza desde una versión vieja
    if (!isset($data['coupons'])) $data['coupons'] = [];
    if (!isset($data['config']['itbmsNote'])) $data['config']['itbmsNote'] = '*Precios no incluyen ITBMS.';
    if (!isset($data['projects'])) $data['projects'] = [];

    $result = file_put_contents($DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo guardar data.json. Verifica permisos (chmod 666).']);
        exit;
    }
    
    echo json_encode(['success' => true]);
    exit;
}
?>
