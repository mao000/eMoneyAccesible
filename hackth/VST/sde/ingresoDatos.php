<!doctype html>
<html>
    <head>
        <meta charset="utf8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>jQuery Dropdown Login Freebie | The Finished Box</title>
        <link rel="stylesheet" href="css/style.css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js?ver=1.4.2"></script>       
       
    </head>
    <body>       
            <div id="container">
                <!-- Login Starts Here -->
                <div id="loginContainer">                                        
                   
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
                                <label>REALIZAR PAGO:</label>
                                <input type="submit" id="cobrar" name="cobrar" value="Pagar" />
                            </fieldset>
                            <!-- <span><a href="#">Perdiste tu contrase&ntilde;a?</a></span>-->
                        </form>
                    
                </div>
                <!-- Login Ends Here -->

            </div>
            
            <?php
            $utfi = time();
            if (isset($_POST['cobrar'])) {
                $cadena_buscada = "[resultCode]=>1";
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $soapParams = array(
                    'location' => 'https://181.211.102.40:8443/mts_bce/services/MTSService',
                    'login' => "UsrSecap1",
                    'password' => "36813e67eb",
                    'stream_context' => $context,
                    'trace' => TRUE,
                    'soap_version' => SOAP_1_2,
                    'local_cert' => 'C:\xampp\php\extras\ssl\s_priv_pub.pem');
                   // 'passphrase' => 'bce');

                $soapClient = new SoapClient('https://181.211.102.40:8443/mts_bce/services/MTSService?wsdl', $soapParams);
                $cash = new stdClass();
                $cash->dtoRequestCobroConfirm = new stdClass();
                $cash->dtoRequestCobroConfirm->amount = $_POST['monto'];
                $cash->dtoRequestCobroConfirm->brandId = 1;
                $cash->dtoRequestCobroConfirm->currency = 1;
                $cash->dtoRequestCobroConfirm->document = $_POST['cedula'];
                $cash->dtoRequestCobroConfirm->language = "ES";
                $cash->dtoRequestCobroConfirm->msisdnSource = $_POST['celular'];
                $cash->dtoRequestCobroConfirm->msisdnTarget = "UsrSecap1";
                $cash->dtoRequestCobroConfirm->password = "36813e67eb";
                $cash->dtoRequestCobroConfirm->pin = "36813e67eb";
                $cash->dtoRequestCobroConfirm->user = "UsrSecap1";
                $cash->dtoRequestCobroConfirm->utfi = $utfi;
                $res = $soapClient->cobroConfirm($cash);
                $resultCode = $res->return->resultText;
                //var_dump($resultCode);

                echo "\t" . $resultCode;

                echo "<script>alert('" . $resultCode . "');</script>";
            }
            ?>
              
    </body>
</html>