<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
  
  function get_content($url_post,$id_tipo_contenido=1){
      $this->db->select('id_contenido,url_post,titulo_en,titulo_es,contenido_en,contenido_es,path_img')
               ->from('contenidos')
               //->where('id_tipo_contenido',$id_tipo_contenido)
               ->where('url_post',$url_post);
      $query = $this->db->order_by('fecha_registro','ASC')->limit(1)->get();
        if ($query->num_rows()>0){
            $row  = $query->row_array();
            $lang = get_lang();

            # titulo / contenido
            if($lang == 'en'){
                $titulo    = html_escape($row['titulo_en']);
                $contenido = html_entity_decode($row['contenido_en']);
            }elseif($lang == 'es'){
                $titulo    = html_escape($row['titulo_es']);
                $contenido = html_entity_decode($row['contenido_es']);
            }

            $id = (int)$row['id_contenido'];
            return array(
                'id'          => $id,
                'url_post'    => html_escape($row['url_post']),
                'titulo'      => $titulo,
                'contenido'   => $contenido,
                'path_img'    => $id.'/'.html_escape($row['path_img'])
            );
        }else{
            return false;
        }
  }
}