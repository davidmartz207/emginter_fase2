<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_manufacturer_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_marca';
                break;
            }
            case 2:{#
                $order_by = 'tabla.nombre';
                break;
            }
            case 3:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'tabla.nombre';
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

        $query = $this->db->select('tabla.id_marca AS id,tabla.nombre,
                                    tabla.estatus AS status,
                                    tabla.fecha_registro')
                        ->from('marcas AS tabla')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'nombre'          => html_escape(extract_string($row->nombre)),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('marcas');
  }
  
  function registrar($arrDatos){
        $this->db->insert('marcas',
              array(
                  'nombre' => $arrDatos['txtNombre']
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.id_marca AS id,tabla.nombre,
                           tabla.estatus')
                        ->from('marcas AS tabla')
                        ->where('id_marca',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'nombre'       => $row['nombre'],
                  'estatus'      => (int)$row['estatus']
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            'nombre'  => $arrData['txtNombre'],
            'estatus' => (int)$arrData['selEstatus']
        );
        $this->db->where('id_marca',$id);
        $this->db->update('marcas',$arrUPD);
        return true;
  }

  function delete($id){
        $this->db->where('id_marca',$id);
        $this->db->delete('marcas');
   }
}