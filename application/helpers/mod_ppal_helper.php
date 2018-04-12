<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('formatear_celda'))
{
	function formatear_celda($cadena){
            if(empty($cadena)) return FALSE;

            $cadena = (string)$cadena;
            //mb_detect_encoding($str);
            //$cadena = utf8_decode($cadena);
            //echo mb_detect_encoding($cadena);
            //$cadena = htmlentities($cadena,0, 'UTF-8');
            return $cadena;
        }
} 

if ( ! function_exists('is_valid_number_phone'))
{
	function is_valid_number_phone($cadena){
            if(empty($cadena)) return FALSE;
            $arrPermitidos = array('0','1','2','3','4','5','6','7','8','9','-','(',')','+',' ');
            $correcto      = TRUE;
            $cadena        = trim($cadena);
            $total_chars   = strlen($cadena);
            for($i=0;$i<=$total_chars;$i++){
                if(isset($cadena[$i]) and !in_array($cadena[$i],$arrPermitidos)){
                    $correcto = FALSE;
                    break;
                }
            }
            return $correcto;
        }
} 

if ( ! function_exists('set_label'))
{
	function set_label($cadena,$only_text=FALSE,$font_size=20){
            if($only_text){
                return '<div class="etiqueta">
                            <div class="etiqueta-texto-'.$font_size.'">'
                                .html_escape($cadena).
                            '</div>
                       </div>';
            }else{
                return '<div class="etiqueta">
                            <div class="etiqueta-texto-'.$font_size.'">'
                                .lang($cadena)
                            .'</div>
                       </div>';
            }
        }
}            
            
if ( ! function_exists('encode_link'))
{
	function encode_link($cadena,$id=''){
		# sustituimos lso caracteres que permitiremos pero en vocales ----------------------
		$title = strtolower($cadena);
        // sustituye & por and
        $title = str_replace('&', 'and', $title);
		// espacios por guiones
        $title = str_replace(' ', '-', $title);
		// sustituye los apostrofes por guiones
        $title = str_replace("'", '-', $title);
		// puntos por guiones
        $title = str_replace('.', '-', $title);
		// under por guiones
        $title = str_replace('_', '-', $title);
		// ñ por n
        $title = str_replace('ñ', 'n', $title);
		// +
        $title = str_replace('+', '-', $title);
        
        //Δ
        $title = str_replace('Δ', 'a', $title);
        
	// acentos
        $title = str_replace(array('á','é','í','ó','ú'),array('a','e','i','o','u'), $title);
		// acentos
        $title = str_replace(array('Á','É','Í','Ó','Ú'),array('a','e','i','o','u'), $title);
		// 
        $title = str_replace(array('â','ê','î','ô','û'),array('a','e','i','o','u'), $title);
		//
        $title = str_replace(array('Â','Ê','Î','Ô','Û'),array('a','e','i','o','u'), $title);
		// diaresis
        $title = str_replace(array('ä','ë','ï','ö','ü'),array('a','e','i','o','u'), $title);
		// diaresis
        $title = str_replace(array('Ä','Ë','Ï','Ö','Ü'),array('a','e','i','o','u'), $title);

		#filtramos el resto por los permitidos ---------------------------------------------
		$permitted_uri_chars = 'abcdefghijklmnopqrstuvwxyz0123456789-';
		$total_title         = strlen($title);
		$new_cadena          = '';
		for ($s=0; $s<$total_title; $s++){
			if(!stristr($permitted_uri_chars, $title[$s])===false){
				$new_cadena .= $title[$s];
					
			}
		}
		$title = $new_cadena;
		
		# elimina los doble guiones --------------------------------------------------------
        $flag = 1;
        while($flag){
            $newtitle = str_replace('--','-',$title);
            if($title != $newtitle){
                $flag = 1;
            }else{
                $flag = 0;
            }
            $title = $newtitle;
        }

		# en caso de que la cadena resultante sea un simple guion, la camuflamos -----------
		if($title=='-'){
			$title = 'dash';
			
		# en caso contrario.. eliminamos los guiones del final
		}else{
			$title = trim($title,'-');
		}
		
		# limitamos el numero de caracteres ------------------------------------------------
        $title = trim(substr($title,0,100));

		# retornamos dependiendo de la solicitud ------------------------------------------
        return !empty($id) ? urlencode($title.'-'.$id) : urlencode($title);
    }
}

