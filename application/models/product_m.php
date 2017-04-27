<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
  
  function get_data_pdf($id_producto){
        $lang = get_lang();
        $this->db->select('p.id_producto,p.oem,p.url_post,p.path_img,
                            p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                            p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                            p.descripcion_es,p.precio,
                            tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
                        ->from('productos AS p')
                        ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                        ->where('p.estatus','1')
                        ->where('p.id_producto',$id_producto);
        $this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if ($query->num_rows()>0){
            $row = $query->row_array();
            $id_producto  = (int)$row['id_producto'];
            # gestion del lenguaje
            if($lang=='es'){
                $datos_linea = 'Tipo: '.$row['tipo_producto_es'];
                $descripcion = $row['descripcion_es'];
            }elseif($lang=='en'){
                $datos_linea = 'Type: '.$row['tipo_producto_en'];
                $descripcion = $row['descripcion_en'];
            }
            return array(
                'id'               => $id_producto,
                'url_post'         => html_escape($row['url_post']),
                'titulo'           => html_escape($row['sku']),
                'path_img'         => !empty($row['path_img']) ? 'media/products/'.html_escape($id_producto.'/'.$row['path_img']) : 'media/default/producto.png',
                'datos_linea'      => html_escape($datos_linea),
                'descripcion'      => html_escape(extract_string($descripcion,200)),
                'arrApplicaciones' => get_applications_by_product(array(),$id_producto)
            );
        }else{
            return FALSE;
        }
  }

  function get_product($url_post){
        $lang = get_lang();
        $this->db->select('p.id_producto,p.oem,p.url_post,p.path_img,
                                    p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                                    p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                                    p.descripcion_es,p.precio,
                                    tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
                        ->from('productos AS p')
                        ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                        ->where('p.estatus','1')
                        ->where('p.url_post',$url_post);
        $query = $this->db->limit(1)->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();
            $id_producto  = (int)$row['id_producto'];
            # gestion del lenguaje
            if($lang=='es'){
                $datos_linea = 'Tipo: '.$row['tipo_producto_es'];
                $descripcion = $row['descripcion_es'];
            }elseif($lang=='en'){
                $datos_linea = 'Type: '.$row['tipo_producto_en'];
                $descripcion = $row['descripcion_en'];
            }
            return array(
                'id'               => $id_producto,
                'titulo'           => html_escape($row['sku']),
                'path_img'         => !empty($row['path_img']) ? 'media/products/'.html_escape($id_producto.'/'.$row['path_img']) : 'media/default/producto.png',
                'datos_linea'      => html_escape($datos_linea),
                'descripcion'      => html_escape(extract_string($descripcion,200)),
                'arrApplicaciones' => get_applications_by_product(array(),$id_producto)
            );
        }else{
            return FALSE;
        }
  }
}