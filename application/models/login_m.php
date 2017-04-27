<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_m extends CI_Model {
  public function __construct(){parent::__construct();}
  
  function no_existe_cuenta($sEmail,$sPass='',$opc=1){
    if(empty($sEmail) or empty($opc)){return true;}
    if($opc==1){
        $query = $this->db->query('SELECT id_usuario FROM usuarios WHERE email=?',array($sEmail));
    }elseif($opc==2 and !empty($sPass)){
        $sPass = encriptar_cadena($sPass);
        $query = $this->db->query('SELECT email,password FROM usuarios 
                                   WHERE email=? AND password=?',array($sEmail,$sPass));            
    }
    return ($query->num_rows()) ? false : true;
  }
  
  function account_for_approving($sEmail){
        if(empty($sEmail)){return TRUE;}

        $query = $this->db->query('SELECT estatus 
                                   FROM usuarios 
                                   WHERE email=?',array($sEmail));
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return $row['estatus']==2 ? TRUE : FALSE;
        }else{
            return TRUE;
        }
  }
}