<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }
  
  function get_data_pdf($id_producto){
      $lang = get_lang();
      $this->db->select('p.id_producto,p.oem,p.new_release,
                           tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es,
                           p.descripcion_es, p.descripcion_en,p.url_post,
                           p.smp,p.wells,dai.nombre as dai,p.sku,p.precio,
                           m.nombre AS marca,ap.ref_id_modelo,GROUP_CONCAT(DISTINCT oem.nombre ) AS oemn,
                        GROUP_CONCAT(DISTINCT smp.nombre ) AS smpn,
                        GROUP_CONCAT(DISTINCT wells.nombre ) AS wellsn,
                        GROUP_CONCAT(DISTINCT dai.nombre) AS dai, tm.nombre_en AS motor,ap.years, p.linea, p.flywheel, p.cover, p.disc_info, p.notes_1, p.notes_2')
          ->from('aplicaciones_productos AS ap')
          ->join('productos AS p','ap.id_producto=p.id_producto')
          ->join('marcas AS m','m.id_marca=ap.id_marca')
          ->join('tipos_motores AS tm','tm.id_tipo_motor=ap.id_tipo_motor')
          ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
          ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
          ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto','left')
          ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto','left')
          ->join('productos_dai AS dai', 'dai.id_producto = p.id_producto','left')
          ->order_by('p.sku','ASC')
          ->group_by("p.id_producto")
          ->where('p.estatus','1')
          ->where('p.id_producto',$id_producto);
      $query = $this->db->limit(1)->get();
      $tipo_prod ="";
      if ($query->num_rows()>0){

          $row = $query->row_array();
          $id_producto  = (int)$row['id_producto'];
          $osw = $this->getOemSmpWells($id_producto);

          # gestion del lenguaje
          if($lang=='es'){
              $datos_linea = 'Tipo: '.$row['tipo_producto_es'];
              $descripcion = $row['descripcion_es'];
          }elseif($lang=='en'){
              $datos_linea = 'Type: '.$row['tipo_producto_en'];
              $descripcion = $row['descripcion_en'];
          }
          return array(
              'id'               => $id_producto,
              'linea'            => html_escape($row['linea']),
              'flywheel'         => html_escape($row['flywheel']),
              'smp'              => html_escape(($osw->smpn =='') ? $row->smp : str_replace("???", ",", $osw->smpn)),
              'wells'            => html_escape(($osw->wellsn =='') ? $row->wells : str_replace("???", ",", $osw->wellsn)),
              'oem'              => html_escape(($osw->oemn =='') ? $row->oem : str_replace("???", ",", $osw->oemn)),
              'dai'              => html_escape($row['dai']),
              'titulo'           => html_escape($row['sku']),
              'path_img'         => !empty($row['path_img']) ? 'media/products/'.html_escape($id_producto.'/'.$row['path_img']) : 'media/default/producto.png',
              'datos_linea'      => html_escape($datos_linea),
              'tipo_prod'        => html_escape($tipo_prod),
              'flywheel'         => html_escape($row['flywheel']),
              'cover'            => html_escape($row['cover']),
              'disc_info'        => html_escape($row['disc_info']),
              'notes_1'          => html_escape($row['notes_1']),
              'notes_2'          => html_escape($row['notes_1']),
              'url_post'         => html_escape($row['url_post']),
              'tipo_prod'        => html_escape($tipo_prod),
              'descripcion'      => html_escape(extract_string($descripcion,200)),
              'arrApplicaciones' => get_applications_by_product(array(),$id_producto)
          );
      }else{
          return FALSE;
      }
  }

  function get_product($url_post){
        $lang = get_lang();
        $this->db->select('p.id_producto,p.oem,p.new_release,
                           tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es,
                           p.descripcion_es, p.descripcion_en,
                           p.smp,p.wells,dai.nombre as dai,p.sku,p.precio,p.url_post,
                           m.nombre AS marca,ap.ref_id_modelo,GROUP_CONCAT(DISTINCT oem.nombre ) AS oemn,
                        GROUP_CONCAT(DISTINCT smp.nombre ) AS smpn,
                        GROUP_CONCAT(DISTINCT wells.nombre ) AS wellsn,
                        GROUP_CONCAT(DISTINCT dai.nombre) AS dai, tm.nombre_en AS motor,ap.years, p.linea, p.flywheel, p.cover, p.disc_info, p.notes_1, p.notes_2')
            ->from('aplicaciones_productos AS ap')
            ->join('productos AS p','ap.id_producto=p.id_producto')
            ->join('marcas AS m','m.id_marca=ap.id_marca')
            ->join('tipos_motores AS tm','tm.id_tipo_motor=ap.id_tipo_motor')
            ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
            ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
            ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto','left')
            ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto','left')
            ->join('productos_dai AS dai', 'dai.id_producto = p.id_producto','left')
            ->order_by('p.sku','ASC')
            ->group_by("p.id_producto")
                        ->where('p.estatus','1')
                        ->where('p.url_post',$url_post);
        $query = $this->db->limit(1)->get();
        $tipo_prod ="";
        if ($query->num_rows()>0){

            $row = $query->row_array();
            $id_producto  = (int)$row['id_producto'];
            $osw = $this->getOemSmpWells($id_producto);

            # gestion del lenguaje
            if($lang=='es'){
                $datos_linea = 'Tipo: '.$row['tipo_producto_es'];
                $descripcion = $row['descripcion_es'];
            }elseif($lang=='en'){
                $datos_linea = 'Type: '.$row['tipo_producto_en'];
                $descripcion = $row['descripcion_en'];
            }
            return array(
                'id'               => $id_producto,
                'linea'            => html_escape($row['linea']),
                'flywheel'         => html_escape($row['flywheel']),
                'smp'              => html_escape(($osw->smpn =='') ? $row->smp : str_replace("???", ",", $osw->smpn)),
                'wells'            => html_escape(($osw->wellsn =='') ? $row->wells : str_replace("???", ",", $osw->wellsn)),
                'oem'              => html_escape(($osw->oemn =='') ? $row->oem : str_replace("???", ",", $osw->oemn)),
                'dai'              => html_escape($row['dai']),
                'titulo'           => html_escape($row['sku']),
                'path_img'         => !empty($row['path_img']) ? 'media/products/'.html_escape($id_producto.'/'.$row['path_img']) : 'media/default/producto.png',
                'datos_linea'      => html_escape($datos_linea),
                'tipo_prod'        => html_escape($tipo_prod),
                'flywheel'         => html_escape($row['flywheel']),
                'cover'            => html_escape($row['cover']),
                'disc_info'        => html_escape($row['disc_info']),
                'notes_1'          => html_escape($row['notes_1']),
                'url_post'         => html_escape($row['url_post']),
                'notes_2'          => html_escape($row['notes_1']),
                'tipo_prod'        => html_escape($tipo_prod),
                'descripcion'      => html_escape(extract_string($descripcion,200)),
                'arrApplicaciones' => get_applications_by_product(array(),$id_producto)
            );
        }else{
            return FALSE;
        }


  }

    function getOemSmpWells($id){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        /*$db_expor_import->select("GROUP_CONCAT(oem.nombre SEPARATOR'???') AS oemn")
                        ->from('productos_oem AS oem')
                        ->where('id_producto',$id)
                        ->group_by("oem.id_producto");*/

        $db_expor_import->select("p.id_producto,GROUP_CONCAT(DISTINCT oem.nombre SEPARATOR'???') AS oemn,
                                GROUP_CONCAT(DISTINCT smp.nombre SEPARATOR'???') AS smpn
                                ,GROUP_CONCAT(DISTINCT wells.nombre SEPARATOR'???') AS wellsn")
            ->from('productos AS p')
            ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
            ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto','left')
            ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto','left')
            ->where('p.id_producto',$id);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            return ($query->result()[0]);
        }else{
            return '';
        }

    }
}