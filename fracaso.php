<!--
The MIT License (MIT)

Copyright (c) 2013 Marco Antonio Orellana Olivares - morello.cl@outlook.com

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
-->
<!DOCTYPE html>
<html lang="en">
        <head>
                <meta name="author" content="Marco Orellana <morello.cl@gmail.com>">
                <meta name="description" content="Cliente prueba PHP consumo API PuntoPagos.com">

                <title>Fracaso</title>
                
        </head>
        <body>
                <?php
                        $funcion = "transaccion/traer";

                        $token = $_GET['token'];

                        $monto = $_COOKIE['1'.$token.'1'];
                        $trx_id = $_COOKIE['1'.$token.'2']; 

                        $config = parse_ini_file('puntopagos.ini', 1);
                        $PUNTOPAGOS_URL = $config['puntopagos']['url'];
                        $PUNTOPAGOS_KEY = $config['puntopagos']['key'];
                        $PUNTOPAGOS_SECRET = $config['puntopagos']['secret'];
                        $nombre_comercio = $config['puntopagos']['nombre'];
                        $home = $config['puntopagos']['home'];
                        
                        $http_request = "<br/><br/>";

                        $fecha = gmdate("D, d M Y H:i:s", time())." GMT";
                        $mensaje = $funcion."\n".$token."\n".$trx_id."\n".$monto."\n".$fecha;
                        $signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
                        $firma = "PP ".$PUNTOPAGOS_KEY.":".$signature;

                        $header = array();
                        $http_request .= $header[] = "Accept: application/json;";
                        $http_request .= $header[] = "Accept-Charset: utf-8;";
                        $http_request .= $header[] = "Accept-Language: en-us,en;q=0.5";
                        $http_request .= $header[] = "Content-type: application/json";
                        $http_request .= $header[] = "Fecha: ".$fecha;
                        $http_request .= $header[] = "Autorizacion: ".$firma;

                        $url_pp = $PUNTOPAGOS_URL."/transaccion/".$token;

                        $curl = curl_init($url_pp);
                        curl_setopt($curl, CURL_VERSION_SSL,"SSL_VERSION_SSLv3"); //optativo
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($curl, CURL_HTTP_VERSION_1_1, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                        $http_response = curl_exec ($curl);

                        $err = curl_errno($curl);
                        $errmsg = curl_error($curl);

                        $response = json_decode($http_response, true);
                        curl_close($curl);

                        if($response['token'] != ""){
                        }else{
                                echo "<h2>Ha ocurrido un error.</h2> <h3>Error (".$err."): ".$errmsg."</h3><br/>Error PP:".$response['error'];
                        }
                ?>
                <table>
                        <tr>
                                <td>Comercio</td>
                                <td><?php echo $nombre_comercio; ?></td>
                        </tr>
                        <tr>
                                <td>Orden de Compra</td>
                                <td><?php echo $trx_id; ?></td>
                        </tr>
                        <tr>
                                <td>Monto</td>
                                <td><?php echo $monto; ?></td>
                        </tr>
                        <?php if ($response['respuesta'] != '1'): ?>
                        <tr>
                                <td>Rechazo</td>
                                <td><?php echo $response['error']; ?></td>
                        </tr>
                        <?php else: ?>
                        <tr>
                                <td>Posibles Causas de Rechazo</td>
                                <td>
                                        Error en el ingreso de los datos de su tarjeta de cr&eacute;dito o Debito (fecha y/o c&oacute;digo de seguridad).<br />
                                        Su tarjeta de cr&eacute;dito o debito no cuenta con el cupo necesario para cancelar la compra.<br />
                                        Tarjeta a&uacute;n no habilitada en el sistema financiero.<br />
                                        Si el problema persiste favor comunicarse con su Banco emisor.<br />
                                </td>
                        </tr>
                        <?php endif;?>
                </table>
                <?php echo '<form action="'.$home.'">'; ?>
                        <input type="submit" method='post' value='Volver' />
                </form>
        </body>
</html>