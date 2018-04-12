<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_banners_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_image_link';
                break;
            }
            case 2:{#
                $order_by = 'i.nombre';
                break;
            }
            case 3:{#oem
                $order_by = 'tabla.nombre';
                break;
            }
            case 4:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'i.nombre';
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

        $query = $this->db->select('tabla.id_image_link AS id,tabla.nombre AS nombre_imagen,
                                    tabla.path_img,tabla.enlace,tabla.estatus AS status,
                                    tabla.fecha_registro,i.nombre AS idioma')
                        ->from('image_link AS tabla')
                        ->join('idiomas AS i','i.id_idioma=tabla.id_idioma')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'idioma'          => html_escape($row->idioma),
                    'nombre'          => html_escape($row->nombre_imagen),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('image_link');
  }
  
  function registrar($arrDatos){
        $this->db->insert('image_link',
              array(
                  'id_idioma'       => $arrDatos['selIdiomas'],
                  'nombre'          => $arrDatos['txtNombre'],
                  'enlace'          => $arrDatos['txtEnlace']
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.id_image_link AS id,tabla.nombre,
                           tabla.path_img,tabla.enlace,tabla.estatus,
                           tabla.fecha_registro,tabla.id_idioma')
                        ->from('image_link AS tabla')
                        ->where('id_image_link',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'id_idioma'       => $row['id_idioma'],
                  'nombre'          => $row['nombre'],
                  'path_img'        => $row['path_img'],
                  'enlace'          => $row['enlace'],
                  'estatus'         => (int)$row['estatus']
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            'id_idioma'       => $arrData['selIdiomas'],
            'nombre'          => $arrData['txtNombre'],
            'enlace'          => $arrData['txtEnlace'],
            'estatus'         => (int)$arrData['selEstatus']
        );
       
        // le pasamos los datos e la nueva imagen, si fue enviada
        if(!empty($arrData['Imagen']) and !empty($arrData['imagen_actual'])){
            // obtenemos los datos de la imagen anterior y la eliminamos junto 
            // con las resoluciones
            $ruta = './media/banners/';
            if(file_exists($ruta.$id.'/'.$arrData['imagen_actual'])){
                  $arrFile = explode('.', $arrData['imagen_actual']);
                  if(is_array($arrFile)){
                        // obtenemos la extension de la imagen
                        $ext      = end($arrFile);
                        // extraemos y almacenamos la ruta sin la extension
                        $imagenes = array_shift(explode(('.'.$ext),($ruta.$id.'/'.$arrData['imagen_actual']))); 
                        // eliminamos las imagenes usando comodin
                        foreach (glob($imagenes.'*') as $nombre_archivo){
                            // eliminamos todas las imagenes menos la 
                            // que se acaba de subir
                            if($nombre_archivo <> ($ruta.$id.'/'.$arrData['Imagen'])){
                                unlink($nombre_archivo);
                            }
                        }
                  }
            }
            // nueva imagen a actualizar
            $arrUPD['path_img'] = $arrData['Imagen'];
        }

        $this->db->where('id_image_link',$id);
        $this->db->update('image_link',$arrUPD);
        return true;
  }
   
  function set_image($id,$image){
        $arrUPD['path_img'] = $image;
        $this->db->where('id_image_link',$id);
        $this->db->update('image_link',$arrUPD);
        return true;
  }

  function delete($id){
        $this->db->where('id_image_link',$id);
        $this->db->delete('image_link');
       
        // eliminamos las imagenes
        delete_dir_and_file('./media/banners/'.$id.'/');
   }
}