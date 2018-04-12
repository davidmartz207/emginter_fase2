<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_catalogs_m extends CI_Model{
  public function __construct(){
      parent::__construct();
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_catalogo';
                break;
            }
            case 2:{#
                $order_by = 'tabla.nombre_en';
                break;
            }
            case 3:{#oem
                $order_by = 'tabla.nombre_es';
                break;
            }
            case 4:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'tabla.nombre_en';
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

        $query = $this->db->select('tabla.linea as linea,tabla.id_catalogo AS id,tabla.nombre_en,
                                    tabla.nombre_es,tabla.estatus AS status,
                                    tabla.fecha_registro')
                        ->from('catalogos_productos AS tabla')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'linea'       => html_escape(extract_string($row->linea)),
                    'nombre_en'       => html_escape(extract_string($row->nombre_en)),
                    'nombre_es'       => html_escape(extract_string($row->nombre_es)),
                    'estatus'         => $row->status==1 ? 'Activo' : 'Inactivo'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('catalogos_productos');
  }
  
  function registrar($arrDatos){
        $this->db->insert('catalogos_productos',
              array(
                  'linea'           => $arrDatos['selLinea'],
                  'nombre_en'       => $arrDatos['txtNombre_en'],
                  'nombre_es'       => $arrDatos['txtNombre_es'],
                  'path_img'        => isset($arrDatos['Imagen']) ? $arrDatos['Imagen'] : '',
                  'ruta_interna'    => $arrDatos['txtLinkInterno'],
                  'ruta_externa'    => $arrDatos['txtLinkExterno']
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.linea AS linea,tabla.id_catalogo AS id,tabla.nombre_en,
                           tabla.nombre_es,tabla.ruta_interna,tabla.ruta_externa,
                           path_img,tabla.estatus')
                        ->from('catalogos_productos AS tabla')
                        ->where('id_catalogo',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'linea'           => $row['linea'],
                  'nombre_en'       => $row['nombre_en'],
                  'nombre_es'       => $row['nombre_es'],
                  'path_img'        => $row['path_img'] ? 'media/catalogos/'.$id.'/'.$row['path_img'] : 'media/default/producto.png',
                  'ruta_interna'    => $row['ruta_interna'],
                  'ruta_externa'    => $row['ruta_externa'],
                  'estatus'         => (int)$row['estatus']
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relacion campo_tabla = valor
       $arrUPD = array(
            'linea'           => $arrData['selLinea'],
            'nombre_en'       => $arrData['txtNombre_en'],
            'nombre_es'       => $arrData['txtNombre_es'],
            'ruta_externa'    => $arrData['txtLinkExterno'],
            'estatus'         => (int)$arrData['selEstatus']
        );
       
        // le pasamos los datos e la nueva imagen, si fue enviada
        if(!empty($arrData['Imagen']) and !empty($arrData['imagen_actual'])){
            // obtenemos los datos de la imagen anterior y la eliminamos junto 
            // con las resoluciones
            $ruta = './media/catalogos/';
            if(file_exists($arrData['imagen_actual'])){
                  $arrFile = explode('.', $arrData['imagen_actual']);
                  if(is_array($arrFile)){
                        // obtenemos la extension de la imagen
                        $ext      = end($arrFile);
                        // extraemos y almacenamos la ruta sin la extension
                        $imagenes = array_shift(explode(('.'.$ext),($arrData['imagen_actual']))); 
                        // eliminamos las imagenes usando comodin
                        foreach (glob($imagenes.'*') as $nombre_archivo){
                            // eliminamos todas las imagenes menos la 
                            // que se acaba de subir
                            if($nombre_archivo <> ($ruta.$id.'/'.$arrData['Imagen']) and 
                               $nombre_archivo <> 'media/default/producto.png'){
                                unlink($nombre_archivo);
                            }
                        }
                  }
            }
            // nueva imagen a actualizar
            $arrUPD['path_img'] = $arrData['Imagen'];
        }

        # actualizamos el archivo si se subio nuevamente
        if(isset($arrData['archivo']) and !empty($arrData['archivo'])){
            $arrUPD['ruta_interna'] = $arrData['archivo'];
        }
        $this->db->where('id_catalogo',$id);
        $this->db->update('catalogos_productos',$arrUPD);
        return true;
  }
  
  function set_image($id,$image){
        $arrUPD['path_img'] = $image;
        $this->db->where('id_catalogo',$id);
        $this->db->update('catalogos_productos',$arrUPD);
        return true;
  }
  
  function set_file($id,$file){
        $arrUPD['ruta_interna'] = $file;
        $this->db->where('id_catalogo',$id);
        $this->db->update('catalogos_productos',$arrUPD);
        return TRUE;
  }

  function delete($id){
        $this->db->where('id_catalogo',$id);
        $this->db->delete('catalogos_productos');
        
        // eliminamos los archivos
        delete_dir_and_file('./downloads/catalog/'.$id.'/');
   }

    function get_lineas(){
        $emgweb_db = $this->load->database('default', TRUE);

        $query = $emgweb_db->get("lineas");
        if ($query->num_rows()>0){
            return ($query->result());
        }else{
            return '';
        }

    }
}