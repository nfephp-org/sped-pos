<?php
namespace NFePHP\POS;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\EscposImage;

class DanfcePos
{
    /**
     * NFCe
     * @var \SimpleXMLElement
     */
    protected $nfce;

    /**
     * protNFe
     * @var \SimpleXMLElement
     */
    protected $protNFe;

    /**
     * Printer
     * @var Mike42\Escpos\Printer
     */
    protected $printer;

    /**
     * Logo do emitente
     * @var Mike42\Escpos\EscposImage
     */
    protected $logo;

    /**
     * Total de itens da NFCe
     * @var integer
     */
    protected $totItens = 0;

    /**
     * URI referente a pagina de consulta da NFCe pela chave de acesso
     * @var string
     */
    protected $uri = '';
    protected $aURI = [
        'AC' => 'http://www.sefaznet.ac.gov.br/nfce/consulta',
        'AL' => 'http://nfce.sefaz.al.gov.br/consultaNFCe.htm',
        'AP' => 'https://www.sefaz.ap.gov.br/sate/seg/SEGf_AcessarFuncao.jsp?cdFuncao=FIS_1261',
        'AM' => 'http://sistemas.sefaz.am.gov.br/nfceweb/formConsulta.do',
        'BA' => 'http://nfe.sefaz.ba.gov.br/servicos/nfce/default.aspx',
        'CE' => '',
        'DF' => 'http://dec.fazenda.df.gov.br/NFCE/',
        'ES' => 'http://app.sefaz.es.gov.br/ConsultaNFCe',
        'GO' => '',
        'MA' => 'http://www.nfce.sefaz.ma.gov.br/portal/consultaNFe.do?method=preFilterCupom&',
        'MT' => 'http://www.sefaz.mt.gov.br/nfce/consultanfce',
        'MS' => 'http://www.dfe.ms.gov.br/nfce',
        'MG' => '',
        'PA' => 'https://appnfc.sefa.pa.gov.br/portal/view/consultas/nfce/consultanfce.seam',
        'PB' => 'http://www.receita.pb.gov.br/nfce',
        'PR' => 'http://www.fazenda.pr.gov.br',
        'PE' => '',
        'PI' => 'http://webas.sefaz.pi.gov.br/nfceweb/consultarNFCe.jsf',
        'RJ' => 'www.nfce.fazenda.rj.gov.br/consulta',
        'RN' => 'http://nfce.set.rn.gov.br/consultarNFCe.aspx',
        'RS' => 'https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx',
        'RO' => 'http://www.nfce.sefin.ro.gov.br',
        'RR' => 'https://www.sefaz.rr.gov.br/nfce/servlet/wp_consulta_nfce',
        'SC' => '',
        'SP' => 'https://www.nfce.fazenda.sp.gov.br/NFCeConsultaPublica/Paginas/ConsultaPublica.aspx',
        'SE' => 'http://www.nfce.se.gov.br/portal/portalNoticias.jsp',
        'TO' => ''
    ];

    /**
     * Carrega o conector da impressora.
     * @param Printer $printer
     */
    public function __construct(PrintConnector $connector)
    {
        $this->printer = new Printer($connector);
    }

    /**
     * Carrega a NFCe
     * @param string $nfcexml
     */
    public function loadNFCe($nfcexml)
    {
        $xml = $nfcexml;
        if (is_file($nfcexml)) {
            $xml = @file_get_contents($nfcexml);
        }
        if (empty($xml)) {
            throw new \InvalidArgumentException('Não foi possivel ler o documento.');
        }
        $nfe = simplexml_load_string($xml, null, LIBXML_NOCDATA);
        $this->protNFe = $nfe->protNFe;
        $this->nfce = $nfe->NFe;
        if (empty($this->protNFe)) {
            //NFe sem protocolo
            $this->nfce = $nfe;
        }
    }

    /**
     * Carrega o logo do emitente
     * @param string $logopath
     */
    public function logo($logopath)
    {
        $this->logo = EscposImage::load($logopath);
    }

