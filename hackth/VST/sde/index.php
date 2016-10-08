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
                                <input type="submit" id="cobrar" name="cobrar" value="Cobrar" />
                            </fieldset>
                        </form>
            <?php
            //FUNCIONES
            if (isset($_POST['cobrar'])) {
               echo "<script>alert('" . cobro($_POST['monto'],$_POST['cedula'],$_POST['celular']) . "');</script>";
            }
           
            
            
            //////////////////////////////////////////////////////////////////
            //FUNCION DE COBRO
            function cobro($monto, $cedula, $celular){
                
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://test.dineroelectronico.ec/mts_bce/services/MTSService',
                    'login' => "UsrWGaleas1",
                    'password' => "Espe2015",
                    'stream_context' => $context,
                    'trace' => TRUE,//);//,
                    'soap_version' => SOAP_1_2);
                   // 'local_cert' => 'C:\xampp\php\extras\openssl\pruebasde.pem');
                    //'passphrase' => 'bce');

                $soapClient = new SoapClient('https://test.dineroelectronico.ec/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestCobroConfirm = new stdClass();
                $cash->dtoRequestCobroConfirm->amount = $monto;
                $cash->dtoRequestCobroConfirm->brandId = 1;
                $cash->dtoRequestCobroConfirm->currency = 1;
                $cash->dtoRequestCobroConfirm->document = $cedula;
                $cash->dtoRequestCobroConfirm->language = "ES";
                $cash->dtoRequestCobroConfirm->msisdnSource = $celular;
                $cash->dtoRequestCobroConfirm->msisdnTarget = "UsrWGaleas1";
                $cash->dtoRequestCobroConfirm->password = "Espe2015";
                $cash->dtoRequestCobroConfirm->pin = "Espe2015";
                $cash->dtoRequestCobroConfirm->user = "UsrWGaleas1";
                $cash->dtoRequestCobroConfirm->utfi = utfi();
                $res = $soapClient->cobroConfirm($cash);
                $resultText = $res->return->resultText;
                $resultCode= $res-> return->resultCode;
                //var_dump($resultCode);
                echo "\t" . $resultCode;
				echo "\t" . $resultText;
                //echo "<script>alert('" . $resultCode . "');</script>";
                return $resultText;
               
            }
            
            function utfi(){
                $utfi=date('Y-m-d').rand(5,100);
               return $utfi; 
            }
            ?>
              
    </body>
</html>