if ( ! function_exists('generar_url_product')){
    // genera una url valida para el artista
    function generar_url_product($nombre){
       # los trozos de url son del tipo: 
       # nombre-cancion
       # nombre-cancion-2 (2 es el contador final para trozos repetidos)
       
       // convierte el nombre de la cancion en una parte de la url
       $cadena = clear_string_url($nombre);
       //codifica la cadena a caracteres permitidos en url
       $cadena = encode_link($cadena);
       
       $CI =& get_instance();
       $CI->load->database('default');

       // busca la url que coincidan con la cadena temporal
       // la busqueda debe ser exacta: url_post=cadena
       $query=$CI->db->query("SELECT url_post   
                              FROM productos 
                              WHERE url_post=?",array($cadena));
       //exit($this->db->last_query()); 
       
       // verificamos si existe el trozo de url exacta
       if ($query->num_rows()>0){
           // como sabemos q existe, ahora buscaremos los trozo que 
           // empiecen con la cadena temporal
           $query2 = $CI->db->query("SELECT url_post   
                              FROM productos 
                              WHERE url_post LIKE ?",array(($cadena.'%')));
           // por lo menos un resultado ha de devolver
           if ($query2->num_rows()>0){
                // convertimos el trozo de la url temporal en un array
                $arrURLTemporal = explode('-',$cadena);
                //echo '<pre>',print_r($arrURLTemporal),'</pre>';
                // si hay mas de un elemento, es que tiene al menos un guion
                $total_temporal = count($arrURLTemporal);

                //
                $total_url_similares = 0; 
                $total_url_similares = $query2->num_rows();

                // si la url tiene algun guion o si hay mas de una 
                // ocurrencia similar, procesamos el trozo de url
                if($total_temporal>1 or $total_url_similares){
                    // sera el index para identificar donde va el contador final
                    $total_temporal = $total_temporal + 1;

                    // aqui almacenaremos el ultimo index numerico encontrado 
                    // por defecto es 1 ya que si la cadena tiene guiones pero es unica, 
                    // debera agregarle l contador al final y no puede ser cero
                    $ultimo     = 1;

                    // ultimo elemento del array $arrURLExistentes
                    $last_index = 0 ;

                    // verificamos en cada trozo devuelto, el ultimo valor 
                    foreach($query2->result() as $row){
                         // convertimos el trozo de url temporal en un array
                         $arrURLExistentes = explode('-',$row->url_post);
                         $total_existentes = count($arrURLExistentes);

                         // los indices de ambos array deben coincidir
                         if($total_existentes == $total_temporal){
                             // ultimo elemento del array
                             $last_index = $arrURLExistentes[$total_existentes-1];
                             //
                             if(ctype_digit($last_index)){
                                 $ultimo = $last_index;
                             }
                         }
                     }#end foreach
                     if(empty($ultimo)){
                         return $cadena;
                     }else{
                         return $cadena.'-'.($ultimo+1);
                     }
                }else{
                     // si llega aqui es porque la url no tiene guiones o 
                     // no hay ocurrencias similares
                     // asi que le agregamos un -2 para continuar la secuencia
                     return $cadena.'-2';
                 }
           }#query 2
        // si no existe el trozo de url, 
        }else{
            // devuelve la cadena limpia para usarla 
            // como identificador url del post
            return $cadena;
        }
   }
}

if ( ! function_exists('generar_url_contenido')){
    // genera una url valida para el artista
    function generar_url_contenido($nombre){
       # los trozos de url son del tipo: 
       # nombre-cancion
       # nombre-cancion-2 (2 es el contador final para trozos repetidos)
       
       // convierte el nombre de la cancion en una parte de la url
       $cadena = clear_string_url($nombre);
       //codifica la cadena a caracteres permitidos en url
       $cadena = encode_link($cadena);
       
       $CI =& get_instance();
       $CI->load->database('default');

       // busca la url que coincidan con la cadena temporal
       // la busqueda debe ser exacta: url_post=cadena
       $query=$CI->db->query("SELECT url_post   
                              FROM contenidos 
                              WHERE url_post=?",array($cadena));
       //exit($this->db->last_query()); 
       
       // verificamos si existe el trozo de url exacta
       if ($query->num_rows()>0){
           // como sabemos q existe, ahora buscaremos los trozo que 
           // empiecen con la cadena temporal
           $query2 = $CI->db->query("SELECT url_post   
                              FROM contenidos 
                              WHERE url_post LIKE ?",array(($cadena.'%')));
           // por lo menos un resultado ha de devolver
           if ($query2->num_rows()>0){
                // convertimos el trozo de la url temporal en un array
                $arrURLTemporal = explode('-',$cadena);
                //echo '<pre>',print_r($arrURLTemporal),'</pre>';
                // si hay mas de un elemento, es que tiene al menos un guion
                $total_temporal = count($arrURLTemporal);

                //
                $total_url_similares = 0; 
                $total_url_similares = $query2->num_rows();

                // si la url tiene algun guion o si hay mas de una 
                // ocurrencia similar, procesamos el trozo de url
                if($total_temporal>1 or $total_url_similares){
                    // sera el index para identificar donde va el contador final
                    $total_temporal = $total_temporal + 1;

                    // aqui almacenaremos el ultimo index numerico encontrado 
                    // por defecto es 1 ya que si la cadena tiene guiones pero es unica, 
                    // debera agregarle l contador al final y no puede ser cero
                    $ultimo     = 1;

                    // ultimo elemento del array $arrURLExistentes
                    $last_index = 0 ;

                    // verificamos en cada trozo devuelto, el ultimo valor 
                    foreach($query2->result() as $row){
                         // convertimos el trozo de url temporal en un array
                         $arrURLExistentes = explode('-',$row->url_post);
                         $total_existentes = count($arrURLExistentes);

                         // los indices de ambos array deben coincidir
                         if($total_existentes == $total_temporal){
                             // ultimo elemento del array
                             $last_index = $arrURLExistentes[$total_existentes-1];
                             //
                             if(ctype_digit($last_index)){
                                 $ultimo = $last_index;
                             }
                         }
                     }#end foreach
                     if(empty($ultimo)){
                         return $cadena;
                     }else{
                         return $cadena.'-'.($ultimo+1);
                     }
                }else{
                     // si llega aqui es porque la url no tiene guiones o 
                     // no hay ocurrencias similares
                     // asi que le agregamos un -2 para continuar la secuencia
                     return $cadena.'-2';
                 }
           }#query 2
        // si no existe el trozo de url, 
        }else{
            // devuelve la cadena limpia para usarla 
            // como identificador url del post
            return $cadena;
        }
   }
}

// permite codificar la imagen a url valida para CI
if ( ! function_exists('replace_special_characters'))
{
    function replace_special_characters($cadena){        
        $string = htmlentities($cadena);
        $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
        return $string;
    }
}

// permite generar parte de la url
// la limpia y la codifica
if ( ! function_exists('generar_url'))
{
    function generar_url($cadena,$id=''){
        $cadena = clear_string_url($cadena);
        return encode_link($cadena,$id);
    }
}

// limpia la string para dejarla valida como parte de la url
if ( ! function_exists('clear_string_url'))
{
    function clear_string_url($cadena){
        $arrCadena = explode(' ',$cadena);
        $strNuevo  = '';
        foreach($arrCadena AS $i => $item){
            if(!empty($item)){
                $strNuevo .= $item.'-';
            }
        }
        $strNuevo = strtolower($strNuevo);
        return trim($strNuevo,'-');
    }
}

// decodifica los caracteres de la url
if ( ! function_exists('decode_link'))
{
    function decode_link($cadena){
        return (int)urldecode($cadena);
    }
}

if ( ! function_exists('str_is_url'))
{
    function str_is_url($url=''){
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}


# permite encriptar una cadena en sha1+md5
if ( ! function_exists('encriptar_cadena'))
{
   function encriptar_cadena($sCadena){

	if (empty($sCadena)){
            return false;
        }else{
            return sha1(md5('#cssnk#'.trim($sCadena).'*emg*'));
        }
   }
}

if ( ! function_exists('get_controller'))
{
   function get_controller(){
        $CI =& get_instance();
	return $CI->router->class;
   }
}
#*******************************************************************************
# ****** FUNCIONES GENERALES ACCESO DB *****************************************
# ******************************************************************************

if ( ! function_exists('get_catalog_products'))
{
 function get_catalog_products(){
        $lang = get_lang();
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('linea,id_catalogo,nombre_en,nombre_es,path_img,ruta_interna,ruta_externa')
                            ->from('catalogos_productos')
                            ->where('estatus','1');

        $CI->db->order_by('fecha_registro','DESC')->limit(4);
        $query =$CI->db->get();
        //echo$CI->db->last_query();exit;
        if ($query->num_rows()>0){
            $arrResult = array();
            foreach ($query->result() as $row){
                # gestion del lenguaje
                if($lang=='en'){
                    $name = $row->nombre_en;
                }elseif($lang=='es'){
                    $name = $row->nombre_es;
                }

                $link   = $row->ruta_externa;
                $target = '_blank';
                if(empty($link)){
                    $link = base_url().'downloads/catalog/'.$row->id_catalogo.'/'.$row->ruta_interna;
                    //$target = '_self';
                    $target = '_blank';
                }

                #img
                if($row->path_img){
                    $path_img = 'media/catalogos/'.$row->id_catalogo.'/'.htmlentities($row->path_img);
                    if(!file_exists('./'.$path_img)){
                        $path_img = 'media/default/producto.png';
                    }
                }else{
                    $path_img = 'media/default/producto.png';
                }


                $arrResult[] = array(
                    'linea'     => $row->linea,
                    'id'       => (int)$row->id_catalogo,
                    'name'     => $name,
                    'name_pdf' => replace_special_characters($name).'.pdf',
                    'path_img' => $path_img,
                    'link'     => $link,
                    'target'   => $target
                );
            }
            return $arrResult;
        }else{
            return FALSE;
        }
    }
}

if ( ! function_exists('get_product_type_by_id_product'))
{
   function get_product_type_by_id_product($id_product){
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('id_tipo_producto')->from('productos')
               ->where('id_producto',$id_product);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return $row['id_tipo_producto'];
        }else{
            return FALSE;
        }
  }
}

if ( ! function_exists('get_applications_by_product'))
{ 
    function get_applications_by_product($arrParam_app,$id_producto=0,$limit=0){
        $lang           = get_lang();
        $arrWhereFilter = array();
        $CI =& get_instance();
        $CI->load->database('default');
        $SQL = 'SELECT a.id_producto,a.years,a.ref_id_modelo,m.nombre AS marca,
                       tm.nombre_en AS tipo_motor 
                FROM aplicaciones_productos AS a 
                JOIN tipos_motores AS tm ON tm.id_tipo_motor=a.id_tipo_motor 
                JOIN marcas AS m ON m.id_marca=a.id_marca
                JOIN productos ON a.id_producto=productos.id_producto
                ';

        if($id_producto){
            $SQL .= ' WHERE a.id_producto=?';
            # se arman los filtros del where
            $arrWhereFilter[] = $id_producto;
        }

        # -- filter applications
        if(is_array($arrParam_app)){
            if(isset($arrParam_app['linea'])){
                $SQL .= ' AND productos.linea=?';
                $arrWhereFilter[] = $arrParam_app['linea'];
            }
            if(isset($arrParam_app['tipo_producto'])){
                $SQL .= ' AND productos.id_tipo_producto=?';
                $arrWhereFilter[] = $arrParam_app['tipo_producto'];
            }
            if(isset($arrParam_app['tipo_motor'])){
                $SQL .= ' AND a.id_tipo_motor=?';
                $arrWhereFilter[] = $arrParam_app['tipo_motor'];
            }
            if(isset($arrParam_app['marca'])){
                $SQL .= ' AND a.id_marca=?';
                $arrWhereFilter[] = $arrParam_app['marca'];
            }
            if(isset($arrParam_app['modelo'])){
                $SQL .= ' AND a.ref_id_modelo=?';
                $arrWhereFilter[] = $arrParam_app['modelo'];
            }            
            if(isset($arrParam_app['years'])){
                # verificamos si hay comas, 
                # si no hay es un like simple
                if(strpos($arrParam_app['years'],',')===FALSE){
                    $SQL .= ' AND a.years LIKE ?';
                    $arrWhereFilter[] = '%'.$arrParam_app['years'].'%';

                # si hay comas hay que armar varios like
                }else{
                    # limpiamos los like
                    $arrParam_app['years'] = trim($arrParam_app['years']);
                    $arrParam_app['years'] = trim($arrParam_app['years'], ',');
                    # armamos un array y lo recorremos
                    $arrYears  = explode(',',$arrParam_app['years']);
                    if(is_array($arrYears)){
                        foreach($arrYears as $i => $item){
                            $item = trim($item);
                            if($i==0){
                                $SQL .= ' AND (a.years LIKE ?';
                                $arrWhereFilter[] = '%'.$item.'%';
                            }else{
                                $SQL .= ' OR a.years LIKE ?';
                                $arrWhereFilter[] = '%'.$item.'%';
                            }                        
                        }
                    }
                    $SQL .= ') ';
                }
            }
        }
        $SQL  .= ' ORDER bY marca ASC';
        $limit = (int)$limit;
        if(!empty($limit)){
                 $SQL .= ' LIMIT '.$limit;
        }
        $query = $CI->db->query($SQL,$arrWhereFilter);
        if ($query->num_rows()>0){
            $arrDatos = array();
            foreach ($query->result() as $row){
                if(!empty($row->years)){
                    $str_years = trim($row->years,',');
                    $arrYears  = explode(',',$str_years);
                    if(is_array($arrYears)){
                        $years = formatear_anhos($arrYears);    
                    }else{
                        $years = $str_years;
                    }
                }else{
                    $years = '';
                }
                $arrDatos[] = array(
                    'id_producto'       => (int)$row->id_producto,
                    'id_tipo_producto'  => get_product_type_by_id_product($row->id_producto),
                    'marca_modelo'      => html_escape($row->marca).($row->ref_id_modelo ? ' / '.get_modelo($row->ref_id_modelo) : ''),
                    'tipo_motor'        => html_escape($row->tipo_motor),
                    'years'             => $years,
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
  }
}

if ( ! function_exists('formatear_anhos'))
{   
    function formatear_anhos($arrYears){
        #2001, 2002, 2003, 2004, 2007, 200, 2009, 2020,2023
        $arrNuevo = array();
        $contador = 0;
        foreach($arrYears as $i => $anho){
            $anho     = (int)$anho;
            if($i==0){
                $arrNuevo[$contador][] = $anho;
                $anterior = $anho;
                continue;
            }elseif(($anterior+1)==$anho){
                $arrNuevo[$contador][] = $anho;
            }else{
                $contador++;
                $arrNuevo[$contador][] = $anho;
            }
            $anterior = $anho;
        }

        $sResult = '';
        foreach($arrNuevo as $i => $item){
            if(isset($item[0])){
                $primero = $item[0];
                if(count($item) == 1){
                    $sResult .= ', '.$primero;
                }else{
                    $ultimo  = end($item);
                    $sResult .= ', '.$primero.'-'.$ultimo;
                }
            }
        }
        $sResult = trim($sResult,',');
        $sResult = trim($sResult);
        return $sResult;
    }
}

if ( ! function_exists('get_idiomas'))
{   
    function get_idiomas(){
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT id_idioma,nombre 
                                 FROM idiomas 
                                 WHERE estatus=1 
                                 ORDER BY nombre ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    'id'     => (int)$row->id_idioma,
                    'nombre' => htmlspecialchars($row->nombre,ENT_QUOTES)
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
    }
}

if ( ! function_exists('get_config_db'))
{
   function get_config_db($campo,$idioma=0,$aplicar_entity_decode=TRUE){
        if(empty($campo)){
            return FALSE;
        }
        if($idioma==0){
            $idioma = get_id_idioma();
        }else{
            $idioma = (int)$idioma;
        }
            
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select($campo.' AS campo')->from('configuraciones')
               ->where('id_idioma',$idioma);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            
            $arrFieldToEntityDecode = array(
                'direccion',
                'horarios_jornada_laboral',
                'txtaMsjRegistro',
                'txtaMsjRegistroAprobacion',
                'texto_contacto',
                'texto_guia_productos',
                'texto_productos'
            );
            if(in_array($campo,$arrFieldToEntityDecode) and $aplicar_entity_decode){
                return html_entity_decode($row['campo']);
            }else{
                return nl2br($row['campo']);
            }
        }else{
            return FALSE;
        }
  }
}

if ( ! function_exists('get_marcas'))
{   
    function get_marcas(){
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT id_marca,nombre 
                                 FROM marcas  
                                 WHERE estatus=1 
                                 ORDER BY nombre ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    'id'     => (int)$row->id_marca,
                    'nombre' => htmlspecialchars($row->nombre,ENT_QUOTES)
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
    }
}

if ( ! function_exists('get_modelo'))
{
   function get_modelo($id_modelo){
        if(empty($id_modelo)){
            return FALSE;
        }
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('nombre')
                 ->from('modelos')
                 ->where('id_modelo',$id_modelo);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return $row['nombre'];
        }else{
            return FALSE;
        }
  }
}

if ( ! function_exists('get_json_tipos_productos'))
{
   function get_json_tipos_productos($linea){
        $lang = get_lang();
        if($lang=='en'){
            $name = 'nombre_en';
        }elseif($lang=='es'){
            $name = 'nombre_es';
        }
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT  A.id_tipo_producto AS id,A.nombre_en,A.nombre_es
                                   FROM  tipos_productos A
                                   INNER JOIN  productos B on B.id_tipo_producto = A.id_tipo_producto                                    
                                   AND   A.estatus=1
                                   AND   B.linea = $linea
                                   GROUP BY A.id_tipo_producto
                                   ORDER BY $name ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                if($lang=='en'){
                    $nombre = $row->nombre_en;
                }elseif($lang=='es'){
                    $nombre = $row->nombre_es;
                }
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($nombre,ENT_QUOTES)
                );
            }       
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array(lang('search_not_found'))));
        }
    }
}