    /**
     * Imprime o DANFCE
     */
    public function imprimir()
    {
        $this->parteI();
        $this->parteII();
        $this->parteIII();
        $this->parteIV();
        $this->parteV();
        $this->parteVI();
        $this->parteVII();
        $this->parteVIII();
        $this->parteIX();
        $this->printer->feed();
        $this->printer->cut();
        $this->printer->close();
    }

    /**
     * Parte I - Emitente
     * Dados do emitente
     * Campo Obrigatório
     */
    protected function parteI()
    {
        $razao = (string)$this->nfce->infNFe->emit->xNome;
        $cnpj = (string)$this->nfce->infNFe->emit->CNPJ;
        $ie = (string)$this->nfce->infNFe->emit->IE;
        $im = (string)$this->nfce->infNFe->emit->IM;
        $log = (string)$this->nfce->infNFe->emit->enderEmit->xLgr;
        $nro = (string)$this->nfce->infNFe->emit->enderEmit->nro;
        $bairro = (string)$this->nfce->infNFe->emit->enderEmit->xBairro;
        $mun = (string)$this->nfce->infNFe->emit->enderEmit->xMun;
        $uf = (string)$this->nfce->infNFe->emit->enderEmit->UF;
        if (array_key_exists($uf, $this->aURI)) {
            $this->uri = $this->aURI[$uf];
        }
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        if (!empty($this->logo)) {
            $this->printer->graphics($this->logo);
        }
        $this->printer->setEmphasis(true);
        $this->printer->text($razao . "\n");
        $this->printer->setEmphasis(false);
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->printer->text("CNPJ: " . $cnpj . "     " . "IE: " . $ie . "\n");
        $this->printer->text($log . ', ' . $nro . "\n");
        $this->printer->text($bairro . ', ' . $mun . ' - ' . $uf . "\n");
        $this->printer->feed();
    }

