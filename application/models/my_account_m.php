<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_account_m extends CI_Model{
  public function __construct(){
      parent::__construct();
      $this->id_usuario = get_id_usuario();
  }
  
  function get_data(){
        $query=$this->db->query('SELECT u.id_usuario,tu.nombre AS tipo_usuario,
                                        u.nombre,u.apellido,u.email,u.empresa,
                                        u.direccion_empresa,u.telefono,u.fax,
                                        u.path_img
                                 FROM usuarios AS u
                                 JOIN tipos_usuarios AS tu 
                                      ON tu.id_tipo_usuario = u.id_tipo_usuario  
                                 WHERE id_usuario=?',array($this->id_usuario));
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return array(
                    'tipo_usuario'      => html_escape($row['tipo_usuario']),
                    'nombre'            => html_escape($row['nombre']),
                    'apellido'          => html_escape($row['apellido']),
                    'email'             => html_escape($row['email']),
                    'empresa'           => html_escape($row['empresa']),
                    'direccion_empresa' => html_escape($row['direccion_empresa']),
                    'telefono'          => html_escape($row['telefono']),
                    'fax'               => html_escape($row['fax']),
                    'path_img'          => html_escape($row['path_img'])
                );
        }else{
            return FALSE;
        }
   }
   
   function set_data($arrDatos){
       $arrUPD = array(
            'nombre'            => $arrDatos['txtNombre'],
            'apellido'          => $arrDatos['txtApellido'],
            'email'             => $arrDatos['txtEmail'],
            'empresa'           => $arrDatos['txtCompany'],
            'direccion_empresa' => $arrDatos['txtAddressCompany'],
            'telefono'          => $arrDatos['txtPhoneNumber'],
            'fax'               => $arrDatos['txtFax'],
            'path_img'          => $arrDatos['imagen']
        );

        if($arrDatos['txtPassword_new']){
            $arrUPD['password'] = encriptar_cadena($arrDatos['txtPassword_new']);
        }
        $this->db->where('id_usuario',$this->id_usuario);
        $this->db->update('usuarios',$arrUPD);
        return true;
   }
   
   function existe_email($email){
        $query = $this->db->select('id_usuario')->from('usuarios')
                          ->where('email',$email)
                          ->where('id_usuario <>',$this->id_usuario)->get();
        return ($query->num_rows()) ? true : false;
  }
  
  function es_valido_password($cadena){
    $this->db->select('password')->from('usuarios')->where('id_usuario',$this->id_usuario);
    $query = $this->db->get();
    //echo $this->db->last_query();exit;
    if($query->num_rows()){
        $row = $query->row_array();
        return $row['password'] == encriptar_cadena($cadena) ? TRUE : FALSE;
    }else{
        return FALSE;
    }
  }
}