if ( ! function_exists('get_json_tipos_motores'))
{   
    function get_json_tipos_motores(){
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT id_tipo_motor AS id,nombre_en,nombre_es
                                   FROM tipos_motores 
                                   WHERE estatus=1
                                   ORDER BY nombre_en ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $nombre = $row->nombre_en .($row->nombre_es ? ' / ' .$row->nombre_es : '');
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($nombre,ENT_QUOTES)
                );
            }       
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array(lang('search_not_found'))));
        }
    }
}

if ( ! function_exists('get_json_marcas'))
{
   function get_json_marcas(){
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT id_marca AS id,nombre
                                   FROM marcas 
                                   WHERE estatus=1
                                   ORDER BY nombre ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->nombre,ENT_QUOTES)
                );
            }       
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array(lang('search_not_found'))));
        }
    }
}

if ( ! function_exists('get_json_modelos'))
{
    function get_json_modelos($id_marca){
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT id_modelo AS id,nombre 
                                   FROM modelos  
                                   WHERE estatus=1 AND id_marca=? 
                                   ORDER BY nombre ASC",
                                   array($id_marca));
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->nombre,ENT_QUOTES)
                );
            }       
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array(lang('search_not_found'))));
        }
    }
}

//
if ( ! function_exists('get_data_producto'))
{   
    function get_data_producto($id_prodcuto){
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('p.url_post,p.sku,tp.nombre_en AS tipo_producto')
                 ->from('productos AS p')
                 ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                 ->where('p.id_producto',$id_prodcuto);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                'url_post'      => html_escape($row['url_post']),
                'sku'           => html_escape($row['sku']),
                'tipo_producto' => html_escape($row['tipo_producto'])
            );
        }else{
            return false;
        }
  }
}

