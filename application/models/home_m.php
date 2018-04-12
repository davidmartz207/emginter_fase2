<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
  
  function get_text_home(){ 
      $this->db->select('id_contenido,url_post,titulo_en,titulo_es,contenido_en,contenido_es,path_img')
               ->from('contenidos')
               ->where('id_tipo_contenido',1);
      $query = $this->db->order_by('fecha_registro','DESC')->limit(1)->get();
        if ($query->num_rows()>0){
            $row  = $query->row_array();
            $lang = get_lang();
            
            # titulo / contenido
            if($lang == 'en'){
                $titulo    = html_escape($row['titulo_en']);
                $contenido = html_entity_decode(extract_string($row['contenido_en'],600));
            }elseif($lang == 'es'){
                $titulo    = html_escape($row['titulo_es']);
                $contenido = html_entity_decode(extract_string($row['contenido_es'],600));
            }

            $id = (int)$row['id_contenido'];
            return array(
                'id'          => $id,
                'url_post'    => site_url('content').'/'.html_escape($row['url_post']),
                'titulo'      => $titulo,
                'contenido'   => $contenido,
                'path_img'    => $id.'/'.html_escape($row['path_img']),
            );
        }else{
            return false;
        }
  }

  function get_image_link(){
        $id_idioma = get_id_idioma();  
        $query = $this->db->select('id_image_link,nombre,path_img,enlace')
               ->from('image_link')
               ->where('id_idioma',$id_idioma)
               ->where('estatus',1)
               ->order_by('id_image_link','DESC')
               ->limit(3)
               ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id = (int)$row->id_image_link;
                $arrDatos[] = array(
                        'id'          => $id,
                        'nombre'      => html_escape($row->nombre),
                        'path_img'    => html_escape($id.'/'.$row->path_img),
                        'enlace'      => site_url($row->enlace),
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }
  
  function get_new_release(){
       $query = $this->db->select('id_producto,url_post,sku,path_img')
               ->from('productos')
               ->where('new_release','1')
               ->where('estatus',1)
               ->order_by('fecha_registro','DESC')
               ->limit(20)
               ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id = (int)$row->id_producto;
                $arrDatos[] = array(
                        'id'          => $id,
                        'titulo'      => html_escape($row->sku),
                        'path_img'    => $row->path_img ? 'media/products/'.html_escape($id.'/'.$row->path_img) : 'media/default/producto.png',
                        'url_post'    => html_escape($row->url_post),
                    );
            }
            return $arrDatos;
        }else{
            return false;
        }
  }  
}