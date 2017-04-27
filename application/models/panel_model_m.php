<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_model_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_modelo';
                break;
            }
            case 2:{#marca
                $order_by = 'm.nombre';
                break;
            }
            case 3:{#
                $order_by = 'tabla.nombre';
                break;
            }
            case 4:{#id
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

        $query = $this->db->select('tabla.id_modelo AS id,tabla.nombre AS modelo,
                                    tabla.estatus AS status,
                                    m.nombre AS marca')
                        ->from('modelos AS tabla')
                        ->join('marcas AS m','m.id_marca=tabla.id_marca')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'marca'           => html_escape(extract_string($row->marca)),
                    'modelo'          => html_escape(extract_string($row->modelo)),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('modelos');
  }
  
  function registrar($arrDatos){
        $this->db->insert('modelos',
              array(
                  'id_marca' => $arrDatos['selMarcas'],
                  'nombre'   => $arrDatos['txtNombre']
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.id_modelo AS id,tabla.id_marca,
                           tabla.nombre,tabla.estatus')
                        ->from('modelos AS tabla')
                        ->where('id_modelo',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'id_modelo'    => (int)$row['id_marca'],
                  'modelo'       => $row['nombre'],
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
            'id_marca'  => $arrData['selMarcas'],
            'nombre'    => $arrData['txtNombre'],
            'estatus'   => (int)$arrData['selEstatus']
        );
        $this->db->where('id_modelo',$id);
        $this->db->update('modelos',$arrUPD);
        return true;
  }

  function delete($id){
        $this->db->where('id_modelo',$id);
        $this->db->delete('modelos');
   }
}