<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
  
  function get_news($id_noticia=null){
      $id_idioma = get_id_idioma();
      $this->db->select()
               ->from('noticias');

      if($id_noticia!=null){
          $this->db->where("id_noticia",$id_noticia);

      }

      $query = $this->db->order_by('fecha_registro','ASC')->get();
        if ($query->num_rows()>0){
            $results  = $query->result_array();
            $lang = get_lang();

            $noticias=array(array());

            $i=0;
            foreach($results as $row){

            # titulo / contenido
            if($lang == 'en'){
                $noticias[$i]["titulo"]=html_escape($row['titulo_en']);
                $noticias[$i]["contenido"]=html_entity_decode($row['texto_en']);
            }elseif($lang == 'es'){
                $noticias[$i]["titulo"]=html_escape($row['titulo_es']);
                $noticias[$i]["contenido"]=html_entity_decode($row['texto_es']);
            }

                $noticias[$i]["id"]=(int)$row['id_noticia'];
                $noticias[$i]["path_img"]=(int)$row['id_noticia'].'/'.html_escape($row['path_img']);
                $noticias[$i]["estatus"]=$row['estatus'];

            $i++;
            }
            return $noticias;
        }else{
            return false;
        }
  }
}