    /**
     * Parte II - Informações Gerais
     * Campo Obrigatório
     */
    protected function parteII()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->setEmphasis(true);
        $this->printer->text("DANFCe - Documento Auxiliar da Nota Fiscal\nde Consumidor Eletronica\n");
        $this->printer->setEmphasis(false);
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->printer->text("Não permite aproveitamento de crédito de ICMS.\n");
        $this->separador();
    }

    /**
     * Parte III - Detalhes da Venda
     * Campo Opcional
     */
    protected function parteIII()
    {
        // Cabeçalho
        $this->printer->setEmphasis(true);
        $this->printer->text("Cód.  Descrição          Qtd. Un.  Valor   Total\n");
        // Itens da NFCe
        $det = $this->nfce->infNFe->det;
        $this->totItens = $det->count();
        $vTot = 0;
        for ($x = 0; $x <= $this->totItens - 1; $x++) {
            $nItem = (int)$det[$x]->attributes()->{'nItem'};
            $cProd = (string)$det[$x]->prod->cProd;
            $xProd = (string)$det[$x]->prod->xProd;
            $qCom = (float)$det[$x]->prod->qCom;
            $uCom = (string)$det[$x]->prod->uCom;
            $vUnCom = (float)$det[$x]->prod->vUnCom;
            $vProd = (float)$det[$x]->prod->vProd;
            $vTot += $vProd;
            // Formatar dados do item
            $linha = new \stdClass();
            $linha->cod = $this->str_pad($cProd, 6, ' ');
            $linha->descricao = $this->str_pad($xProd, 19, ' ');
            $linha->quantidade = $this->str_pad($qCom, 5, ' ');
            $linha->unidade = $this->str_pad($uCom, 3, ' ');
            $linha->valor_unit = $this->str_pad(number_format($vUnCom, 2, ',', '.'), 7, ' ', STR_PAD_LEFT);
            $linha->valor_total = $this->str_pad(number_format($vProd, 2, ',', '.'), 8, ' ', STR_PAD_LEFT);
            // Imprimir linha
            $this->printer->text($linha->cod . $linha->descricao . $linha->quantidade . $linha->unidade . $linha->valor_unit . $linha->valor_total . "\n");
        }
        $printtot = str_pad((string)"Total:", 31, " ", STR_PAD_LEFT) . str_pad("R$" . number_format($vTot, 2, ',', '.'), 17, " ", STR_PAD_LEFT);
        $this->printer->setEmphasis(true);
        $this->printer->text($printtot . "\n");
        $this->printer->setEmphasis(false);
    }

    /**
     * Parte IV - Totais da Venda
     * Campo Obrigatório
     */
    protected function parteIV()
    {
        $vNF = (float)$this->nfce->infNFe->total->ICMSTot->vNF;
        $printTotItens = $this->str_pad('Qtd. Total:', 31, " ", STR_PAD_LEFT) . $this->str_pad((string)$this->totItens, 17, " ", STR_PAD_LEFT);
        $this->printer->text($printTotItens . "\n");
        $this->separador();
        $pag = $this->nfce->infNFe->pag->detPag;
        $tot = $pag->count();
        for ($x = 0; $x <= $tot - 1; $x++) {
            $tPag = (string)$pag->tPag;
            $tPag = (string)$this->tipoPag($tPag);
            $vPag = (float)$pag->vPag;
            $printFormPag = $this->str_pad($tPag . ":", 31, " ", STR_PAD_LEFT) . $this->str_pad("R$" . number_format($vPag, 2, ',', '.'), 17, " ", STR_PAD_LEFT);
            $this->printer->text($printFormPag . "\n");
        }
    }

    /**
     * Parte V - Informação de tributos
     * Campo Obrigatório
     */
    protected function parteV()
    {
        $vTotTrib = (float)$this->nfce->infNFe->total->ICMSTot->vTotTrib;
        $printimp = $this->str_pad("Informação dos Tributos Incidentes:", 35, " ") . str_pad("R$" . number_format($vTotTrib, 2, ',', '.'), 13, " ", STR_PAD_LEFT);
        $this->printer->text($printimp . "\n");
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text("Fonte IBPT - Lei Federal 12.741/2012\n");
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->separador();
    }


    /**
     * Parte VI - Mensagem de Interesse do Contribuinte
     * conteudo de infCpl
     * Campo Opcional
     */
    protected function parteVI()
    {
        $infCpl = (string)$this->nfce->infNFe->infAdic->infCpl;
        if (!empty($infCpl)) {
            $this->printer->text($infCpl . "\n");
        }
    }

    /**
     * Parte VII - Mensagem Fiscal e Informações da Consulta via Chave de Acesso
     * Campo Obrigatório
     */
    protected function parteVII()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $tpAmb = (int)$this->nfce->infNFe->ide->tpAmb;
        if ($tpAmb == 2) {
            $this->printer->text("EMITIDA EM AMBIENTE DE HOMOLOGAÇÃO\nSEM VALOR FISCAL\n");
        }
        $tpEmis = (int)$this->nfce->infNFe->ide->tpEmis;
        if ($tpEmis != 1) {
            $this->printer->text("EMITIDA EM AMBIENTE DE CONTINGÊNCIA\n");
        }
        $nNF = (string)$this->nfce->infNFe->ide->nNF;
        $serie = (int)$this->nfce->infNFe->ide->serie;
        $dhEmi = (string)$this->nfce->infNFe->ide->dhEmi;
        $Id = (string)$this->nfce->infNFe->attributes()->{'Id'};
        $chave = substr($Id, 3, strlen($Id) - 3);
        //$this->printer->text('Nr. ' . $nNF. ' Serie ' .$serie . ' Emissão ' .$dhEmi . ' via Consumidor');
        $linha = new \stdClass();
        $linha->numero = $this->str_pad("NFCe: " . preg_replace("/[^0-9]/", "", $nNF), 15);
        $linha->serie = $this->str_pad("Série: " . $serie, 10);
        $linha->data = $this->str_pad(date('d/m/Y H:i:s', strtotime($dhEmi)), 23, ' ', STR_PAD_LEFT);
        $this->printer->text($linha->numero . $linha->serie . $linha->data . "\n");
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->printer->text("Consulte pela chave de acesso em ");
        $this->printer->text($this->uri . "\n");
        $this->printer->text("CHAVE DE ACESSO\n");
        $this->printer->text($chave . "\n");
        $this->separador();
    }

    /**
     * Parte VIII - Informações sobre o Consumidor
     * Campo Opcional
     */
    protected function parteVIII()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $dest = $this->nfce->infNFe->dest;
        if (empty($dest)) {
            $this->printer->setEmphasis(true);
            $this->printer->text("CONSUMIDOR NAO IDENTIFICADO\n");
            $this->printer->setEmphasis(false);
            return;
        }
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $xNome = (string)$this->nfce->infNFe->dest->xNome;
        $this->printer->text($xNome . "\n");
        $cnpj = (string)$this->nfce->infNFe->dest->CNPJ;
        $cpf = (string)$this->nfce->infNFe->dest->CPF;
        $idEstrangeiro = (string)$this->nfce->infNFe->dest->idEstrangeiro;
        if (!empty($cnpj)) {
            $this->printer->text("CNPJ " . $cnpj . "\n");
            $this->printer->lineFeed();
        }
        if (!empty($cpf)) {
            $this->printer->text("CPF " . $cpf . "\n");
        }
        if (!empty($idEstrangeiro)) {
            $this->printer->text("Estrangeiro " . $idEstrangeiro . "\n");
        }
        $xLgr = (string)$this->nfce->infNFe->dest->enderDest->xLgr;
        $nro = (string)$this->nfce->infNFe->dest->enderDest->nro;
        $xCpl = (string)$this->nfce->infNFe->dest->enderDest->xCpl;
        $xBairro = (string)$this->nfce->infNFe->dest->enderDest->xBairro;
        $xMun = (string)$this->nfce->infNFe->dest->enderDest->xMun;
        $uf = (string)$this->nfce->infNFe->dest->enderDest->UF;
        $cep = (string)$this->nfce->infNFe->dest->enderDest->CEP;
        $this->printer->text($xLgr . ", " . $nro . ".\n");
        if (!empty($xCpl)) {
            $this->printer->text($xCpl . ", ");
        }
        $this->printer->text($xBairro . ". ");
        $this->printer->text($xMun . " - " . $uf . ".\n");
        $this->separador();
    }

    /**
     * Parte IX - QRCode
     * Campo Obrigatório
     */
    protected function parteIX()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text("Consulte via Leitor de QRCode\n");
        $qr = (string)$this->nfce->infNFeSupl->qrCode;
        // $this->printer->barcodeQRCode($qr);
        $this->printer->qrCode($qr, Printer::QR_ECLEVEL_L, 5);
        $this->printer->feed();
        if (!empty($this->protNFe)) {
            $nProt = (string)$this->protNFe->infProt->nProt;
            $dhRecbto = (string)$this->protNFe->infProt->dhRecbto;
            $this->printer->text("Protocolo de autorização: " . $nProt . "\n");
        } else {
            $this->printer->setEmphasis(true);
            $this->printer->text("NOTA FISCAL INVÁLIDA - SEM PROTOCOLO DE AUTORIZAÇÃO\n");
            $this->printer->setEmphasis(false);
        }
    }

    /**
     * Returns payment method as text.
     * @param int $tPag
     * @return string
     */
    private function tipoPag($tPag)
    {
        $aPag = [
            '01' => 'Dinheiro',
            '02' => 'Cheque',
            '03' => 'Cartao de Credito',
            '04' => 'Cartao de Debito',
            '05' => 'Credito Loja',
            '10' => 'Vale Alimentacao',
            '11' => 'Vale Refeicao',
            '12' => 'Vale Presente',
            '13' => 'Vale Combustivel',
            '99' => 'Outros'
        ];
        if (array_key_exists($tPag, $aPag)) {
            return $aPag[$tPag];
        }
        return '';
    }

    private function str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $diff = strlen($input) - mb_strlen($input);
        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }

    private function separador()
    {
        $this->printer->text(str_repeat('=', 48) . "\n");
    }


    /**
     * Function to encode a number as two bytes. This is straight out of Mike42\Escpos\Printer
     * @param int $tPag
     * @return string
     */
    function intLowHigh($input, $length)
    {
        $outp = "";
        for ($i = 0; $i < $length; $i++) {
            $outp .= chr($input % 256);
            $input = (int)($input / 256);
        }
        return $outp;
    }
}