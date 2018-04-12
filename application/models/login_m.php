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


    function consulta_cantidad($email){
       $query = $this->db->query('SELECT count(*) as count FROM count_sessions a join usuarios b WHERE b.id_usuario = a.id_usuario and b.email = ? and id_tipo_usuario <> 1',array($email));

        if ($query->num_rows()>0){
            $row = $query->row_array();
            return $row['count'] ? $row['count'] : 0;
        }else{
            return 0;
        }

    }

    function consulta_sesion($email){
        $query = $this->db->query('SELECT min(id) as id,session_id as sesion  FROM count_sessions a join usuarios b WHERE b.id_usuario = a.id_usuario and b.email = ? and id_tipo_usuario <> 1',array($email));

        if ($query->num_rows()>0){
            $row = $query->row_array();
            $this->session->set_userdata('sesion',$row['sesion']) ;
        }

        return true;

    }


    function consulta_tipo($email){
        $query = $this->db->query('SELECT id_tipo_usuario as id FROM usuarios WHERE email = ?',array($email));

        if ($query->num_rows()>0){
            $row = $query->row_array();
            return $row['id'] ? $row['id'] : 0;
        }else{
            return 1;
        }

    }

    function destruirsesion($sesion){
        $query = $this->db->query('SELECT id FROM count_sessions a  WHERE session_id =  ? ',array($sesion));


        if ($query->num_rows()>0){
            $row = $query->row_array();
            $id= $row['id'];
            $this->db->delete('count_sessions',['id'=>$id]);
        }

        return true;
    }

    function contar($email){
        //obtenemos los datos por email
        $query = $this->db->query('SELECT count(id) as count  FROM count_sessions a join usuarios b WHERE b.id_usuario = a.id_usuario and b.email = ? and b.id_tipo_usuario <> 1',array($email));


        $query2 = $this->db->query('SELECT a.id_usuario  FROM usuarios a where a.email = ? ',array($email));
        if ($query2->num_rows()>0){
            $row1 = $query2->row_array();
            $id_usuario = $row1['id_usuario'];
        }

        $contador = 0;
        //si existen datos se actualiza , si no se crea el registro
        if ($query->num_rows()>0){
            $row = $query->row_array();

            $contador = $row['count'];

            //si no existen datos

            //si existen menos de 2 registros
            if($contador <= 2){
                $data = array(
                    'id_usuario'=>$id_usuario,
                    'session_id'=>$this->session->userdata('session_id'),
                    'fecha' =>  date('Y-m-d H:i:s')

                );
                $this->session->set_userdata('sesion',$data['session_id']);
                $this->db->insert('count_sessions', $data);

            }

            return true;
        }
    }

}