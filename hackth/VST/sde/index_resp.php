<!doctype html>
<html>
    <head>
        <meta charset="utf8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>jQuery Dropdown Login Freebie | The Finished Box</title>
        <link rel="stylesheet" href="css/style.css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js?ver=1.4.2"></script>
        <script src="js/login.js"></script>
    </head>
    <body>                
                        <form id="loginForm" method="post">
                            <fieldset id="body">
                                <fieldset>
                                    <label for="email">Monto</label>
                                    <input type="text" name="monto" id="monto" />
                                </fieldset>
                                <fieldset>
                                    <label for="password">C&eacute;dula</label>
                                    <input type="text" name="cedula" id="cedula" />
                                </fieldset>
                                <fieldset>
                                    <label for="password">N&uacute;mero celular</label>
                                    <input type="text" name="celular" id="celular" />
                                </fieldset>
								<fieldset>
                                    <label for="password">Usuario</label>
                                    <input type="text" name="usuario" id="usuario" />
                                </fieldset>
								<fieldset>
                                    <label for="password">Password</label>
                                    <input type="password" name="contrasena" id="contrasena" />
                                </fieldset> 
                                <input type="submit" id="cobrar" name="monitoreo" value="Monitorear Servicio" />
                            </fieldset>
                        </form>
            <?php
            //FUNCIONES
            if (isset($_POST['monitoreo'])) {
                for($a=1; $a<= 1440; $a++){
                    $respuesta=cobroPRE($_POST['monto'],$_POST['cedula'],$_POST['celular'],$_POST['usuario'],$_POST['contrasena']);
                    $file = fopen("datos.txt", "a");
                    if($respuesta==1){
                        fwrite($file, date("d.m.Y H:i:s") . " COBRO OK" . PHP_EOL);
                    }
					if($respuesta==2){
                        fwrite($file, date("d.m.Y H:i:s") . " COBRO ERROR" . PHP_EOL);
                        //sms();
                    }
                    sleep(300);
                    fclose($file);               
               }
            }
           
            function sms(){
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://172.22.17.72:8443/mts_bce/services/MTSService',
                    'login' => "UsrWGaleas1",
                    'password' => "Espe2015",
                    'stream_context' => $context,
                    'trace' => TRUE,
                    'soap_version' => SOAP_1_2,
                    'local_cert' => 'C:\xampp\php\extras\openssl\pruebasde.pem');
                   // 'passphrase' => 'bce');

                $soapClient = new SoapClient('https://172.22.17.72:8443/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestSendSMS = new stdClass();
                $cash->dtoRequestSendSMS->language = "ES";
                $cash->dtoRequestSendSMS->mensaje = "CAIDA " . date("d.m.Y H:i:s");
                $cash->dtoRequestSendSMS->msisdnDestino = "0996231970";
                $cash->dtoRequestSendSMS->operadora = "74002";
                $cash->dtoRequestSendSMS->password = "Espe2015";
                $cash->dtoRequestSendSMS->user = "UsrWGaleas1";
                $cash->dtoRequestSendSMS->utfi = utfi();
                $res = $soapClient->sendSMS($cash);
                $resultCode= $res-> return->resultCode;
            }
            
            //////////////////////////////////////////////////////////////////
            //FUNCION DE COBRO
            function cobroPRE($monto, $cedula, $celular, $usuario, $contrasena){
                try{
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://172.22.17.72:8443/mts_bce/services/MTSService',
                    'login' => $usuario,
                    'password' => $contrasena,
                    'stream_context' => $context,
                    'trace' => TRUE,
                    'soap_version' => SOAP_1_2,
                    'local_cert' => 'C:\xampp\php\extras\openssl\pruebasde.pem');
                   // 'passphrase' => 'bce');

                $soapClient = new SoapClient('https://172.22.17.72:8443/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestCobroPre = new stdClass();
                $cash->dtoRequestCobroPre->amount = $monto;
                $cash->dtoRequestCobroPre->brandId = 1;
                $cash->dtoRequestCobroPre->currency = 1;
                $cash->dtoRequestCobroPre->document = $cedula;
                $cash->dtoRequestCobroPre->language = "ES";
                $cash->dtoRequestCobroPre->msisdnSource = $celular;
                $cash->dtoRequestCobroPre->msisdnTarget = $usuario;
                $cash->dtoRequestCobroPre->password = $contrasena;
                $cash->dtoRequestCobroPre->pin = $contrasena;
                $cash->dtoRequestCobroPre->user = $usuario;
                $cash->dtoRequestCobroPre->utfi = utfi();
                $res = $soapClient->cobroPre($cash);
                $resultText = $res->return->resultText;
                $resultCode = $res->return->resultCode;
                return $resultCode;
                }
                catch (Exception $e){
                    $resultCode=2;
                    return $resultCode;
                }
            }
            
            //FUNCION PAGO
            function pago($monto, $cedula, $celular){
                 
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://172.22.17.72:8443/mts_bce/services/MTSService',
                    'login' => "UsrWGaleas1",
                    'password' => "Espe2016",
                    'stream_context' => $context,
                    'trace' => TRUE,
                    'soap_version' => SOAP_1_2,
                    'local_cert' => 'C:\xampp\php\extras\openssl\pruebasde.pem');
                   // 'passphrase' => 'bce');

                $soapClient = new SoapClient('https://172.22.17.72:8443/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestRemitPre = new stdClass();
                $cash->dtoRequestRemitPre->amount = $monto;
                $cash->dtoRequestRemitPre->brandId = 1;
                $cash->dtoRequestRemitPre->currency = 1;
                $cash->dtoRequestRemitPre->document = $cedula;
                $cash->dtoRequestRemitPre->language = "ES";
                $cash->dtoRequestRemitPre->msisdnSource = "UsrWGaleas1";
                $cash->dtoRequestRemitPre->msisdnTarget = $celular;
                $cash->dtoRequestRemitPre->password = "Espe2016";
                $cash->dtoRequestRemitPre->pin = "Espe2016";
                $cash->dtoRequestRemitPre->user = "UsrWGaleas1";
                $cash->dtoRequestRemitPre->utfi = utfi();
                $res = $soapClient->remitPre($cash);
                //$resultText = $res->return->resultText;
                $resultCode= $res-> return->resultCode;
                return $resultCode;
            }
            //FUNCION PAGO
            function carga($monto, $cedula, $celular){
                 
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://172.22.17.72:8443/mts_bce/services/MTSService',
                    'login' => "UsrWGaleas1",
                    'password' => "Espe2016",
                    'stream_context' => $context,
                    'trace' => TRUE,
                    'soap_version' => SOAP_1_2,
                    'local_cert' => 'C:\xampp\php\extras\openssl\pruebasde.pem');
                   // 'passphrase' => 'bce');

                $soapClient = new SoapClient('https://172.22.17.72:8443/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestCashInPre = new stdClass();
                $cash->dtoRequestCashInPre->amount = $monto;
                $cash->dtoRequestCashInPre->brandId = 1;
                $cash->dtoRequestCashInPre->currency = 1;
                $cash->dtoRequestCashInPre->document = $cedula;
                $cash->dtoRequestCashInPre->language = "ES";
                $cash->dtoRequestCashInPre->msisdnSource = "UsrWGaleas1";
                $cash->dtoRequestCashInPre->msisdnTarget = $celular;
                $cash->dtoRequestCashInPre->password = "Espe2016";
                $cash->dtoRequestCashInPre->pin = "Espe2016";
                $cash->dtoRequestCashInPre->user = "UsrWGaleas1";
                $cash->dtoRequestCashInPre->utfi = utfi();
                $res = $soapClient->cashInPre($cash);
                //$resultText = $res->return->resultText;
                $resultCode= $res-> return->resultCode;
                return $resultCode;
            }
            
            function utfi(){
                $utfi=date('Y-m-d').rand(5,100);
               return $utfi; 
            }
            ?>
              
    </body>
</html>