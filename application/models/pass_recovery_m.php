<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pass_recovery_m extends CI_Model {
  public function __construct(){parent::__construct();}
  
  function existe_cuenta($sEmail){
    if(empty($sEmail)){return true;}
    $query = $this->db->query('SELECT id_usuario FROM usuarios WHERE email=?',array($sEmail));
    return ($query->num_rows()) ? true : false;
  }
  
  function set_data($email,$pass){
        $arrDatos = array('password' => encriptar_cadena($pass));
        $this->db->where('email',$email);
        $this->db->update('usuarios',$arrDatos);
        return true;
   }
}