<?php
require_once "../../vendor/autoload.php";

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use NFePHP\POS\DanfcePos;

// Configuração de impressão
$printer_ip = '127.0.0.1'; // IP da impressora
$printer_porta = 9100; // Porta de conexão

// Conectar à impressora térmica
try {
    $connector = new NetworkPrintConnector($printer_ip, $printer_porta);
} catch (\Exception $ex) {
    die('Não foi possível conectar com a impressora.');
}

// Inicializar DanfcePos
$danfcepos = new DanfcePos($connector);

// Carregar logo da empresa
$logopath = '../../fixtures/logo.png'; // Impressa no início da DANFCe
$danfcepos->logo($logopath);

// Carregar NFCe
$xmlpath = '../../fixtures/nfce_exemplo.xml'; // Também poderia ser o conteúdo do XML, no lugar do path
$danfcepos->loadNFCe($xmlpath);

// Imprimir
$danfcepos->imprimir();

echo 'DANFCe impresso.';