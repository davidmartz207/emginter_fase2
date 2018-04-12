<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_news_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function get_all_data($limit,$desde,$order_by='',$selUpDown=''){
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'tabla.id_noticia';
                break;
            }
            case 2:{#
                $order_by = 'tabla.titulo_en';
                break;
            }
            case 3:{#oem
                $order_by = 'tabla.titulo_es';
                break;
            }
            case 4:{#id
                $order_by = 'tabla.estatus';
                break;
            }
            default:{
                $order_by = 'tabla.titulo_en';
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

        $query = $this->db->select('tabla.id_noticia AS id,tabla.titulo_en,
                                    tabla.titulo_es,tabla.estatus AS status,
                                    tabla.fecha_registro')
                        ->from('noticias AS tabla')
                        ->order_by($order_by,$selUpDown)
                        ->limit($limit,$desde)
                        ->get();
        if ($query->num_rows()>0){
            foreach ($query->result() as $row){
                $id  = (int)$row->id;
                $arrResult[] = array(
                    'id'              => $id,
                    'titulo_en'       => html_escape(extract_string($row->titulo_en)),
                    'titulo_es'       => html_escape(extract_string($row->titulo_es)),
                    'estatus'         => $row->status==1 ? 'Publicada' : 'No Publicada'
                );
            }
            return $arrResult;
        }else{
            return false;
        }
  }
   
  function get_rows(){
        return (int)$this->db->count_all('noticias');
  }
  
  function registrar($arrDatos){
        $this->db->insert('noticias',
              array(
                  'titulo_en'       => $arrDatos['txtTitulo_en'],
                  'titulo_es'       => $arrDatos['txtTitulo_es'],
                  'texto_en'       => $arrDatos['txtTexto_en'],
                  'texto_es'       => $arrDatos['txtTexto_es'],
              ));
        return $this->db->insert_id();
  }
  
  //
   function get_data($id){
        $this->db->select('tabla.id_noticia AS id,tabla.titulo_en,
                           tabla.titulo_es,tabla.texto_es,tabla.texto_en,tabla.estatus,tabla.path_img')
                        ->from('noticias AS tabla')
                        ->where('id_noticia',$id);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                  'titulo_en'       => $row['titulo_en'],
                'titulo_es'       => $row['titulo_es'],
                'texto_en'       => $row['texto_en'],
                'texto_es'       => $row['texto_es'],
                'estatus'         => (int)$row['estatus'],
                  'path_img'         => $row['path_img'] ? 'media/news/pt'.$id.'/'.html_escape($row['path_img']) : 'media/default/producto.png'
                );
        }else{
            return false;
        }
  }
 
  function set_data($arrData){
       $id = (int)$arrData['id'];
       
       // relaicon campo_tabla = valor
       $arrUPD = array(
            'titulo_en'       => $arrData['txtTitulo_en'],
           'titulo_es'       => $arrData['txtTitulo_es'],
           'texto_en'       => $arrData['txtTexto_en'],
           'texto_es'       => $arrData['txtTexto_es'],
            'estatus'         => (int)$arrData['selEstatus']
        );
        $this->db->where('id_noticia',$id);
        $this->db->update('noticias',$arrUPD);
        return true;
  }

  function delete($id){
        $this->db->where('id_noticia',$id);
        $this->db->delete('noticias');
   }

    function set_image($id,$image){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $arrUPD['path_img'] = $image;
        $this->db->where('id_noticia',$id);
        $this->db->update('noticias',$arrUPD);
        return true;
    }
}