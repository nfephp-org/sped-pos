<?php
require_once "../../vendor/autoload.php";

use NFePHP\POS\PrintConnectors\Base64PrintConnector;
use NFePHP\POS\DanfcePos;

// Inicializar conector
$connector = new Base64PrintConnector();

// Inicializar DanfcePos
$danfcepos = new DanfcePos($connector);

// Carregar logo da empresa
$logopath = '../../fixtures/logo.png'; // Impressa no início da DANFCe
$danfcepos->logo($logopath);

// Carregar NFCe
$xmlpath = '../../fixtures/nfce_exemplo.xml'; // Também poderia ser o conteúdo do XML, no lugar do path
$danfcepos->loadNFCe($xmlpath);

// Gerar impressão
$danfcepos->imprimir();

// Obter impressão em base64
$base64 = $connector->getBase64Data();

// Retornar resposta
echo $base64;