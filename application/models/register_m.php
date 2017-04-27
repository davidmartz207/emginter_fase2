<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }

  function existe_email($email){
        $query = $this->db ->select('id_usuario')->from('usuarios')->where('email',$email)->get();
        return ($query->num_rows()) ? true : false;
  }
      
  function registrar($arrDatos){
        $txtNombre         = $arrDatos['txtNombre'];
        $txtApellido       = $arrDatos['txtApellido'];
        $txtEmail          = $arrDatos['txtEmail'];
        $txtPassword       = $arrDatos['txtPassword'];
        $txtCompany        = $arrDatos['txtCompany'];
        $txtAddressCompany = $arrDatos['txtAddressCompany'];
        $txtPhoneNumber    = $arrDatos['txtPhoneNumber'];
        $txtFax            = $arrDatos['txtFax'];
        $this->db->insert('usuarios',
              array(
                  'nombre'            => $txtNombre,
                  'apellido'          => $txtApellido,
                  'email'             => $txtEmail,
                  'empresa'           => $txtCompany,
                  'direccion_empresa' => $txtAddressCompany,
                  'telefono'          => $txtPhoneNumber,
                  'fax'               => $txtFax,
                  'password'          => encriptar_cadena($txtPassword),
                  'id_tipo_usuario'   => 3, #cliente 
                  'estatus'           => 2  #por aprobar
              ));
        return $this->db->insert_id();
   }
}