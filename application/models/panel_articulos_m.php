<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_articulos_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }

  function get_datos($restringir,$limit,$desde,$order_by='',$selUpDown=''){
      
        $order_by = (int)$order_by;	
        switch($order_by){
            case 1:{#id
                $order_by = 'c.id_contenido';
                break;
            }
            case 2:{#
                $order_by = 'c.titulo_en';
                break;
            }
            case 3:{#
                $order_by = 'c.titulo_es';
                break;
            }
            case 4:{#usuario
                $order_by = 'u.nombre';
                break;
            }
            case 5:{#estatus
                $order_by = 'c.estatus';
                break;
            }
            case 6:{#
                $order_by = 'c.fecha_registro';
                break;
            }
            default:{
                $order_by = 'c.fecha_registro';
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
                $selUpDown = 'DESC';
            }
        }
        $this->db->select('c.id_contenido AS id,c.titulo_en,c.titulo_es,c.url_post,
                           u.nombre AS user_first_name,u.apellido AS user_last_mame,
                           c.estatus AS estatus,c.fecha_registro,
                           tc.nombre AS tipo_contenido')
                        ->from('contenidos AS c')
                        ->join('usuarios AS u','u.id_usuario=c.id_usuario')
                        ->join('tipos_contenidos AS tc','tc.id_tipo_contenido=c.id_tipo_contenido');
        if($restringir){
            $this->db->where('c.id_usuario',$restringir);
        }
        $this->db->order_by($order_by,$selUpDown)->limit($limit,$desde);   
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrDatos[] = array(
                        'id'              => $id,
                        'tipo_contenido'  => html_escape(extract_string($row->tipo_contenido)),
                        'usuario'         => html_escape(extract_string($row->user_first_name.' '.$row->user_last_mame)),
                        'titulo_en'       => html_escape(extract_string($row->titulo_en)),
                        'titulo_es'       => html_escape(extract_string($row->titulo_es)),
                        'estatus'         => $row->estatus ? 'Activo' : 'Inactivo',
                        'fecha_registro'  => html_escape(convertir_fecha($row->fecha_registro,'timestamp')),
                        'link'            => site_url('content').'/'.$row->url_post
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }

  function get_rows(){
        return (int)$this->db->count_all('contenidos');
   }

   function registrar($arrDatos){
      $selTiposContenidos   = $arrDatos['selTiposContenidos'];
      $txtAsunto_en         = trim($arrDatos['txtAsunto_en']);
      $txtaDescripcion_en   = trim($arrDatos['txtaDescripcion_en']);
      $txtAsunto_es         = trim($arrDatos['txtAsunto_es']);
      $txtaDescripcion_es   = trim($arrDatos['txtaDescripcion_es']);
      $this->db->insert('contenidos',
              array(
                  'id_tipo_contenido' => $selTiposContenidos,
                  'url_post'          => generar_url_contenido($txtAsunto_en),
                  'id_usuario'        => get_id_usuario(),
                  'titulo_en'         => $txtAsunto_en,
                  'contenido_en'      => $txtaDescripcion_en,
                  'titulo_es'         => $txtAsunto_es,
                  'contenido_es'      => $txtaDescripcion_es
              ));
      return $this->db->insert_id();
   }

   function set_image($id,$image){
        $arrUPD['path_img'] = $image;
        $this->db->where('id_contenido',$id);
        $this->db->update('contenidos',$arrUPD);
        return true;
   }

   function get_data($id){
        $query = $this->db->select('c.id_contenido AS id,c.url_post,
                                    c.titulo_en,c.contenido_en,
                                    c.titulo_es,c.contenido_es,
                                    c.path_img,c.estatus AS id_estatus')
                            ->from('contenidos AS c')
                            ->where('c.id_contenido',$id)
                            ->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return array(
                    'id'             => $id,
                    'url_post'       => site_url('content').'/'.$row['url_post'],
                    'id_estatus'     => $row['id_estatus'],
                    'titulo_en'      => $row['titulo_en'],
                    'descripcion_en' => $row['contenido_en'],
                    'titulo_es'      => $row['titulo_es'],
                    'descripcion_es' => $row['contenido_es'],
                    'path_img'       => $row['path_img'] ? $id.'/'.$row['path_img'] : '',
                );
        }else{
            return false;
        }
  }
  
  function set_data($arrData){
       $id                 = (int)$arrData['id'];
       $txtAsunto_en       = $arrData['txtAsunto_en'];
       $txtaDescripcion_en = $arrData['txtaDescripcion_en'];
       $txtAsunto_es       = $arrData['txtAsunto_es'];
       $txtaDescripcion_es = $arrData['txtaDescripcion_es'];
       $id_estatus         = (int)$arrData['selEstatus'];       
       $arrUPD             = array(
            'titulo_en'        => $txtAsunto_en,
            'contenido_en'     => $txtaDescripcion_en,
            'titulo_es'        => $txtAsunto_es,
            'contenido_es'     => $txtaDescripcion_es,
            'estatus'          => $id_estatus
        );
       
       // le pasamos los datos e la nueva imagen, si fue enviada
        if(!empty($arrData['Imagen']) and !empty($arrData['imagen_actual'])){
            // obtenemos los datos de la imagen anterior y la eliminamos junto 
            // con las resoluciones
            $ruta = './media/contenidos/';
            if(file_exists($ruta.$arrData['imagen_actual'])){
                  $arrFile = explode('.', $arrData['imagen_actual']);
                  if(is_array($arrFile)){
                        // obtenemos la extension de la imagen
                        $ext      = end($arrFile);
                        // extraemos y almacenamos la ruta sin la extension
                        $imagenes = array_shift(explode(('.'.$ext),($ruta.$arrData['imagen_actual']))); 
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
        
        $this->db->where('id_contenido',$id);
        $this->db->update('contenidos',$arrUPD);
        return true;
   }
   
   function eliminar_comentarios($id){
      $this->db->delete('comentarios_contenidos', array('id_contenido' => $id)); 
   }

   function eliminar_articulo($id){
      //$this->eliminar_comentarios($id);
      $this->db->delete('contenidos', array('id_contenido' => $id)); 
   }
   
   function get_tipos_contenidos(){
        $this->db->select('tc.id_tipo_contenido AS id,tc.nombre')
                        ->from('tipos_contenidos AS tc')
                        ->where('tc.estatus',1);   
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $arrDatos[(int)$row->id] = html_escape(extract_string($row->nombre));
            }
            return $arrDatos;
        }else{
            return false;
        }
   }
}