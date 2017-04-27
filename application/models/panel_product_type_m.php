<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_product_type_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_tipo_producto';
                break;
            }
            case 2:{#
                $order_by = 'tabla.nombre_en';
                break;
            }
            case 3:{#oem
                $order_by = 'tabla.nombre_es';
                break;
            }
            case 4:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'tabla.nombre_en';
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

        $query = $this->db->select('tabla.id_tipo_producto AS id,tabla.nombre_en,
                                    tabla.nombre_es,tabla.estatus AS status,
                                    tabla.fecha_registro')
                        ->from('tipos_productos AS tabla')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'nombre_en'       => html_escape(extract_string($row->nombre_en)),
                    'nombre_es'       => html_escape(extract_string($row->nombre_es)),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('tipos_productos');
  }
  
  function registrar($arrDatos){
        $this->db->insert('tipos_productos',
              array(
                  'nombre_en'       => $arrDatos['txtNombre_en'],
                  'nombre_es'       => $arrDatos['txtNombre_es'],
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.id_tipo_producto AS id,tabla.nombre_en,
                           tabla.nombre_es,tabla.estatus,tabla.path_img')
                        ->from('tipos_productos AS tabla')
                        ->where('id_tipo_producto',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'nombre_en'       => $row['nombre_en'],
                  'nombre_es'       => $row['nombre_es'],
                'estatus'         => (int)$row['estatus'],
                  'path_img'         => $row['path_img'] ? 'media/product_types/pt'.$id.'/'.html_escape($row['path_img']) : 'media/default/producto.png'
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            'nombre_en'       => $arrData['txtNombre_en'],
            'nombre_es'       => $arrData['txtNombre_es'],
            'estatus'         => (int)$arrData['selEstatus']
        );
        $this->db->where('id_tipo_producto',$id);
        $this->db->update('tipos_productos',$arrUPD);
        return true;
  }

  function delete($id){
        $this->db->where('id_tipo_producto',$id);
        $this->db->delete('tipos_productos');
   }

    function set_image($id,$image){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $arrUPD['path_img'] = $image;
        $this->db->where('id_tipo_producto',$id);
        $this->db->update('tipos_productos',$arrUPD);
        return true;
    }
}