<?php
        /*
        The MIT License (MIT)

        Copyright (c) 2013 Marco Antonio Orellana Olivares <morello.cl@outlook.com>

        Permission is hereby granted, free of charge, to any person obtaining a copy of
        this software and associated documentation files (the "Software"), to deal in
        the Software without restriction, including without limitation the rights to
        use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
        the Software, and to permit persons to whom the Software is furnished to do so,
        subject to the following conditions:

        The above copyright notice and this permission notice shall be included in all
        copies or substantial portions of the Software.

        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
        IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
        FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
        COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
        IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
        CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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