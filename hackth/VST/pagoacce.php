<!DOCTYPE html>
<html lang="es"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<base href="http://localhost/hackth/"><!--<base href=".">-->

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="keywords" content="efectivo, efectivo desde tu celular, efectivo ec, efectivo Ecuador, dinero electrónico, discapacidad, accesibilidad, web, estandares web, accessibility, W3C, WCAG">
<meta name="description" content="Todo tipo de información sobre efectivo desde tu celular en la Web: dinero electrónico, qué es efectivo ec, cómofunciona el efectivo desde tu celular, como realizar transacciones desde tu celular, etc.">
<meta name="author" content="Hack153">
<link rel="shortcut icon" href="https://hwid6.vmall.com/oauth2/up/common/images/default/favicon.ico" type="image/x-icon">
<title>efectivo ec | pagos en línea</title>
<!--<link rel="stylesheet" type="text/css" media="all" href="VST/CSS/estilo02.css" title="Normal">-->
<!--<link href="VST/CSS/style01.css" rel="stylesheet" type="text/css">-->
<link href="VST/CSS/home.css" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" href="VST/sde/css/style.css" />-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js?ver=1.4.2"></script>
<script src="VST/sde/js/login.js"></script>
<body>
<!-- Saltar -->
<div id="enlaces">
	<ul>
		<li><a href="http://localhost/hackth/?menu=accesibilidad-formularios-controles#menu">Saltar al menú principal</a>
		</li>
	</ul>
</div>
<!-- Fin Saltar -->
<!-- Cabecera -->
<header>
	<div id="cabecera">
		<div id="logo1">
		<a href="https://efectivo.ec/" title="Efectivo EC"><img src="VST/IMG/logo-efectivo.png" alt="Efectivo desde tu celular"></a>
		</div>
		<h1 class="screen-reader-text"><a href="http://localhost/hackth/">Efectivo EC</a></h1>
	</div>
</header>
<!-- Fin Cabecera -->
<main>
<!-- Migas de pan -->
<h2 class="screen-reader-text">Migas de pan</h2>
<div id="migas">
	Estás en: <a href="http://localhost/hackth/">Inicio</a> &gt; <a href="http://localhost/hackth/?menu=ejemplos">Pagos electrónicos y confirmación</a>
</div>
<!-- Fin Migas de pan -->
<!-- Contenido -->
<br><br>
<div id="contenido">
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
              
</div>
</main>
 </body>

</html>