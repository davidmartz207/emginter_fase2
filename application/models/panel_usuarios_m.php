<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_usuarios_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_usuarios($search_estatus='',$limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'u.id_usuario';
                break;
            }
            case 2:{#tipo de usuario
                $order_by = 'tu.nombre';
                break;
            }
            case 3:{#
                $order_by = 'u.nombre';
                break;
            }
            case 4:{#
                $order_by = 'u.apellido';
                break;
            }            
            case 5:{#
                $order_by = 'u.empresa';
                break;
            }
            case 6:{#
                $order_by = 'p.estatus';
                break;
            }
            default:{
                $order_by = 'u.id_usuario';
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

        $this->db->select('u.id_usuario AS id,tu.nombre AS tipo_usuario,
                                    u.nombre AS name,u.apellido,u.email,u.empresa,
                                    u.direccion_empresa,u.telefono,u.fax,
                                    u.path_img,u.estatus AS p_estatus')
                        ->from('usuarios AS u')
                        ->join('tipos_usuarios AS tu','tu.id_tipo_usuario=u.id_tipo_usuario');
        
        if($search_estatus){
            $this->db->where('u.estatus',2);
        }
        
        $this->db->order_by($order_by,$selUpDown)->limit($limit,$desde);
        
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id_usuario  = (int)$row->id;
                
                // estatus
                if($row->p_estatus==0){
                     $estatus = 'Inactivo';
                }elseif($row->p_estatus==1){
                    $estatus = 'Activo';
                }elseif($row->p_estatus==2){
                     $estatus = 'Por Aprobar';
                }
                $arrResult[] = array(
                    'id'              => $id_usuario,
                    'tipo_usuario'    => html_escape($row->tipo_usuario),
                    'nombre'          => html_escape($row->name),
                    'apellido'        => html_escape($row->apellido),
                    'email'           => html_escape($row->email),
                    'id_estatus'      => (int)$row->p_estatus,
                    'estatus'         => $estatus
                );
            }
            return $arrResult;
        }else{
            return FALSE;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('usuarios');
  }
      
  function registrar($arrDatos){
        $selUsuarios       = $arrDatos['selTiposUsuarios'];
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
                  'id_tipo_usuario'   => $selUsuarios,
                  'nombre'            => $txtNombre,
                  'apellido'          => $txtApellido,
                  'email'             => $txtEmail,
                  'empresa'           => $txtCompany,
                  'direccion_empresa' => $txtAddressCompany,
                  'telefono'          => $txtPhoneNumber,
                  'fax'               => $txtFax,
                  'password'          => encriptar_cadena($txtPassword),
                  'estatus'           => 2  #por aprobar
              ));
        return $this->db->insert_id();
   }
  
  function get_data($id){
        $query=$this->db->query('SELECT u.id_usuario,u.id_tipo_usuario,
                                        u.nombre,u.apellido,u.email,u.empresa,
                                        u.direccion_empresa,u.telefono,u.fax,
                                        u.path_img,u.estatus AS status
                                 FROM usuarios AS u  
                                 WHERE id_usuario=?',array($id));
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return array(
                    'id_tipo_usuario'   => $row['id_tipo_usuario'],
                    'nombre'            => $row['nombre'],
                    'apellido'          => $row['apellido'],
                    'email'             => $row['email'],
                    'empresa'           => $row['empresa'],
                    'direccion_empresa' => $row['direccion_empresa'],
                    'telefono'          => $row['telefono'],
                    'fax'               => $row['fax'],
                    'path_img'          => $row['path_img'],
                    'estatus'           => $row['status']
                );
        }else{
            return FALSE;
        }
  }
 
  function set_data($arrDatos){
       $id = $arrDatos['id'];
       $id = (int)$id;
       $arrUPD = array(
            'id_tipo_usuario'   => $arrDatos['selTiposUsuarios'],
            'nombre'            => $arrDatos['txtNombre'],
            'apellido'          => $arrDatos['txtApellido'],
            'email'             => $arrDatos['txtEmail'],
            'empresa'           => $arrDatos['txtCompany'],
            'direccion_empresa' => $arrDatos['txtAddressCompany'],
            'telefono'          => $arrDatos['txtPhoneNumber'],
            'fax'               => $arrDatos['txtFax'],
            'path_img'          => $arrDatos['imagen'],
            'estatus'           => $arrDatos['selEstatus']
        );

        if($arrDatos['txtPassword']){
            $arrUPD['password'] = encriptar_cadena($arrDatos['txtPassword']);
        }
        
        #
        if($arrDatos['hddEstatus']==2 and $arrDatos['selEstatus']==1){
            $EMG_email_contacto = get_config_db('email_contact');
            $fecha              = date('d/m/Y H:i:s');
            $mensaje_footer     = '<br><hr><br>
                                    Thank you / Gracias, 
                                    <br>EMG International
                                    <br><a target="_blank" href="'.site_url('login').'">Emginter.com</a>';
            
            # ------------------------------------------------------------------
            # email al administrador -------------------------------------------
            # ------------------------------------------------------------------
            $mensaje = 'Se ha aprobado la cuenta <b>'.$arrDatos['txtEmail'].'</b> el '
                       .$fecha.' por un administrador';
            $mensaje .= $mensaje_footer;
            send_email($EMG_email_contacto,'EMG International - Se ha aprobado una cuenta de usuario'
                       ,$mensaje,'EMG International',$EMG_email_contacto);

            # ------------------------------------------------------------------
            # email al usuario -------------------------------------------------
            # ------------------------------------------------------------------
            $mensaje  = get_config_db('mensaje_registro_aprobacion');
            $mensaje .= $mensaje_footer;
            send_email($arrDatos['txtEmail'],'Your Account at EMG International / Su cuenta en EMG International'
                       ,$mensaje,'EMG International',$EMG_email_contacto);
        }
        
        $this->db->where('id_usuario',$id);
        $this->db->update('usuarios',$arrUPD);
        return TRUE;
  }
  
  function enabled_user($id){
        $id = (int)$id;

        $arrUPD['estatus'] = 1;
        $this->db->where('id_usuario',$id);
        $this->db->update('usuarios',$arrUPD);
        return TRUE;
   }
   
  function set_image($id,$image){
        $arrUPD['path_img'] = $image;
        $this->db->where('id_usuario',$id);
        $this->db->update('usuarios',$arrUPD);
        return TRUE;
  }

  function delete($id){
      
        if($id == 1){
            return FALSE;
        }

        # del usuarios
        $this->db->where('id_usuario',$id);
        $this->db->delete('usuarios');

        // eliminamos las imagenes
        delete_dir_and_file('./media/usuarios/'.$id.'/');
   }
   
   public function get_tipos_usuarios(){
        $query = $this->db->query('SELECT id_tipo_usuario AS id,nombre
                                   FROM tipos_usuarios 
                                   WHERE estatus=1
                                   ORDER BY nombre ASC');
        if($query->num_rows()>0){            
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    'id'     => (int)$row->id,
                    'nombre' => htmlspecialchars($row->nombre,ENT_QUOTES)
                );
            }
            return $arrDatos;
        }else{
            return FALSE;
        }
   }
    
   function existe_email($email,$id=''){
        $this->db->select('id_usuario')
                          ->from('usuarios')
                          ->where('email',$email);
        if(!empty($id)){
            $this->db->where('id_usuario <>',$id);
        }
        $query = $this->db->get();
        return ($query->num_rows()) ? true : false;
  }
}