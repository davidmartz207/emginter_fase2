<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_inicio_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
  
  function get_products(){
        $query=$this->db->select("p.id_producto,p.oem,p.url_post,p.path_img,
                            p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                            p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                            p.descripcion_es,p.precio,p.estatus AS p_estatus,p.fecha_registro,
                            tp.nombre_en AS tipo_producto,GROUP_CONCAT(oem.nombre SEPARATOR'???') AS oemn")
                        ->from('productos AS p')
                        ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
                        ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
                        ->group_by("p.id_producto")
                        ->order_by("p.fecha_registro", "DESC")
                        ->limit(4)->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id_producto  = (int)$row->id_producto;
                $arrPerfiles[] = array(
                        'id'              => $id_producto,
                        'tipo_producto'   => html_escape($row->tipo_producto),
                        'sku'             => html_escape($row->sku),
                        'oem'             => html_escape(($row->oemn =='') ? $row->oem : str_replace("???", ",", $row->oemn)),
                        'path_img'        => !empty($row->path_img) ? 'media/products/'.html_escape($id_producto.'/'.$row->path_img) : 'media/default/producto.png',
                        'url_post'        => html_escape($row->url_post)
                    );
            }
            
            
            return $arrPerfiles;
        }else{
            return false;
        }
  }
   
  function get_max_users($users_in_wait=false){
       $this->db->select('count(*) AS total')->from('usuarios');
        if($users_in_wait){
            $this->db->where('estatus',2);
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return $row['total'];
        }else{
            return 0;
        }
  }
  
  function get_max_products(){
        return (int)$this->db->count_all('productos');
  }
}