# determina si el usuario esta logueado o no..
if ( ! function_exists('user_is_logged'))
{
   function user_is_logged(){
        $CI =& get_instance();
        $CI->load->library('session');
        $arrSesion = $CI->session->userdata('ses_usuario');
        if (!isset($arrSesion['usuario'])){
            return false;

        }else{
            $session_id = $CI->session->userdata('session_id');
            $sess_use_database = $CI->config->item('sess_use_database');
            if (!empty($session_id) and $sess_use_database){
                $CI->load->database('default');
                $query = $CI->db->query('SELECT * FROM ci_sesion 
                                WHERE session_id=?',$session_id);
                return ($query->num_rows()>0) ? true : false;
            }else{
                return (!empty($session_id)) ? true : false;
            }
        }
        $CI->db->close();
        unset($CI);
    }
}

# determina si el usuario es administrador
if ( ! function_exists('user_is_admin'))
{
   function user_is_admin(){
	return user_is(1);
  }  
}

# determina si el usuario es operador
if ( ! function_exists('user_is_operador'))
{
   function user_is_operador(){
	return user_is(2);//el 2 es operador
  }  
}

# determina si el usuario es cliente
if ( ! function_exists('user_is_cliente'))
{
   function user_is_cliente(){
	return user_is(3);//el 3 es cliente        
  }  
}

# determina si el usuario es representante
if ( ! function_exists('user_is_representante'))
{
   function user_is_representante(){
	return user_is(4);//el 3 esrepresentante
  }  
}

# determina si el usuario es del tipo dado..
if ( ! function_exists('user_is'))
{
   function user_is($tipo_de_usuario){
        $tipo_de_usuario = (int)$tipo_de_usuario;
        if(!user_is_logged() or empty($tipo_de_usuario)){
            return FALSE;
        }
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->database('default');
        
        $arrSesion = $CI->session->userdata('ses_usuario');
        if (!isset($arrSesion['id_usuario']) or !isset($arrSesion['usuario'])){
            return FALSE;
        }
        
        $CI->db->select('id_tipo_usuario AS id');
        $CI->db->from('usuarios');
        $CI->db->where('id_usuario',$arrSesion['id_usuario']);
        $query = $CI->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['id']==$tipo_de_usuario ? TRUE :FALSE;
        }else{
            return FALSE;
        }
            
        $CI->db->close();
  }  
}

if ( ! function_exists('get_id_usuario'))
{
   function get_id_usuario(){

        $CI =& get_instance();
        $CI->load->library('session');
        $arrSesion = $CI->session->userdata('ses_usuario');
	return (int)$arrSesion['id_usuario'];
    }
}

if ( ! function_exists('get_name_usuario'))
{
   function get_name_usuario(){

        $CI =& get_instance();
        $CI->load->library('session');
        $arrSesion = $CI->session->userdata('ses_usuario');
	return html_escape($arrSesion['usuario']);
    }
}

if ( ! function_exists('get_user_full_name'))
{
   function get_user_full_name(){
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->database('default');
        $arrSesion = $CI->session->userdata('ses_usuario');
        if (!isset($arrSesion['id_usuario'])){
            return FALSE;
        }else{
            $CI->db->select('nombre,apellido');
            $CI->db->from('usuarios');
            $CI->db->where('id_usuario',$arrSesion['id_usuario']);
            $query = $CI->db->get();
            if($query->num_rows() > 0) {
                $row = $query->row_array();
                return $row['nombre'].' '.$row['apellido'];
            }else{
                return 'Indefinido';
            } 
        }        
    }
}

if ( ! function_exists('get_rol'))
{
   function get_rol(){

        $CI =& get_instance();
        $CI->load->library('session');
        $arrSesion = $CI->session->userdata('ses_usuario');
	return html_escape($arrSesion['rol']);
    }
}

if (! function_exists('cerrar_sesion')){
    
    function cerrar_sesion($redirect=true){
        $CI =& get_instance();
        $CI->load->database('default');
        $sess_use_database = $CI->config->item('sess_use_database');
        if (user_is_logged()){
            $CI->load->library('session');
            if($sess_use_database){
                $delete = false;
                $arrSesion = $CI->session->userdata('ses_usuario');
                $CI->db->select('user_data,session_id');
                $CI->db->from('ci_sesion');
                $query    = $CI->db->get();
                foreach($query->result() as $row){
                    $valor = $row->user_data;
                    $arrData = unserialize($valor);
                    if (isset($arrData['ses_usuario']) and 
                        $arrSesion['usuario']==$arrData['ses_usuario']['usuario']){
                        $CI->db->delete('ci_sesion',array('session_id' => $row->session_id));
                        $delete = true;
                        break;
                    }
                }
                if($delete){
                    $CI->session->sess_destroy();
                }
            }else{
                $CI->session->sess_destroy();
            }
        }
        unset($CI);
        if($redirect){
            redirect('home', 'location');
        }
   }
}


