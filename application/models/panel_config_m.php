<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_config_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_idioma';
                break;
            }
            case 2:{#
                $order_by = 'tabla.num_row_table';
                break;
            }
            case 3:{#
                $order_by = 'tabla.telefono';
                break;
            }
            case 4:{#
                $order_by = 'tabla.email_contact';
                break;
            }
            case 5:{#
                $order_by = 'tabla.direccion';
                break;
            }
            case 6:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'tabla.id_idioma';
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

        $query = $this->db->select('tabla.id_idioma,tabla.num_row_table,tabla.telefono,tabla.fax,
                                    tabla.email_contact,tabla.email_public,tabla.direccion,
                                    tabla.estatus AS status,tabla.fecha_registro,i.nombre AS idioma')
                        ->from('configuraciones AS tabla')
                        ->join('idiomas AS i','i.id_idioma=tabla.id_idioma')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $arrResult[] = array(
                    'id'              => (int)$row->id_idioma,
                    'idioma'          => html_escape(extract_string($row->idioma)),
                    'num_row_table'   => (int)$row->num_row_table,
                    'telefono'        => html_escape(extract_string($row->telefono)),
                    'fax'             => html_escape(extract_string($row->fax)),
                    'email_contact'   => html_escape(extract_string($row->email_contact)),
                    'email_public'    => html_escape(extract_string($row->email_public)),
                    'direccion'       => extract_string(strip_tags($row->direccion,20)),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('configuraciones');
  }
  
  //
   function get_data($id){
        $query = $this->db->select('tabla.id_idioma,tabla.num_row_table,tabla.telefono,tabla.fax,
                                    tabla.email_contact,tabla.email_public,tabla.direccion,
                                    tabla.estatus AS status,i.nombre AS idioma,
                                    tabla.mensaje_registro,tabla.mensaje_registro_aprobacion,
                                    texto_productos,texto_guia_productos,
                                    texto_contacto,horarios_jornada_laboral')
                        ->from('configuraciones AS tabla')
                        ->join('idiomas AS i','i.id_idioma=tabla.id_idioma')
                        ->where('tabla.id_idioma',$id)
                        ->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'idioma'                   => $row['idioma'],
                  'telefono'                 => $row['telefono'],
                  'fax'                      => $row['fax'],
                  'email_contact'            => $row['email_contact'],
                  'email_public'             => $row['email_public'],
                  'direccion'                => $row['direccion'],
                  'mensaje_registro'         => $row['mensaje_registro'],
                  'msj_registro_aprobacion'  => $row['mensaje_registro_aprobacion'],
                  'texto_productos'          => $row['texto_productos'],
                  'texto_guia_productos'     => $row['texto_guia_productos'],
                  'texto_contacto'           => $row['texto_contacto'],
                  'horarios_jornada_laboral' => $row['horarios_jornada_laboral'],
                  'estatus'                  => (int)$row['status']
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       //echo "SET SATA <pre>",print_r($arrData),"</pre>";exit;
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            //'num_row_table'   => (int)$arrData['num_row_table'],
            'telefono'                      => $arrData['txtTelefono'],
            'fax'                           => $arrData['txtFax'],
            'email_contact'                 => $arrData['txtEmail_contact'],
            'email_public'                  => $arrData['txtEmail_public'],
            'direccion'                     => $arrData['txtaDireccion'],
            'mensaje_registro'              => $arrData['txtaMsjRegistro'],
            'mensaje_registro_aprobacion'   => $arrData['txtaMsjRegistroAprobacion'],
            'texto_productos'               => $arrData['txtaTextoProductos'],
            'texto_guia_productos'          => $arrData['txtaTextoGuiaProductos'],
            'texto_contacto'                => $arrData['txtaTextoContacto'],
            'horarios_jornada_laboral'      => $arrData['txtaHorariosJornadaLaboral'],
            'estatus'                       => (int)$arrData['selEstatus']
        );
        $this->db->where('id_idioma',$id);
        $this->db->update('configuraciones',$arrUPD);
        return true;
  }
}