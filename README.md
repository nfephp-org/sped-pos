# SPED-POS

Biblioteca para impressão do DANFCe em impressoras térmicas compatíveis.


## Modo de uso

1. Instale o pacote: `composer require nfephp-org/sped-pos`

2. Instale o pacote escpos-php: `composer require mike42/escpos-php`

3. Inclua a classe DanfcePos: `use NFePHP\POS\DanfcePos;`

4. Veja os códigos exemplo na pasta `demo` sobre como utilizar os conectores da classe escpos-php.

   Para o conector de rede, um exemplo seria: 

   ```php
   <?php
   use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
   $connector = new NetworkPrintConnector('127.0.0.1', 9100);
   ```

1. Inicialize o DanfcePos.

   ```php
   <?php
   use NFePHP\POS\DanfcePos;
   $danfcepos = new DanfcePos($connector);
   ```

2. Carregue o logo e o XML da NFCe.

   ```php
   $logopath = 'logo.png';
   $danfcepos->logo($logopath);
   
   $xmlpath = 'nfce_exemplo.xml';
   $danfcepos->loadNFCe($xmlpath);
   ```

3. Imprima.

   ```php
   $danfcepos->imprimir();
   ```

Em caso de dúvidas sobre o modo de uso, sempre recorra aos exemplos da pasta `demo`.


## Exemplo impresso
<img width="300" src="https://raw.githubusercontent.com/nfephp-org/sped-pos/master/demo/networkprint/networkprint.jpg">

## Demonstrações
Para executar as demonstrações:
1. Clone o repositório

   `git clone https://github.com/nfephp-org/sped-pos.git`

   `cd sped-pos`
2. Instale as dependências

   `composer install`
3. Execute o servidor

   `cd demo`

   `php -S localhost:7000`
4. Acesse no navegador

   NetworkPrint: `http://localhost:7000/networkprint/networkprint.php`

   Base64: `http://localhost:7000/base64/base64.php`

   QZ.io: `http://localhost:7000/qzio`

## Créditos
 - Renan Galeno (desenvolvedor)
 - Roberto L. Machado - pela biblioteca PosPrint, base deste projeto, e pelo espaço no projeto NFePHP.
 - Michael Billington - pelo driver ESC/POS para PHP, amplamente utilizado neste projeto.

## Licença
Este pacote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia Arquivo de Licença para maiores informações.
