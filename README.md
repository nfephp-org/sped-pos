# SPED-POS

Biblioteca para impressão do DANFCe em impressoras térmicas compatíveis.

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

[![Latest Stable Version][ico-stable]][link-packagist]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

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


[ico-stable]: https://poser.pugx.org/nfephp-org/sped-pos/version
[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-pos.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-pos.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-pos.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-pos/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-pos.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-pos.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-pos.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-pos.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square
[ico-gitter]: https://img.shields.io/badge/GITTER-4%20users%20online-green.svg?style=flat-square


[link-packagist]: https://packagist.org/packages/nfephp-org/sped-pos
[link-travis]: https://travis-ci.org/nfephp-org/sped-pos
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-pos/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-pos
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-pos
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-pos/issues
[link-forks]: https://github.com/nfephp-org/sped-pos/network
[link-stars]: https://github.com/nfephp-org/sped-pos/stargazers