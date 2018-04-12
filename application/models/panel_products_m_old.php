<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_products_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }

  
  function get_products($arrParam,$limit,$desde,$order_by='',$selUpDown=''){
      
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'p.id_producto';
                break;
            }
            case 2:{#tipo de productos
                $order_by = 'tp.nombre_en';
                break;
            }
            case 3:{#sku
                $order_by = 'p.sku';
                break;
            }    
            case 4:{#oem
                $order_by = 'p.oem';
                break;
            }        
            case 5:{#smp
                $order_by = 'p.smp';
                break;
            }
            case 6:{#wells
                $order_by = 'p.wells';
                break;
            }
            case 20:{#estatus
                $order_by = 'p.estatus';
                break;
            }
            default:{
                $order_by = 'p.sku';
            }
        }
        $selUpDown = (int)$selUpDown;
        switch($selUpDown){
            case 1:{#id
                $selUpDown = 'ASC';
                break;
            }
            case 2:{#
                $selUpDown = 'DESC';
                break;
            }
            default:{
                $selUpDown = 'ASC';
            }
        }

        $this->db->select('p.id_producto,p.oem,p.url_post,p.path_img,
                            p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                            p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                            p.descripcion_es,p.precio,p.estatus AS p_estatus,p.fecha_registro,
                            tp.nombre_en AS tipo_producto')
                        ->from('productos AS p')
                        ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto');
            
        # filtros del buscador
        if(is_array($arrParam)){
            if(isset($arrParam['txtSKU']) and !empty($arrParam['txtSKU'])){
                $this->db->where('p.sku',$arrParam['txtSKU']);
            }
            if(isset($arrParam['txtOEM']) and !empty($arrParam['txtOEM'])){
                $this->db->where('p.oem',$arrParam['txtOEM']);
            }
            if(isset($arrParam['txtSMP']) and !empty($arrParam['txtSMP'])){
                $this->db->where('p.smp',$arrParam['txtSMP']);
            }
            if(isset($arrParam['txtWELLS']) and !empty($arrParam['txtWELLS'])){
                $this->db->where('p.wells',$arrParam['txtWELLS']);
            }
            if(isset($arrParam['selTiposProductos']) and !empty($arrParam['selTiposProductos'])){
                $this->db->where('p.id_tipo_producto',$arrParam['selTiposProductos']);
            }

            # -- filter applications
            $arrParam_app = ''; 
            if(isset($arrParam['selTiposMotores']) and !empty($arrParam['selTiposMotores'])){
                $arrParam_app['tipo_motor'] = $arrParam['selTiposMotores'];
            }
            if(isset($arrParam['selMarcas']) and !empty($arrParam['selMarcas'])){
                $arrParam_app['marca'] = $arrParam['selMarcas'];
            }
            if(isset($arrParam['selModelos']) and !empty($arrParam['selModelos'])){
                $arrParam_app['modelo'] = $arrParam['selModelos'];
            }            
            if(isset($arrParam['txtYears']) and !empty($arrParam['txtYears'])){
                $arrParam_app['years'] = $arrParam['txtYears'];
            }
        }
               
        $this->db->order_by($order_by,$selUpDown)->limit($limit,$desde);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $arrResult = array();
            foreach ($query->result() as $row){
                $id_producto  = (int)$row->id_producto;
                # obtenemos las aplicaciones del producto
                $arrApplicaciones = get_applications_by_product($arrParam_app,$id_producto);
                //echo "<pre>",print_r($arrApplicaciones),"</pre>";
                # si hay parametros y no hay resultados, no cargamos datos
                if(!is_array($arrApplicaciones)){
                    continue;
                }
                $arrResult[] = array(
                    'id'              => $id_producto,
                    'tipo_producto'   => html_escape($row->tipo_producto),
                    'sku'             => html_escape($row->sku),
                    'oem'             => html_escape($row->oem),
                    'smp'             => html_escape($row->smp),
                    'wells'           => html_escape($row->wells),
                    'url_post'        => html_escape($row->url_post),
                    'estatus'         => $row->p_estatus==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows($arrParam){
        $this->db->select('count(p.id_producto) AS total');
        $this->db->from('productos AS p');
        
        # filtros del buscador
        if(is_array($arrParam)){
            if(isset($arrParam['txtSKU']) and !empty($arrParam['txtSKU'])){
                $this->db->where('p.sku',$arrParam['txtSKU']);
            }
            if(isset($arrParam['txtOEM']) and !empty($arrParam['txtOEM'])){
                $this->db->where('p.oem',$arrParam['txtOEM']);
            }
            if(isset($arrParam['txtSMP']) and !empty($arrParam['txtSMP'])){
                $this->db->where('p.smp',$arrParam['txtSMP']);
            }
            if(isset($arrParam['txtWELLS']) and !empty($arrParam['txtWELLS'])){
                $this->db->where('p.wells',$arrParam['txtWELLS']);
            }
            if(isset($arrParam['selTiposProductos']) and !empty($arrParam['selTiposProductos'])){
                $this->db->where('p.id_tipo_producto',$arrParam['selTiposProductos']);
            }
        }
        
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['total'];
        }else{
            return FALSE;
        }
  }
  
  function registrar($arrDatos){
        $this->db->insert('productos',
              array(
                  'url_post'           => generar_url_product($arrDatos['txtSKU']),
                  'id_tipo_producto'   => isset($arrDatos['selTiposProductos']) ? $arrDatos['selTiposProductos'] : '0',
                  'nombre_en'          => '',
                  'nombre_es'          => '',
                  'sku'                => isset($arrDatos['txtSKU']) ? $arrDatos['txtSKU'] : 'no sku',
                  'new_release'        => isset($arrDatos['selNewRelease']) ? $arrDatos['selNewRelease'] : '',
                  'descripcion_en'     => isset($arrDatos['txtaDescripcionEn']) ? $arrDatos['txtaDescripcionEn'] : '',
                  'descripcion_es'     => isset($arrDatos['txtaDescripcionEs']) ? $arrDatos['txtaDescripcionEs'] : '',
                  'oem'                => isset($arrDatos['txtOEM']) ? $arrDatos['txtOEM'] : '',
                  'smp'                => isset($arrDatos['txtSMP']) ? $arrDatos['txtSMP'] : '',
                  'wells'              => isset($arrDatos['txtWells']) ? $arrDatos['txtWells'] : '',
                  'path_img'           => isset($arrDatos['Imagen']) ? $arrDatos['Imagen'] : '',
                  'precio'             => isset($arrDatos['txtSellPrice']) ? $arrDatos['txtSellPrice'] : '',
                  'estatus'            => '1',
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('p.id_producto,p.oem,p.url_post,p.path_img,
                        p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                        p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                        p.descripcion_es,p.precio,p.estatus AS p_estatus,p.fecha_registro,
                        p.id_tipo_producto')
                        ->from('productos AS p')
                        ->where('id_producto',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                    'id_producto'        => $id,
                    'url_post'           => $row['url_post'],
                    'id_tipo_producto'   => $row['id_tipo_producto'],
                    'nombre_en'          => $row['producto_en'],
                    'nombre_es'          => $row['producto_es'],
                    'sku'                => $row['sku'],
                    'new_release'        => (int)$row['new_release'],
                    'descripcion_en'     => $row['descripcion_en'],
                    'descripcion_es'     => $row['descripcion_es'],
                    'oem'                => $row['oem'],
                    'smp'                => $row['smp'],
                    'wells'              => $row['wells'],
                    'path_img'           => $row['path_img'] ? 'media/products/'.$id.'/'.html_escape($row['path_img']) : 'media/default/producto.png',
                    'precio'             => $row['precio'],
                    'estatus'            => (int)$row['p_estatus']
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            'id_tipo_producto'   => (isset($arrData['selTiposProductos']) ? $arrData['selTiposProductos'] : ''),
            'nombre_en'          => '',
            'nombre_es'          => '',
            'sku'                => (isset($arrData['txtSKU']) ? $arrData['txtSKU'] : ''),
            'new_release'        => (isset($arrData['selNewRelease']) ? $arrData['selNewRelease'] : ''),
            'descripcion_en'     => (isset($arrData['txtaDescripcionEn']) ? $arrData['txtaDescripcionEn'] : ''),
            'descripcion_es'     => (isset($arrData['txtaDescripcionEs']) ? $arrData['txtaDescripcionEs'] : ''),
            'oem'                => (isset($arrData['txtOEM']) ? $arrData['txtOEM'] : ''),
            'smp'                => (isset($arrData['txtSMP']) ? $arrData['txtSMP'] : ''),
            'wells'              => (isset($arrData['txtWells']) ? $arrData['txtWells'] : ''),
            'precio'             => (isset($arrData['txtSellPrice']) ? $arrData['txtSellPrice'] : ''),
            'estatus'            => (isset($arrData['selEstatus']) ? (int)$arrData['selEstatus'] : '')
        );
       
        // le pasamos los datos e la nueva imagen, si fue enviada
        if(!empty($arrData['Imagen']) and !empty($arrData['imagen_actual'])){
            // obtenemos los datos de la imagen anterior y la eliminamos junto 
            // con las resoluciones
            $ruta = './media/products/';
            if(file_exists($arrData['imagen_actual'])){
                  $arrFile = explode('.', $arrData['imagen_actual']);
                  if(is_array($arrFile)){
                        // obtenemos la extension de la imagen
                        $ext      = end($arrFile);
                        // extraemos y almacenamos la ruta sin la extension
                        $imagenes = array_shift(explode(('.'.$ext),($arrData['imagen_actual']))); 
                        // eliminamos las imagenes usando comodin
                        foreach (glob($imagenes.'*') as $nombre_archivo){
                            // eliminamos todas las imagenes menos la 
                            // que se acaba de subir
                            if($nombre_archivo <> ($ruta.$id.'/'.$arrData['Imagen']) and 
                               $nombre_archivo <> 'media/default/producto.png'){
                                unlink($nombre_archivo);
                            }
                        }
                  }
            }
            // nueva imagen a actualizar
            $arrUPD['path_img'] = $arrData['Imagen'];
        }

        $this->db->where('id_producto',$id);
        $this->db->update('productos',$arrUPD);
        return true;
  }
   
  function set_image($id,$image){
        $arrUPD['path_img'] = $image;
        $this->db->where('id_producto',$id);
        $this->db->update('productos',$arrUPD);
        return true;
  }

  function delete($id){
        #del applications
        $this->db->where('id_producto',$id);
        $this->db->delete('aplicaciones_productos');
       
        # del products
        $this->db->where('id_producto',$id);
        $this->db->delete('productos');

        // eliminamos las imagenes
        delete_dir_and_file('./media/products/'.$id.'/');
   }
   
   function truncate_all_data_prodcuts(){
        $this->db->empty_table('aplicaciones_productos');
        $this->db->empty_table('productos');
        $this->db->empty_table('modelos');  
        $this->db->empty_table('marcas');
        $this->db->empty_table('tipos_productos'); 
        $this->db->empty_table('tipos_motores');        

        // eliminamos las imagenes
        delete_dir_and_file('./media/products/');
   }
   
    public function get_json_tipos_productos(){
        $query = $this->db->query("SELECT id_tipo_producto AS id,nombre_en,nombre_es
                                   FROM tipos_productos 
                                   WHERE estatus=1
                                   ORDER BY nombre_en ASC");
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->nombre_en . ' - ' . $row->nombre_es,ENT_QUOTES)
                );
            }       
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array("-- no se encontraron registros..")));
        }
    }
    
    # funciones de procesamiento de xls
    # product ------------------------------------------------------------------
    function existe_producto($sku){
        $this->db->select('id_producto AS id')
                 ->from('productos')
                 ->where('sku',$sku);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    # product type -------------------------------------------------------------
    function existe_product_type($nombre_en){
        $this->db->select('id_tipo_producto AS id')
                 ->from('tipos_productos')
                 ->where('nombre_en',$nombre_en);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_tipo_producto($nombre_en,$nombre_es){        
        $this->db->insert('tipos_productos',
              array(
                  'nombre_en' => $nombre_en,
                  'nombre_es' => $nombre_es
              ));
        return $this->db->insert_id();
    }
    function update_tipo_producto($id,$nombre_en,$nombre_es){
        $arrUPD['nombre_en'] = $nombre_en;
        $arrUPD['nombre_es'] = $nombre_es;
        $this->db->where('id_tipo_producto',$id);
        $this->db->update('tipos_productos',$arrUPD);
        return true;
    }
    # manufacturer -------------------------------------------------------------
    function existe_marca($marca){
        $this->db->select('id_marca AS id')
                 ->from('marcas')
                 ->where('nombre',$marca);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_marca($marca){
        $this->db->insert('marcas',
              array(
                  'nombre' => $marca
              ));
        return $this->db->insert_id();
    }
    # modelo--------------------------------------------------------------------
    function existe_modelo($id_marca,$modelo){
        $this->db->select('id_modelo AS id')
                 ->from('modelos')
                 ->where('id_marca',$id_marca)
                 ->where('nombre',$modelo);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_modelo($id_marca,$modelo){
        $this->db->insert('modelos',
              array(
                  'id_marca' => $id_marca,
                  'nombre'   => $modelo
              ));
        return $this->db->insert_id();
    }
    # tipo de motor ------------------------------------------------------------
    function existe_tipo_motor($tipo_motor){
        $this->db->select('id_tipo_motor AS id')
                 ->from('tipos_motores')
                 ->where('nombre_en',$tipo_motor);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_tipo_motor($tipo_motor){
        $this->db->insert('tipos_motores',
              array(
                  'nombre_en' => $tipo_motor
              ));
        return $this->db->insert_id();
    }
    # aplicacion producto ------------------------------------------------------
    function existe_aplicacion_producto($id_producto,$id_tipo_motor,$id_marca,$id_modelo){
        $this->db->select('id_aplicaciones_producto AS id')
                 ->from('aplicaciones_productos')
                 ->where('id_producto',$id_producto)
                 ->where('id_tipo_motor',$id_tipo_motor)
                 ->where('id_marca',$id_marca)
                 ->where('ref_id_modelo',$id_modelo);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_aplicacion_producto($id_producto,$id_tipo_motor,$id_marca,$id_modelo,$years){
        $this->db->insert('aplicaciones_productos',
              array(
                  'id_producto'   => $id_producto,
                  'id_tipo_motor' => $id_tipo_motor,
                  'id_marca'      => $id_marca,
                  'ref_id_modelo' => $id_modelo,
                  'years'         => $years
              ));
        return $this->db->insert_id();
    }
    
    function get_data_export(){
        $this->db->select('p.oem,p.new_release,
                           tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es,
                           p.smp,p.wells,p.sku,p.precio,
                           m.nombre AS marca,ap.ref_id_modelo,tm.nombre_en AS motor,ap.years')
                        ->from('aplicaciones_productos AS ap')
                        ->join('productos AS p','ap.id_producto=p.id_producto')
                        ->join('marcas AS m','m.id_marca=ap.id_marca')
                        ->join('tipos_motores AS tm','tm.id_tipo_motor=ap.id_tipo_motor')
                        ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                        ->order_by('p.sku','ASC');
        //$this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if ($query->num_rows()>0){
            $arrResult = array();
            foreach ($query->result() as $row){
                $arrResult[] = array(
                    'oem'              => html_escape($row->oem),
                    'new_release'      => html_escape($row->new_release),
                    'tipo_producto_en' => html_escape($row->tipo_producto_en),
                    'tipo_producto_es' => html_escape($row->tipo_producto_es),
                    'imagen'           => '',
                    'smp'              => html_escape($row->smp),
                    'wells'            => html_escape($row->wells),
                    'sku'              => html_escape($row->sku),
                    'sell_price'       => html_escape($row->precio),
                    'marca'            => html_escape($row->marca),
                    'modelo'           => get_modelo($row->ref_id_modelo),
                    'motor'            => html_escape($row->motor),
                    'years'            => html_escape($row->years)
                );
            }
            return $arrResult;
        }else{
            return FALSE;
        }
    }
  
    # --------------------------------------------------------------------------
    # admin search user control
    #---------------------------------------------------------------------------
    function set_field($campo,$valor){
        if($this->if_exists_field($campo)){
            $this->db->where('campo',$campo);
            $this->db->where('hash',$this->get_hash());
            $this->db->update('admin_search',array('valor' => $valor));
        }else{
            $this->db->insert('admin_search',array(
                'campo' => $campo,
                'valor' => $valor,
                'hash'  => $this->get_hash()
            ));
        }
        $this->delete_fields();
        //exit($this->db->last_query());
    }
    
    function if_exists_field($campo){
        $this->db->select('valor')->from('admin_search')
                ->where('campo',$campo)
                ->where('hash',$this->get_hash());
        $query = $this->db->get();
        //exit($this->db->last_query());
        if ($query->num_rows()>0){
             return TRUE;
         }else{
             return FALSE;
         }
    }
    
    function get_field($campo){
        $this->db->select('valor')->from('admin_search')
                ->where('campo',$campo)
                ->where('hash',$this->get_hash());
        $query = $this->db->get();
        //exit($this->db->last_query());
        if ($query->num_rows()>0){
             $row = $query->row_array();
             return $row['valor'];
         }else{
             return FALSE;
         }
    }
    
    function get_hash(){
        return md5($this->input->ip_address());
    }
    
    function delete_fields(){
        $this->db->where('hash',$this->get_hash());
        $this->db->where('WEEK(fecha_registro) <','WEEK(curdate())');
        $this->db->delete('admin_search');
    }
}