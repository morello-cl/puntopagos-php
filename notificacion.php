<?php
        /*
        @desc "Ejemplo procedural para ilustrar una correcta creaciÃ³e transaccion en ambiente sandbox de Punto Pagos"
        @autor Marco Orellana Olivares <morello.cl@gmail.com>
        @inputs $_POST['pp_trx_id'],$_REQUEST['pp_monto'], $_REQUEST['pp_medio_pago'], $_REQUEST['pp_detalle'] (opcional). Estos podrian estar tambien en sesion.
        */
        $config = parse_ini_file('puntopagos.ini', 1);
        $PUNTOPAGOS_URL = $config['puntopagos']['url'];
        $PUNTOPAGOS_KEY = $config['puntopagos']['key'];
        $PUNTOPAGOS_SECRET = $config['puntopagos']['secret'];
        $nombre_comercio = $config['puntopagos']['nombre'];
        $home = $config['puntopagos']['home'];

        $method = $_SERVER['REQUEST_METHOD'];  

        if($method == 'GET')
        {
                $token = $_GET['token'];
                echo $token.'<br />';
        }
        else
        {
                $headers = getallheaders();
                //echo $headers['Autorizacion'];
                //echo $headers['Fecha'];

                $value = json_decode(file_get_contents('php://input'));
                $token = $value->{"token"};
                $trx_id = $value->{"trx_id"};
                $medio_pago = $value->{"medio_pago"};                
                $monto  = $value->{"monto"};
                $fecha_aprobacion = $value->{"fecha_aprobacion"};
                $numero_tarjeta = $value->{"numero_tarjeta"};
                $num_cuotas = $value->{"num_cuotas"};
                $tipo_cuotas = $value->{"tipo_cuotas"};
                $valor_cuota = $value->{"valor_cuota"};
                $primer_vencimiento = $value->{"primer_vencimiento"};
                $numero_operacion = $value->{"numero_operacion"};
                $codigo_autorizacion = $value->{"codigo_autorizacion"};

                //(moo) validar firma
                $fecha = $headers['Fecha'];
                $mensaje = "transaccion/notificacion\n".$token."\n".$trx_id."\n".$monto."\n".$fecha;
                $signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
                $firma = "PP ".$PUNTOPAGOS_KEY.":".$signature;

                if($firma == $headers['Autorizacion']){
                        $data = array('token' => $token, 'response' => '00');
                } else {
                        $data = array('token' => $token, 'response' => '99', 'error' => 'Error de autentificacion');
                }

                header ('Content-type: application/json');                
                echo json_encode($data);        
        }
?>