if (! function_exists('get_product_type')){
    function get_product_type(){
      $id_idioma = get_id_idioma();
      
      $CI =& get_instance();
      $CI->load->database('default');
      $CI->db->select('id_tipo_producto,nombre_en,nombre_es')
               ->from('tipos_productos');
      if($id_idioma==2){
        $CI->db->order_by('nombre_en','ASC');
      }else{
          $CI->db->order_by('nombre_es','ASC');
      }
      $query = $CI->db->limit(30)->get();
      if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                # lenguage default
                $nombre = $row->nombre_en;
                # 3 = español
                if($id_idioma==3 and !empty($row->nombre_es)){
                    $nombre = $row->nombre_es;
                }
                $id_tipo_producto = (int)$row->id_tipo_producto;
                $arrDatos[] = array(
                        'id'          => $id_tipo_producto,
                        'nombre'      => html_escape($nombre),
                        'enlace'      => site_url('products').'/type/'.$id_tipo_producto,
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
}

if (! function_exists('get_paises')){
    function get_paises(){   
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('code,name')
               ->from('country');
        $query = $CI->db->order_by('name','ASC')->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $arrDatos[] = array(
                        'code'  => html_escape($row->code),
                        'name'  => html_escape($row->name),
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
}

if (! function_exists('get_slider')){
    function get_slider(){
      $id_idioma = get_id_idioma();
      
      $CI =& get_instance();
      $CI->load->database('default');
      $CI->db->select('id_galeria,nombre,path_img')
             ->from('galeria_global');
      if(!empty($id_idioma)){
          $CI->db->where_in('id_idioma',array(1,$id_idioma));
      }else{
          $CI->db->where('id_idioma',1);
      }
      $CI->db->where('estatus',1);
      $query = $CI->db->order_by('nombre','ASC')
                        ->limit(10)->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id = (int)$row->id_galeria;
                $arrDatos[] = array(
                    'id'                => $id,
                    'nombre'            => html_escape($row->nombre),
                    'path_img'          => html_escape($id.'/'.$row->path_img)
                );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
}

if (! function_exists('get_news')){
    function get_news(){
        $id_idioma = get_id_idioma();

        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('id_noticia,titulo_es,titulo_en,path_img')
            ->from('noticias');
        $CI->db->where('estatus',1);
        $query = $CI->db->order_by('fecha_registro','DESC')
            ->limit(6)->get();

        if($id_idioma==3){
            $campo_titulo="titulo_es";
        }else{
            $campo_titulo="titulo_en";
        }

        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id = (int)$row->id_noticia;

                if(!empty($row->path_img)){

                    $path=html_escape('media/news/pt'.$id.'/'.$row->path_img);
                }else{
                    $path=html_escape('media/default/producto.png');
                }
                $arrDatos[] = array(
                    'id'                => $id,
                    'titulo'            => $row->$campo_titulo,
                    'path_img'          => $path
                );
            }
            return $arrDatos;
        }else{
            return false;
        }
    }
}

if ( ! function_exists('get_last_product'))
{
   function get_last_product($limit=1){
        $lang = get_lang();
        $CI =& get_instance();
        $CI->load->database('default');
        $CI->db->select('p.id_producto AS id,p.sku,p.url_post,p.path_img,
                         tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
                 ->from('productos AS p')
                 ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                 ->where('p.estatus',1)
                 ->where('p.new_release',1)
                 ->order_by('p.sku','ASC')
                 ->order_by('p.fecha_registro','DESC')
                 ->limit($limit);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $arrDatos = array();
            foreach ($query->result() as $row){
                $id_producto = (int)$row->id;
        
                # tipo de producto
                if($lang=='es'){
                    $tipo_producto = html_escape($row->tipo_producto_es);
                }elseif($lang=='en'){
                    $tipo_producto = html_escape($row->tipo_producto_en);
                }
        
                # imagen
                if(!empty($row->path_img) and file_exists('media/products/'.$id_producto.'/'.$row->path_img)){
                    $path_img = 'media/products/'.$id_producto.'/'.html_escape($row->path_img);
                }else{
                    $path_img = 'media/default/producto.png';
                }
        
                $arrDatos[] = array(
                    'sku'           => html_escape(extract_string($row->sku)),
                    'url_post'      => html_escape($row->url_post),
                    'tipo_producto' => extract_string($tipo_producto,38),
                    'path_img'      => $path_img
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
  }
}

if ( ! function_exists('get_types_product'))
{
   function get_types_product($limit=1){
        $lang = get_lang();
        $CI =& get_instance();
        $CI->load->database('default');
        
        $CI->db->select('tp.id_tipo_producto AS tipo_id,p.id_producto AS id,p.sku,p.url_post,tp.path_img,
                         tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
                 ->from('tipos_productos AS tp')
                 ->join('productos AS p','p.id_tipo_producto=tp.id_tipo_producto')
                 ->group_by("tp.id_tipo_producto")
                 ->order_by('tp.id_tipo_producto','RANDOM')
                 ->limit($limit);
        $query = $CI->db->get();
        if ($query->num_rows()>0){
            $arrDatos = array();
            foreach ($query->result() as $row){
                $id_producto = (int)$row->id;
                $id_tipo_producto = (int)$row->tipo_id;

                # tipo de producto
                if($lang=='es'){
                    $tipo_producto = html_escape($row->tipo_producto_es);
                }elseif($lang=='en'){
                    $tipo_producto = html_escape($row->tipo_producto_en);
                }

                # imagen
                if(!empty($row->path_img) and file_exists('media/product_types/pt'.$id_tipo_producto.'/'.$row->path_img)){
                    $path_img = 'media/product_types/pt'.$id_tipo_producto.'/'.$row->path_img;
                }else{
                    $path_img = 'media/default/producto.png';
                }
        
                $arrDatos[] = array(
                    'sku'           => html_escape(extract_string($row->sku)),
                    'url_post'      => html_escape($row->url_post),
                    'tipo_producto' => extract_string($tipo_producto,38),
                    'path_img'      => $path_img,
                    'tipo_id'      => html_escape($row->tipo_id)
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
  }
}

if (! function_exists('get_tipos_productos')){
    function get_tipos_productos(){      
      $CI =& get_instance();
      $CI->load->database('default');
      $CI->db->select('id_tipo_producto,nombre_en,nombre_es')
               ->from('tipos_productos');
      $query = $CI->db->order_by('nombre_en','ASC')
                        ->limit(10)->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $arrDatos[] = array(
                    'id'          => (int)$row->id_tipo_producto,
                    'nombre'      => html_escape($row->nombre_en
                                               .($row->nombre_es ? ' | '.$row->nombre_es : '')),
                );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
}

if (! function_exists('set_color_text')){
    function set_color_text($cadena,$color='red'){
       if(empty($cadena)) return false;
       
       $cadena = trim($cadena);
       $arrCadena = explode(' ',$cadena);
       if(is_array($arrCadena)){
           #
           $first = $arrCadena[0];
            
           #eliminamos el primer elemento y armamos una cadena sin el
           array_shift($arrCadena);
           $string = implode(' ',$arrCadena);
           return '<span class="'.$color.'">'.$first.'</span> '.$string;
       }else{
           return $cadena;
       }
       
  }
}

if (! function_exists('get_lang')){
    function get_lang(){
        $CI   =& get_instance();
        #en|es
        return $CI->lang->lang();
  }
}

if (! function_exists('get_global_lang')){
    function get_global_lang(){
        $CI =& get_instance();
        #en|es
        return $CI->lang->load('global');
  }
}

if (! function_exists('get_id_idioma')){
    function get_id_idioma(){
        $CI   =& get_instance();
        #en|es
        $lang = $CI->lang->lang();
        
        $CI->load->database('default');
        $query = $CI->db->query('SELECT id_idioma
                                 FROM idiomas 
                                 WHERE lang=?',array($lang));
        if($query->num_rows()){
            $row = $query->row_array();
            return (int)$row['id_idioma'];
        }else{
            return false;
        }
  }
}

if (! function_exists('get_data_usuario')){
    function get_data_usuario($sEmail='',$sPass=''){        
        # tipo de busqueda
        if(empty($sEmail) and empty($sPass)){
            $where = 'id_usuario=?';
            $param = array(
                get_id_usuario()
            );
        }else{
            $where = 'email=? AND password=?';
            $param = array(
                $sEmail,
                encriptar_cadena($sPass)
            );             
        }
        $CI =& get_instance();
        $CI->load->database('default');
        $query = $CI->db->query("SELECT u.id_usuario,u.nombre,u.apellido,tu.nombre AS rol,
                                        u.email,u.empresa
                                 FROM usuarios AS u
                                 JOIN tipos_usuarios AS tu ON tu.id_tipo_usuario=u.id_tipo_usuario  
                                 WHERE $where",$param);
        if($query->num_rows()){
            $row = $query->row_array();
            return array(
                'id_usuario'      => $row['id_usuario'],
                'usuario'         => $row['nombre'],
                'nombre_completo' => $row['nombre'].' '.$row['apellido'],
                'rol'             => $row['rol'],
                'email'           => $row['email'],
                'empresa'         => $row['empresa']
            );
        }
        return false;
  }
}

if (! function_exists('generar_sesion')){
    function generar_sesion($sEmail,$sPassword){
        $arrDataUsuario = get_data_usuario($sEmail,$sPassword);
	//echo '<pre>',print_r($arrDataUsuario),'<pre>';exit;
        $arrSesion = array(
            'id_usuario'      => $arrDataUsuario['id_usuario'],
            'email'           => $sEmail,
            'usuario'         => $arrDataUsuario['usuario'],
            'nombre_completo' => $arrDataUsuario['nombre_completo'],
            'rol'             => $arrDataUsuario['rol'],
            'tiempo_login'    => date('Y-m-d H:m:s')
        );
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->session->set_userdata('ses_usuario',$arrSesion);
        if(get_controller()=='home'){
            $CI->session->set_userdata('controller','home');
        }
        redirect('orders', 'location');
   }
}

if (! function_exists('es_correcto_nombre')){
    function es_correcto_nombre($cadena){
        if(empty($cadena)) return false;
        return ctype_alnum_plus($cadena,'esp;ace;dia;ene;gui;pun;num;und;apo;com;2pu;puc;par;cor;sla;mas');
    }
}

/**
 * Función: ctype_alnum_plus
 * Autor: Reinaldo Infante Cassiani (cass)
 * Sitio Web: http://cassianinet.blogspot.com/2011/04/ctypealpha-y-espacios-en-php.html
**/
function ctype_alnum_plus($sCadena,$restriccion='esp',$MostrarError=true){
   if(empty($sCadena)){
 if($MostrarError)
  die('ctype_alnum_plus: el primer parámetro es obligatorío.');
    else return false;
   }
   $restriccion = trim($restriccion,';');
   $sAtajos     = array('esp','ace','dia','ene','gui','pun',
                    'num','und','apo','com','2pu','puc',
                    'par','cor','sla','mas');
   
   if (strlen($restriccion)>3 and strpos($restriccion,';')===false){
      if ($MostrarError) 
 die('ctype_alnum_plus: el segundo parámetro sólo acepta ('
 .implode(',',$sAtajos).') separados por punto y coma (;)');
      else return false;
   }   
   $sExpr = array('esp'=>'\s','ace'=>'ÁÉÍÓÚáéíóú','dia'=>'ÄËÏÖÜäëïöü','ene'=>'Ññ',
                  'pun'=>'\.','gui'=>'\-','num'=>'\d','und'=> '_','apo'=>"\'",
                  'com'=>',','2pu'=>':','puc'=>';','par'=>'()','cor'=>'\[\]',
                  'sla'=>'\/','mas'=>'\+');
   
   $sPatron  = '/^[a-z';
   $restriccion = strtolower($restriccion);
   if ($restriccion=='esp'){
      $sPatron .= $sExpr['esp'];
   }else{
      $arrRestriccion = explode (';',$restriccion);
      foreach ($arrRestriccion as $name) {
         if (!in_array($name,$sAtajos)) {
            if($MostrarError) 
  die('ctype_alnum_plus: el segundo parámetro sólo acepta ('
                .implode(',',$sAtajos).') separados por punto y coma (;)');
            else return false;
         }
         $sPatron .= $sExpr[$name];
      }
   }
   $sPatron .= ']+$/i';
   return preg_match($sPatron,$sCadena) ? true : false;
}

// valida Email
function no_es_email_valido($sEmail){
   if(empty($sEmail)) return true;
   $patron="/^[_a-zA-Z0-9-ñÑ]+(\\.[_a-zA-Z0-9-ñÑ]+)*@+([_a-zA-Z0-9-]+\\.)*[a-zA-Z0-9-]{2,100}\\.[a-zA-Z]{2,6}$/";
   return (!preg_match($patron,$sEmail) ? true : false);
}

if (! function_exists('get_year')){
    function get_year($fecha){
        # 2012/07/02/ 11:35:41
         if(empty($fecha)){ 
            return false; 
        }else{
            list($year,$mes,$dia)= explode('-',$fecha);
            return $year;
        }
    }
}

if (! function_exists('extraer_anio')){
    function extraer_anio($fecha,$donde=1){
        #1 02/07/2012
        #2 2012/07/02
        list($uno,$dos,$tres)= explode('/',$fecha);
        if($donde==1){
            return (int)$uno;
        }elseif($donde==2){
            return (int)$tres;
        }
    }
}

if (! function_exists('str_to_date')){
    function str_to_date($fecha,$opt=1){
        # 02/07/2012
        if(empty($fecha)){ 
            return false;
        }else{
            if($opt==1){
                list($dia,$mes,$anio)= explode('/',$fecha);
                return $anio.'/'.$mes.'/'.$dia;
            }elseif($opt==2){
                list($anio,$mes,$dia)= explode('-',$fecha);
                return $dia.'/'.$mes.'/'.$anio;
            }
        }
    }
}

if (! function_exists('get_fecha_completa')){
    function get_fecha_completa($fecha,$show_hora=false){
        # 2012/07/02/ 11:35:41
        
        if(empty($fecha)){ 
            $fecha = date('Y/m/d H:i:s');
            
        }else{
            list($anio,$mes,$dia)= explode('-',$fecha);
            list($dia,$hora)     = explode(' ',$dia);
        }
        
        $arrMeses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Dicembre'
        );
        
        return $dia.' de '.$arrMeses[$mes] .' del '.$anio.($show_hora ? ' '.$hora : '').'.';
    }
}

if (! function_exists('compara_fechas')){
    function compara_fechas($FechaHora1, $FechaHora2){
        /*
         * $f1="12/30/1993 22:50:01"; 
        $f2="10-30-1992 23:12:31";

        if (compara_fechas($f1,$f2) <0)
          echo "$f1 es menor que $f2";

        if (compara_fechas($f1,$f2) >0)
          echo "$f1 es mayor que $f2";

        if (compara_fechas($f1,$f2) ==0)
          echo "$f1 es igual que $f2";
        */
        list($Fecha1,$Tiempo1) = explode(" ",$FechaHora1);
        list($Fecha2,$Tiempo2) = explode(" ",$FechaHora2);

        list($Hora1,$Minuto1,$Segundo1) = explode(":",$Tiempo1);
        list($Hora2,$Minuto2,$Segundo2) = explode(":",$Tiempo2);

        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$Fecha1))
          list($Mes1,$Dia1,$Anio1) = explode('/',$Fecha1);

        if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$Fecha1))
          list($Mes1,$Dia1,$Anio1) = explode('-',$Fecha1);

        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$Fecha2))      
          list($Mes2,$Dia2,$Anio2) = explode('/',$Fecha2);

        if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$Fecha2))
          list($Mes2,$Dia2,$Anio2) = explode('-',$Fecha2);

        $Hora1=(int)$Hora1;
        $Hora2=(int)$Hora2;

        $Minuto1=(int)$Minuto1;
        $Minuto2=(int)$Minuto2;

        $Segundo1=(int)$Segundo1;
        $Segundo2=(int)$Segundo2;

        $Dia1=(int)$Dia1;
        $Dia2=(int)$Dia2;

        $Mes1=(int)$Mes1;
        $Mes2=(int)$Mes2;

        $Anio1=(int)$Anio1;
        $Anio2=(int)$Anio2;

        $Fec1 = mktime($Hora1,$Minuto1,$Segundo1,$Mes1,$Dia1,$Anio1);		

        $Fec2 = mktime($Hora2,$Minuto2,$Segundo2,$Mes2,$Dia2,$Anio2);

        $Dif = $Fec1 - $Fec2;

        return ($Dif); 
     }
}

if (! function_exists('generar_log')){
   function generar_log($cadena){
        $habilitado = false;
        if(!$habilitado or empty($cadena)) return false;
        $CI =& get_instance();
        $CI->load->helper('file');
        $txt = './includes/log.txt';
        //if(file_exists($txt)){ unlink($txt);}
        return write_file($txt, '[===]'.$cadena, 'a') ? true : false;
   }
}

if ( ! function_exists('send_email'))
{
    function send_email($destinatario,$titulo,$mensaje,$name_remitente,$email_remitente,$debug=false,$arrAdjunto=array()){
        $CI =& get_instance();
        $CI->load->library('email');
        /*
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = '';
        $config['smtp_pass'] = '';
        $config['charset']   = 'iso-8859-1';
         */
        $config['charset']   = 'utf-8';
        $config['mailtype']  = 'html';
        $config['newline']   = '\r\n';
        $config['wordwrap']  = TRUE;
        $CI->email->initialize($config);
        $CI->email->from($email_remitente,$name_remitente);
        $CI->email->to($destinatario);
        $CI->email->subject($titulo);
        $CI->email->message($mensaje);

        if(is_array($arrAdjunto) and count($arrAdjunto)>0){
            foreach($arrAdjunto as $item){
                $CI->email->attach($item);
            }
        }
        
        $result_email = $CI->email->send();
        if($debug) echo $CI->email->print_debugger();
        return $result_email;
    }
}

if ( ! function_exists('extract_string'))
{
    function extract_string($sCadena,$Longitud=60,$inicio=0){
            if (empty($sCadena)) return false; 
    
            return ucfirst(((strlen($sCadena)>=$Longitud)
                   ? trim(substr($sCadena,$inicio,$Longitud)).' ..' 
                   : trim($sCadena)));
    }
}

if ( ! function_exists('show_msj_error'))
{
	function show_msj_error($sMsj,$show_x=true){          
            echo '<div class="alert alert-danger alert-dismissable">';
            if($show_x){
                echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            }
            echo strip_tags($sMsj,'<a><b><br><span><strong><i>').'</div>';
        }        
}

if ( ! function_exists('show_msj_confirmacion'))
{
	function show_msj_confirmacion($sMsj,$show_x=true){          
            echo '<div class="alert alert-success alert-dismissable">';
            if($show_x){
                echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            }
            echo strip_tags($sMsj,'<a><b><br><span><strong><i>').'</div>';
        }        
}

if ( ! function_exists('show_msj_info'))
{
	function show_msj_info($sMsj,$show_x=true){
            echo '<div class="alert alert-info alert-dismissable">';
            if($show_x){
                echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            }
            echo strip_tags($sMsj,'<a><b><br><span><strong><i>').'</div>';
        }        
}

if ( ! function_exists('show_msj_warning'))
{
	function show_msj_warning($sMsj,$show_x=true){
            echo '<div class="alert alert-warning alert-dismissable" style="color:yelllow">';
            if($show_x){
                echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            }
            echo strip_tags($sMsj,'<a><b><br><span><strong><i>').'</div>';
        }        
}

if ( ! function_exists('rnd_string'))
{
   function rnd_string($long_string){
        $str_rnd_string = '';
        for ($i=0; $i<$long_string - 1; $i++){
            switch (rand(0,2)){
                case 0: $str_rnd_string .= chr((rand(0,9)) + 48);break;
                case 1: $str_rnd_string .= chr((rand(0,25)) + 65);break;
                case 2: $str_rnd_string .= chr((rand(0,25)) + 97);break;
            }
        }
        return $str_rnd_string;
   }
}

if (! function_exists('procesar_imagenes')){
    function procesar_imagenes($FILE,$ruta='',$return_json=true,$arrExtra=array()){
        //$path_destino='./includes/images/'.$ruta;
        $path_destino='./media/'.$ruta;

        if(!is_dir($path_destino)){
            mkdir($path_destino);
        }
        if(isset($FILE)){
            $ret       = array();
            $old_name  = $FILE["name"];
            if(isset($arrExtra['top_name']) and !empty($arrExtra['top_name'])){
                $arrExtra['top_name'] = encode_link($arrExtra['top_name']);
                $new_name             = $arrExtra['top_name'].'-'.rnd_string(4);
            }else{
                $new_name  = encriptar_cadena(rnd_string(5).rnd_string(5));
            }
            $trozos    = explode('.',basename($old_name)); 
            $new_name .= '.'.end($trozos);
            $file      = $path_destino.$new_name;
            if(move_uploaded_file($FILE['tmp_name'],$file)){
                $ret[] = $new_name;
            }else{
                 $ret[] = 'Error al subir la imagen'; 
            }
            // si se solicito el json, lo devolvemos junto con el nombre 
            // en un array, para procesarlo por separado
            // sino devolvemos solo el nombre..


            if($return_json){
                return array('name'=>$new_name,'json'=>json_encode($ret));
            }else{
                return $new_name;
            }
         }
    }
}

if (! function_exists('procesar_archivo')){
    function procesar_archivo($FILE,$ruta='',$arrExtra=array()){
        $path_destino='./downloads/'.$ruta;

        if(!is_dir($path_destino)){
            mkdir($path_destino);
        }
        if(isset($FILE)){
            $old_name  = $FILE["name"];
            if(isset($arrExtra['top_name']) and !empty($arrExtra['top_name'])){
                $arrExtra['top_name'] = encode_link($arrExtra['top_name']);
                $new_name             = $arrExtra['top_name'];
            }else{
                $new_name  = encriptar_cadena(rnd_string(5).rnd_string(5));
            }
            $trozos    = explode('.',basename($old_name)); 
            $new_name .= '.'.end($trozos);
            $file      = $path_destino.$new_name;
            if(move_uploaded_file($FILE['tmp_name'],$file)){
                return $new_name;
            }else{
                return FALSE;
            }
         }
    }
}

if ( ! function_exists('delete_dir_and_file'))
{
    function delete_dir_and_file($dir){
        // verifica si es una carpeta valida
        if(is_dir($dir)){
            // recorre los archivos del directorio y los va eliminando
            foreach(glob($dir . "/*") as $file){
                // si encuentra una carpeta, se hace recursiva
                if(is_dir($file)){
                    delete_dir_and_file($file);
                    
                // si es un archivo lo elimina
                }elseif(is_file($file) and $file <> 'media/default/producto.png'){
                    unlink($file);
                }
            }
            // se elimina la carpeta
            //rmdir($dir);
        }
    }
}

if ( ! function_exists('convertir_fecha'))
{
    function convertir_fecha($cadena,$tipo='date'){
        switch($tipo){
            case 'date':{
                //2012-11-29
                list($anio,$mes,$dia) = explode('-',$cadena);
                return $dia.'/'.$mes.'/'.$anio;
                break;
            }
            case 'date_inverso':{
                //29-19-2012
                list($dia,$mes,$anio) = explode('/',$cadena);
                return $anio.'-'.$mes.'-'.$dia;
                break;
            }
            case 'timestamp':{
                //2012-11-29 10:06:20.38148
                list($fecha,$hora)      = explode(' ',$cadena);
                list($anio,$mes,$dia)   = explode('-',$fecha);
                //list($hora_f,$micros_s) = explode('.',$hora);
                return $dia.'/'.$mes.'/'.$anio;//.' '.$hora_f;
                break;
            }
        }
   }
}

if ( ! function_exists('generar_pdf_tcpdf'))
{
   function generar_pdf_tcpdf($body,$titulo_archivo=''){
        $sTitulo        = 'EMG International';
        $titulo_archivo = !empty($titulo_archivo) ? $titulo_archivo : 'EMG_International'.date('d-m-Y');
	$html           = "<html><head>
        <style>
            h1{
                font-family: times;
                font-size: 50px;
                text-align:center;
            }
            .center{
                text-align:center;
            }
            .nota{
                font-family: arial;
                font-size: 10pt;
            }
            table{
                border: 1px solid #ddd;
                margin: 0 auto;
            }
            .campo {
                color: #000;
		font-weight: bold;
                font-family: Arial Black;
                font-size: 28px;
                background-color: #BDE5F8;
                text-align: left;
                width: 40%;
            }
            .valor {
		font-weight: none;
                color: #000;
                background-color: #fff;
                font-size: 26px;
                font-family: times new roman;
            }
            .page-producto{
                margin-top: 10px;
                padding: 1px;
            }
            .page-producto .product-row{
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
                padding-bottom: 20px;
                border-bottom: 1px solid #ddd; 
            }
            .page-producto .product-row .imagen{
                text-align: center;
                margin-right: 10px;
            }
            .page-producto .product-row .titulo{
                text-align: center;
            }
            .page-producto .product-row .titulo a{
                font-size: 50px;
                font-weight: bold;
                color: #2a2a2a;
                text-align: center;
                text-transform: uppercase;
            }
            .page-producto .product-row .descripcion p{
                font-size: 50px;
                font-weight: none;
                color: #5c5b5b;
                text-align: justify;
                margin-top: 5px;
                word-break: break-all;
            }
            .page-producto .product-row .datos-linea{
                font-size: 50px;
                color: #de2211;
                text-align: center;
            }
        </style>
        </head><body><h1 align='center'>$sTitulo</h1>";
        $html .= $body;
        $html .= '</body></html>';
        // Limpiar (eliminar) y deshabilitar los búferes de salida
	if(ob_get_contents()){
		ob_end_clean();
	}
        //exit($html);
        ini_set("memory_limit","512M");
	//memory_limit = 512M;
        //require_once('./includes/plugins/tcpdf/config/lang/spa.php');
	require_once('./includes/plugins/tcpdf/tcpdf.php');
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('EMG International');
	$pdf->SetTitle($sTitulo);
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	//$pdf->setLanguageArray($l);
	$pdf->setFontSubsetting(true);
	$pdf->SetFont('dejavusans', '', 12, '', true);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($titulo_archivo.'.pdf','D');
  } 
}

if ( ! function_exists('get_server_path'))
{
   function get_server_path(){ 
       return '/home/users/web/b164/dom.robertobeltran/public_html/';
   }
}

if ( ! function_exists('generar_pdf_dompdf'))
{
   function generar_pdf_dompdf($html,$titulo_archivo='',$forzar_descarga = FALSE){  
        $titulo_archivo = !empty($titulo_archivo) ? 'EMG_Catalog_'.$titulo_archivo.'_'.strtotime('now') : 'EMG_Catalog_'.strtotime('now');
	if(ob_get_contents()){ob_end_clean();}
        //exit($html);
        require_once './includes/plugins/dompdf/dompdf_config.inc.php';
	
        ob_start();
        ini_set('error_log', dirname(__FILE__).'error.log');
        ini_set('log_errors', 'on');
        //ini_set('display_errors', 'on');

	ini_set('allow_url_include', 'on');
        ini_set("memory_limit", "2048M");
        ini_set("max_execution_time", "999");

	$dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        if(!$forzar_descarga){
            $path    = 'downloads/pdf/';
            $archivo = $path."$titulo_archivo.pdf";
            file_put_contents($archivo, $dompdf->output());
            return array(
                    'name'      => "$titulo_archivo.pdf",
                    'path_file' => base_url().$archivo
                );
        }else{
            $dompdf->stream("$titulo_archivo.pdf");
        }        
  } 
}

if ( ! function_exists('noCache')){
    function noCache(){
        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}

if ( ! function_exists('clear_dirctory'))
{
    function clear_dirctory($dir){
        $ficheroseliminados= 0;
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if (is_file($dir.$file)) {
                if ( unlink($dir.$file) ){
                    //echo getcwd();
                    $ficheroseliminados++;
                }         
            }
         }
         return $ficheroseliminados;
    }
}