<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class panel_applications_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
   
  function get_datos($id_producto,$limit,$desde,$order_by='',$selUpDown=''){
        $order_by    = (int)$order_by;        
        switch($order_by){
            case 1:{#id_aplicaciones_producto
                $order_by = 'a.id_aplicaciones_producto';
                break;
            }
            case 2:{#marca
                $order_by = 'a.id_marca';
                break;
            }
            case 3:{#id_tipo_motor
                $order_by = 'a.id_tipo_motor';
                break;
            }
            case 4:{#years
                $order_by = 'a.years';
                break;
            }
            case 5:{#estatus
                $order_by = 'ca.estatus';
                break;
            }
            case 6:{#
                $order_by = 'a.fecha_registro';
                break;
            }
            default:{
                $order_by = 'a.id_aplicaciones_producto';
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
        $this->db->select('a.id_aplicaciones_producto AS id,a.years,a.ref_id_modelo,p.sku,
                           m.nombre AS marca,tm.nombre_en AS tipo_motor,
                           a.estatus AS estatus,a.fecha_registro')
                        ->from('aplicaciones_productos AS a')
                        ->join('productos AS p','p.id_producto=a.id_producto')
                        ->join('tipos_motores AS tm','tm.id_tipo_motor=a.id_tipo_motor')
                        ->join('marcas AS m','m.id_marca=a.id_marca')
                        ->where('a.id_producto',$id_producto);
        $this->db->order_by($order_by,$selUpDown)->limit($limit,$desde);   
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrDatos[] = array(
                        'id'                => $id,
                        'sku'               => $row->sku,
                        'years'             => html_escape(extract_string($row->years,30)),
                        'marca'             => html_escape($row->marca),
                        'modelo'            => get_modelo($row->ref_id_modelo),
                        'tipo_motor'        => html_escape($row->tipo_motor),
                        'estatus'           => $row->estatus ? 'Activo' : 'Inactivo',
                        'fecha_registro'    => html_escape(convertir_fecha($row->fecha_registro,'timestamp'))
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
   
  function get_rows($id_producto){
      $query=$this->db->query('SELECT count(id_aplicaciones_producto) AS total
                                 FROM aplicaciones_productos 
                                 WHERE id_producto=?',array($id_producto));
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return (int)$row['total'];
        }else{
            return 0;
        }
  }

  function registrar($arrDatos){
        $this->db->insert('aplicaciones_productos',
              array(
                  'id_producto'        => $arrDatos['id_producto'],
                  'id_marca'           => $arrDatos['selMarcas'],
                  'ref_id_modelo'      => $arrDatos['selModelos'],
                  'id_tipo_motor'      => $arrDatos['selTiposMotores'],
                  'years'              => $arrDatos['txtYears']
              ));
        return $this->db->insert_id();
   }

   //
   function get_data($id){
       $this->db->select('a.id_aplicaciones_producto AS id,a.years,
                          a.ref_id_modelo,p.sku,a.id_marca,a.id_tipo_motor,
                          a.estatus AS estatus,a.fecha_registro,
                          p.id_producto,p.url_post')
                        ->from('aplicaciones_productos AS a')
                        ->join('productos AS p','p.id_producto=a.id_producto')
                        ->join('tipos_motores AS tm','tm.id_tipo_motor=a.id_tipo_motor')
                        ->join('marcas AS m','m.id_marca=a.id_marca')
                        ->where('a.id_aplicaciones_producto',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                    'id'                => $id,
                    'id_producto'       => $row['id_producto'],
                    'url'               => $row['url_post'],
                    'sku'               => $row['sku'],
                    'id_marca'          => (int)$row['id_marca'],
                    'id_modelo'         => (int)$row['ref_id_modelo'],
                    'id_tipo_motor'     => (int)$row['id_tipo_motor'],
                    'years'             => $row['years'],
                    'estatus'           => (int)$row['estatus']
                );
        }else{
            return false;
        }
  }

  // hace el update de los nuevos datos
  function set_data($arrData){
       $id = (int)$arrData['id'];

       // relacion campo_tabla = valor
       // actualizamos la cancion
       $arrUPD = array(
            'id_marca'           => $arrData['selMarcas'],
            'ref_id_modelo'      => $arrData['selModelos'],
            'id_tipo_motor'      => $arrData['selTiposMotores'],
            'years'              => $arrData['txtYears'],
            'estatus'            => (int)$arrData['estatus']
        );
        $this->db->where('id_aplicaciones_producto',$id);
        $this->db->update('aplicaciones_productos',$arrUPD);
        return TRUE;
  }

  function delete($id){
        $this->db->where('id_aplicaciones_producto',$id);
        $this->db->delete('aplicaciones_productos');
   }
   
   # --------------------------------------------------------------------------
    # admin search user control
    #---------------------------------------------------------------------------
    function set_field($campo,$valor){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        if($this->if_exists_field($campo)){
            $db_expor_import->where('campo',$campo);
            $db_expor_import->where('hash',$this->get_hash());
            $db_expor_import->update('admin_search',array('valor' => $valor));
        }else{
            $db_expor_import->insert('admin_search',array(
                'campo' => $campo,
                'valor' => $valor,
                'hash'  => $this->get_hash()
            ));
        }
        $this->delete_fields();
        //exit($db_expor_import->last_query());
    }
    
    function if_exists_field($campo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('valor')->from('admin_search')
                ->where('campo',$campo)
                ->where('hash',$this->get_hash());
        $query = $db_expor_import->get();
        //exit($db_expor_import->last_query());
        if ($query->num_rows()>0){
             return TRUE;
         }else{
             return FALSE;
         }
    }
    
    function get_field($campo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('valor')->from('admin_search')
                ->where('campo',$campo)
                ->where('hash',$this->get_hash());
        $query = $db_expor_import->get();
        //exit($db_expor_import->last_query());
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
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->where('hash',$this->get_hash());
        $db_expor_import->where('WEEK(fecha_registro) <','WEEK(curdate())');
        $db_expor_import->delete('admin_search');
    }
}