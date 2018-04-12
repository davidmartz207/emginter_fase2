<?php
class MY_Router extends CI_Router {
    function __construct(){
        parent::__construct();
    }

    // esta funcion le hace un hook al procesamiento 
    // de las rutas del core (_validate_request)
    function _validate_request($segments){
        //echo $segments[0];exit;
        //echo '<pre>',print_r($segments),'</pre>';exit;

        // asignamos los parametros como vienen
        //$param0
        if (isset($segments[0])){
            $param1 = $segments[0];
        }
        if (isset($segments[1])){
            $param2 = $segments[1];
        }
        if (isset($segments[2])){
            $param3 = $segments[2];
        }
        if (isset($segments[3])){
            $param4 = $segments[3];
        }
        if (isset($segments[4])){
            $param5 = $segments[4];
        }

        # about-us / nosotros
        if($param1 == 'about-us' or $param1 == 'nosotros'){
            $param1   = 'about_us';
            $segments = array($param1);         
        }
        
        # content
        if(($param1 == 'content' or $param1 == 'contenido') and isset($param2)){
            $param1   = 'content';
            $segments = array($param1, 'index', $param2);
        }
        
        # products por defecto
        # param1 sera un producto por defecto
        if (isset($param1) and !file_exists(APPPATH.'controllers/'.$param1.EXT)){
            $segments = array('products', 'index', $param1);
        }
            
        # indicamos que continue el procesamiento de las rutas con las actualizaciones..
        return parent::_validate_request($segments);
    }
}
?>