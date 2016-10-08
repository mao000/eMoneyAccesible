<?php
date_default_timezone_set('America/Bogota');
$sysFecha = Date("Y-m-d");
$estado;
$fecha_inicial = (isset($_POST["fecha_inicial"])) ? $_POST["fecha_inicial"] : '';
$fecha_final = (isset($_POST["fecha_final"])) ? $_POST["fecha_final"] : '';
$fecha_inicial = "2016-02-20";
$fecha_final = "2016-02-21";
$utfi = time();
$cadena_buscada = "[resultCode]=>1";
$context = stream_context_create(array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
        ));
//$rutaSer = require_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/cer_sistema/cer_bc.pem");
$soapParams = array(
    'location' => 'https://181.211.102.40:8443/mts_bce/services/MTSService',
    'login' => "UsrSecap1",
    'password' => "36813e67eb",
    'stream_context' => $context,
    'trace' => TRUE,
    'soap_version' => SOAP_1_2,
  //  'local_cert' => $rutaSer);
 'local_cert' => 'C:\xampp\php\extras\ssl\s_priv_pub.pem');

 //echo $rutaSer;

$soapClient = new SoapClient('https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl', $soapParams);
$cash = new stdClass();
$cash->dtoRequestReportTransaction = new stdClass();
$cash->dtoRequestReportTransaction->dateFrom = $fecha_inicial;
$cash->dtoRequestReportTransaction->dateTo = $fecha_final;
$cash->dtoRequestReportTransaction->language = "ES";
$cash->dtoRequestReportTransaction->page = 0;
$cash->dtoRequestReportTransaction->password = "36813e67eb";
$cash->dtoRequestReportTransaction->rowPerPage = 1000000;
$cash->dtoRequestReportTransaction->sourceId = 80991;
$cash->dtoRequestReportTransaction->user = "UsrSecap1";
$cash->dtoRequestReportTransaction->utfi = $utfi;
$res = $soapClient->reportTransaction($cash);
$error = $res->return->resultText;
$resultCode = $res->return->resultCode;
if ($resultCode == 1) {
    if (isset($res->return->reportTransactionList)) {
        ?>

        <table cellpadding="0" width="100%" cellspacing="0" border="0" class="tablaResultado" id="datosTransacciones" style="font-size:0.8em; font-family:Arial, Helvetica, sans-serif">
            <thead>

                <tr>
                    <th width="1%" style="background: #A7DFF8; color: #004396;">MONTO</th>
                    <th width="1%" style="background: #A7DFF8; color: #004396;">FUENTE</th>
                    <th width="1%" style="background: #A7DFF8; color: #004396;">MONEDA</th>
                    <th width="1%"  style="background: #A7DFF8; color: #004396;">CELULAR</th>
                    <th width="1%"  style="background: #A7DFF8; color: #004396;">ESTADO</th>
                    <th width="1%" style="background: #A7DFF8; color: #004396;">NUM TRANSACCION</th>
                    <th width="1%" style="background: #A7DFF8; color: #004396;">FECHA Y HORA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($res->return->reportTransactionList) == 1) {
                    $datos = $res->return->reportTransactionList;
                    if ($datos->result == 4 || $datos->result == 0 || $datos->result == 3) {
                        ?>
                        <tr id="tr">

                            <td width="1%">
                                <?php echo $datos->amount; ?>                            
                            </td>

                            <td width="1%">
                                <?php echo $datos->brandId; ?>                           
                            </td>

                            <td width="1%">
                                <?php echo $datos->currency; ?>                            
                            </td>

                            <td width="1%">
                                <?php echo $datos->msisdnSource; ?>                            
                            </td>
                            <?php
                            if ($datos->result == 3) {
                                $estado = "REVERSADO";
                            }
                            if ($datos->result == 0) {
                                $estado = "OK";
                            }
                            if ($datos->result == 4) {
                                $estado = "TRANSACCION REVERSO";
                            }
                            ?>
                            <td width="1%">
                                <?php echo $estado; ?>                            
                            </td>

                            <td width="1%">
                                <?php echo $datos->transactionId; ?>                            
                            </td>

                            <td width="1%">
                                <?php echo $datos->tsTransaction; ?>                           
                            </td>
                        </tr>  

                        <?php
                    }
                } else {
                    foreach ($res->return->reportTransactionList as $datos) {

                        //echo json_encode($datos);
                        if ($datos->result == 4 || $datos->result == 0 || $datos->result == 3) {
                            ?>
                            <tr id="tr">

                                <td width="1%">
                                    <?php echo $datos->amount; ?>                            
                                </td>

                                <td width="1%">
                                    <?php echo $datos->brandId; ?>                           
                                </td>

                                <td width="1%">
                                    <?php echo $datos->currency; ?>                            
                                </td>

                                <td width="1%">
                                    <?php echo $datos->msisdnSource; ?>                            
                                </td>
                                <?php
                                if ($datos->result == 3) {
                                    $estado = "REVERSADO";
                                }
                                if ($datos->result == 0) {
                                    $estado = "OK";
                                }
                                if ($datos->result == 4) {
                                    $estado = "TRANSACCION REVERSO";
                                }
                                ?>
                                <td width="1%">
                                    <?php echo $estado; ?>                            
                                </td>

                                <td width="1%">
                                    <?php echo $datos->transactionId; ?>                            
                                </td>

                                <td width="1%">
                                    <?php echo $datos->tsTransaction; ?>                           
                                </td>
                            </tr>  

                            <?php
                        }
                    }
                }
                ?>
            </tbody> 
        </table>
        <?php
    } else {
        echo "1";
    }
} else {
    if ($resultCode == 2) {
        echo "2";
    } else {
        echo "3";
    }
}
?>

