<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_products_m extends CI_Model{
    public function __construct(){
        parent::__construct();
    }


    function get_products($arrParam,$limit,$desde,$order_by='',$selUpDown=''){
        
        $db_search = $this->load->database('emg_search', TRUE);
        $order_by = (int)$order_by;
        switch($order_by){
            case 1:{#id
                $order_by = 'p.id_producto';
                break;
            }
            case 2:{#tipo de productos
                $order_by = 'tp.nombre_en';
                break;
            }
            case 3:{#sku
                $order_by = 'p.sku';
                break;
            }
            case 4:{#oem
                $order_by = 'oem.nombre';
                break;
            }
            case 5:{#smp
                $order_by = 'smp.nombre';
                break;
            }
            case 6:{#wells
                $order_by = 'wells.nombre';
                break;
            }
            case 20:{#estatus
                $order_by = 'p.estatus';
                break;
            }
            default:{
                $order_by = 'p.sku';
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

        $db_search->select("p.id_producto,p.oem,p.url_post,p.path_img,p.linea,
                            p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                            p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                            p.descripcion_es,p.precio,p.estatus AS p_estatus,p.fecha_registro,
                            tp.nombre_en AS tipo_producto,GROUP_CONCAT(DISTINCT oem.nombre SEPARATOR'???') AS oemn,
                            GROUP_CONCAT(DISTINCT smp.nombre SEPARATOR'???') AS smpn,GROUP_CONCAT(DISTINCT wells.nombre SEPARATOR'???') AS wellsn")
            ->from('productos AS p')
            ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
            ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto','left')
            ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto','left')
            ->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
            ->group_by("p.id_producto");

        # filtros del buscador

        if(is_array($arrParam)){
            if(isset($arrParam['txtSKU']) and !empty($arrParam['txtSKU'])){
                $db_search->like("p.sku",$arrParam['txtSKU']);
            }

            if(isset($arrParam['txtOEM']) and !empty($arrParam['txtOEM'])){
                $db_search->like("REPLACE(REPLACE(REPLACE(oem.nombre,'-', ''),'/',''),' ','')",$this->caracter($arrParam['txtOEM']));
                $db_search->or_like("REPLACE(REPLACE(REPLACE(p.oem,'-', ''),'/',''),' ','')",$this->caracter($arrParam['txtOEM']));
            }
            if(isset($arrParam['txtSMP']) and !empty($arrParam['txtSMP'])){
                $db_search->like("REPLACE(REPLACE(REPLACE(smp.nombre,'-', ''),'/',''),' ','')",$arrParam['txtSMP']);
                $db_search->or_like("REPLACE(REPLACE(REPLACE(p.smp,'-', ''),'/',''),' ','')",$arrParam['txtSMP']);
            }
            if(isset($arrParam['txtWELLS']) and !empty($arrParam['txtWELLS'])){
                $db_search->like("REPLACE(REPLACE(REPLACE(wells.nombre,'-', ''),'/',''),' ','')",$arrParam['txtWELLS']);
                $db_search->or_like("REPLACE(REPLACE(REPLACE(p.wells,'-', ''),'/',''),' ','')",$arrParam['txtWELLS']);
            }
            if(isset($arrParam['selTiposProductos']) and !empty($arrParam['selTiposProductos'])){
                $db_search->where('p.id_tipo_producto',$arrParam['selTiposProductos']);
            }
            if(isset($arrParam['linea']) and !empty($arrParam['linea'])){
                $db_search->where('p.linea',$arrParam['linea']);
            }
            if(isset($arrParam['selLinea']) and !empty($arrParam['selLinea'])){

                $db_search->where('p.linea',$arrParam['selLinea']);
            }

            # -- filter applications
            $arrParam_app = '';
            if(isset($arrParam['selTiposMotores']) and !empty($arrParam['selTiposMotores'])){
                $arrParam_app['tipo_motor'] = $arrParam['selTiposMotores'];
            }
            if(isset($arrParam['selMarcas']) and !empty($arrParam['selMarcas'])){
                $arrParam_app['marca'] = $arrParam['selMarcas'];
            }
            if(isset($arrParam['selModelos']) and !empty($arrParam['selModelos'])){
                $arrParam_app['modelo'] = $arrParam['selModelos'];
            }
            if(isset($arrParam['txtYears']) and !empty($arrParam['txtYears'])){
                $arrParam_app['years'] = $arrParam['txtYears'];
            }
        }

        $db_search->order_by($order_by,$selUpDown)->limit($limit,$desde);
        $query = $db_search->get();

        if ($query->num_rows()>0){
            $arrResult = array();
            foreach ($query->result() as $row){
                $id_producto  = (int)$row->id_producto;
                # obtenemos las aplicaciones del producto
                $arrApplicaciones = get_applications_by_product($arrParam_app,$id_producto);
                //echo "<pre>",print_r($arrApplicaciones),"</pre>";
                # si hay parametros y no hay resultados, no cargamos datos
                if(!is_array($arrApplicaciones)){
                    //continue;
                }
                $arrResult[] = array(
                    'id'              => $id_producto,
                    'tipo_producto'   => html_escape($row->tipo_producto),
                    'linea'           => html_escape($row->linea),
                    'sku'             => html_escape($row->sku),
                    'oem'             => html_escape(($row->oemn =='') ? $row->oem : str_replace("???", ",", $row->oemn)),
                    'smp'             => html_escape(($row->smpn =='') ? $row->smp : str_replace("???", ",", $row->smpn)),
                    'wells'           => html_escape(($row->wellsn =='') ? $row->wells : str_replace("???", ",", $row->wellsn)),
                    'url_post'        => html_escape($row->url_post),
                    'estatus'         => $row->p_estatus==1 ? 'Activo' : 'Inactivo'
                );
            }

            
            return $arrResult;
        }else{
            return false;
        }
    }

    function get_rows($arrParam){
        $db_search = $this->load->database('emg_search', TRUE);
        $db_search->select('count(distinct(p.id_producto)) AS total');
        $db_search->join('aplicaciones_productos','aplicaciones_productos.id_producto=p.id_producto');
        $db_search->from('productos AS p');

        # filtros del buscador
        if(is_array($arrParam)){
            if(isset($arrParam['txtSKU']) and !empty($arrParam['txtSKU'])){
                $db_search->where('p.sku',$arrParam['txtSKU']);
            }
            if(isset($arrParam['txtOEM']) and !empty($arrParam['txtOEM'])){
                $db_search->where('p.oem',$arrParam['txtOEM']);
            }
            if(isset($arrParam['txtSMP']) and !empty($arrParam['txtSMP'])){
                $db_search->where('p.smp',$arrParam['txtSMP']);
            }
            if(isset($arrParam['txtWELLS']) and !empty($arrParam['txtWELLS'])){
                $db_search->where('p.wells',$arrParam['txtWELLS']);
            }
            if(isset($arrParam['selTiposProductos']) and !empty($arrParam['selTiposProductos'])){
                $db_search->where('p.id_tipo_producto',$arrParam['selTiposProductos']);
            }
            if(isset($arrParam['selMarcas']) and !empty($arrParam['selMarcas'])){
                $db_search->where('aplicaciones_productos.id_marca',$arrParam['selMarcas']);
            }
            if(isset($arrParam['linea']) and !empty($arrParam['linea'])){
                $db_search->where('p.linea',$arrParam['linea']);
            }
            if(isset($arrParam['selLinea']) and !empty($arrParam['selLinea'])){

                $db_search->where('p.linea',$arrParam['selLinea']);
            }
        }

        $query = $db_search->get();


        if($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['total'];
        }else{
            return FALSE;
        }
    }

    function registrar($arrDatos){

        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $this->db->trans_start();
        $db_expor_import->insert('productos',
            array(
                'url_post'           => generar_url_product($arrDatos['txtSKU']),
                'id_tipo_producto'   => isset($arrDatos['selTiposProductos']) ? $arrDatos['selTiposProductos'] : '0',
                'nombre_en'          => '',
                'nombre_es'          => '',
                'sku'                => isset($arrDatos['txtSKU']) ? $arrDatos['txtSKU'] : 'no sku',
                'new_release'        => isset($arrDatos['selNewRelease']) ? $arrDatos['selNewRelease'] : '',
                'descripcion_en'     => isset($arrDatos['txtaDescripcionEn']) ? $arrDatos['txtaDescripcionEn'] : '',
                'descripcion_es'     => isset($arrDatos['txtaDescripcionEs']) ? $arrDatos['txtaDescripcionEs'] : '',
                'oem'                => '',
                'smp'                => '',
                'wells'              => '',
                'path_img'           => isset($arrDatos['Imagen']) ? $arrDatos['Imagen'] : '',
                'precio'             => isset($arrDatos['txtSellPrice']) ? $arrDatos['txtSellPrice'] : '',
                'estatus'            => '1',
                'linea'              => isset($arrDatos['linea'])?$arrDatos['linea']:"",
                'flywheel'           => isset($arrDatos['txtFlywheel'])?$arrDatos['txtFlywheel']:"",
                'cover'              => isset($arrDatos['txtCover'])?$arrDatos['txtCover']:"",
                'disc_info'          => isset($arrDatos['txtDisc_info'])?$arrDatos['txtDisc_info']:"",
              //  'disc_splines'     => isset($arrDatos['txtDisc_splines'])?$arrDatos['txtDisc_splines']:"",
              //  'disc_hub_size'    => isset($arrDatos['txtDisc_hub_size'])?$arrDatos['txtDisc_hub_size']:"",
                'notes_1'            => isset($arrDatos['txtNotes_1'])?$arrDatos['txtNotes_1']:"",
                'notes_2'            => isset($arrDatos['txtNotes_2'])?$arrDatos['txtNotes_2']:"",
               // 'cylinder'         => isset($arrDatos['txtCylinder'])?$arrDatos['txtCylinder']:"",
                'linea'              => isset($arrDatos['txtLinea'])?$arrDatos['txtLinea']:"",

            ));
        $idProducto = $db_expor_import->insert_id();
        if(isset($arrDatos['txtOEM'])){
            $oem = explode(",", $arrDatos['txtOEM']);
            foreach ($oem as $value) {
                $db_expor_import->insert('productos_oem',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $idProducto
                    ));
            }
        }
        if(isset($arrDatos['txtSMP'])){
            $smp = explode(",", $arrDatos['txtSMP']);
            foreach ($smp as $value) {
                $db_expor_import->insert('productos_smp',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $idProducto
                    ));
            }
        }
        if(isset($arrDatos['txtWells'])){
            $wells = explode(",", $arrDatos['txtWells']);
            foreach ($wells as $value) {
                $db_expor_import->insert('productos_wells',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $idProducto
                    ));
            }
        }
        if(isset($arrDatos['txtDai'])){
            $wells = explode(",", $arrDatos['txtDai']);
            foreach ($wells as $value) {
                $db_expor_import->insert('productos_dai',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $idProducto
                    ));
            }
        }
        $this->db->trans_complete();
        unset($arrDatos);
        return $idProducto;
    }

    //
    function get_data($id){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select("p.linea,p.id_producto,p.oem,p.url_post,p.path_img,
                        p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                        p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                        p.descripcion_es,p.precio,p.estatus AS p_estatus,p.fecha_registro,
                        p.id_tipo_producto,GROUP_CONCAT(DISTINCT oem.nombre ) AS oemn,
                        GROUP_CONCAT(DISTINCT smp.nombre ) AS smpn,
                        GROUP_CONCAT(DISTINCT wells.nombre ) AS wellsn,
                        GROUP_CONCAT(DISTINCT dai.nombre) AS dai,

			p.flywheel,p.cover,p.disc_info,p.notes_1,p.notes_2")

            ->from('productos AS p')
            ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto','left')
            ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto','left')
            ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto','left')
            ->join('productos_dai AS dai', 'dai.id_producto = p.id_producto','left')
            ->group_by("p.id_producto")
            ->where('p.id_producto',$id);

        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return array(
                'id_producto'        => $id,
                'linea'              => $row['linea'],
                'url_post'           => $row['url_post'],
                'id_tipo_producto'   => $row['id_tipo_producto'],
                'nombre_en'          => $row['producto_en'],
                'nombre_es'          => $row['producto_es'],
                'sku'                => $row['sku'],
                'new_release'        => (int)$row['new_release'],
                'descripcion_en'     => $row['descripcion_en'],
                'descripcion_es'     => $row['descripcion_es'],
                'oem'                => ($row['oemn'] =='') ? $row['oem'] : str_replace("???", ",", $row['oemn']),
                'smp'                => ($row['smpn'] =='') ? $row['smp'] : str_replace("???", ",", $row['smpn']),
                'wells'              => ($row['wellsn'] =='') ? $row['wells'] : str_replace("???", ",", $row['wellsn']),
                'path_img'           => $row['path_img'] ? 'media/products/'.$id.'/'.html_escape($row['path_img']) : 'media/default/producto.png',
                'precio'             => $row['precio'],
                'estatus'            => (int)$row['p_estatus'],
                'flywheel'           => $row['flywheel'],
                'cover'              => $row['cover'],
                'disc_info'      => $row['disc_info'],
              //  'disc_splines'       => $row['disc_splines'],
               // 'disc_hub_size'      => $row['disc_hub_size'],
                'notes1'             => $row['notes_1'],
                'notes2'             => $row['notes_2'],
                'txtDai'             => $row['dai'],
               // 'cylinder'           => $row['cylinder']

            );
        }else{
            return false;
        }
    }

    function set_data($arrData){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $id = (int)$arrData['id'];

        // relaicon campo_tabla = valor
        $arrUPD = array(
	    'linea'              => (isset($arrData['selLinea']) ? $arrData['selLinea'] : ''), 
            'id_tipo_producto'   => (isset($arrData['selTiposProductos']) ? $arrData['selTiposProductos'] : ''),
            'nombre_en'          => '',
            'nombre_es'          => '',
            'sku'                => (isset($arrData['txtSKU']) ? $arrData['txtSKU'] : ''),
            'new_release'        => (isset($arrData['selNewRelease']) ? $arrData['selNewRelease'] : ''),
            'descripcion_en'     => (isset($arrData['txtaDescripcionEn']) ? $arrData['txtaDescripcionEn'] : ''),
            'descripcion_es'     => (isset($arrData['txtaDescripcionEs']) ? $arrData['txtaDescripcionEs'] : ''),
            'oem'                => '',
            'smp'                => '',
            'wells'              => '',
            'precio'             => (isset($arrData['txtSellPrice']) ? $arrData['txtSellPrice'] : ''),
            'estatus'            => (isset($arrData['selEstatus']) ? (int)$arrData['selEstatus'] : ''),
	        'flywheel'           => (isset($arrData['flywheel']) ? $arrData['flywheel'] : ''),
	        'cover'              => (isset($arrData['cover']) ? $arrData['cover'] : ''),
	        'disc_info'          => (isset($arrData['disc_info']) ? $arrData['disc_info'] : ''),
	        'notes_1'            => (isset($arrData['notes_1']) ? $arrData['notes_1'] : ''),
	        'notes_2'            => (isset($arrData['notes_2']) ? $arrData['notes_2'] : ''),

        );

        // le pasamos los datos e la nueva imagen, si fue enviada
        if(!empty($arrData['Imagen']) and !empty($arrData['imagen_actual'])){
            // obtenemos los datos de la imagen anterior y la eliminamos junto 
            // con las resoluciones
            $ruta = './media/products/';
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
        $this->db->trans_start();
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->update('productos',$arrUPD);

        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_oem');
        if(isset($arrData['txtOEM'])){
            $oem = explode(",", $arrData['txtOEM']);
            foreach ($oem as $value) {
                $db_expor_import->insert('productos_oem',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $id
                    ));
            }
        }
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_smp');
        if(isset($arrData['txtSMP'])){
            $smp = explode(",", $arrData['txtSMP']);
            foreach ($smp as $value) {
                $db_expor_import->insert('productos_smp',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $id
                    ));
            }
        }
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_wells');
        if(isset($arrData['txtWells'])){
            $wells = explode(",", $arrData['txtWells']);
            foreach ($wells as $value) {
                $db_expor_import->insert('productos_wells',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $id
                    ));
            }
        }

        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_dai');
        if(isset($arrData['txtDai'])){
            $dai = explode(",", $arrData['txtDai']);
            foreach ($dai as $value) {
                $db_expor_import->insert('productos_dai',
                    array(
                        'nombre'        => $value,
                        'id_producto'   => $id
                    ));
            }
        }

        $this->db->trans_complete();
        return true;
    }

    function set_image($id,$image){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $arrUPD['path_img'] = $image;
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->update('productos',$arrUPD);
        return true;
    }

    function delete($id){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        #del applications
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('aplicaciones_productos');

        # del productos_oem
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_oem');
        # del productos_oem
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_smp');
        # del productos_oem
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos_wells');

        # del products
        $db_expor_import->where('id_producto',$id);
        $db_expor_import->delete('productos');

        // eliminamos las imagenes
        delete_dir_and_file('./media/products/'.$id.'/');
    }

    function truncate_all_data_prodcuts(){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->empty_table('aplicaciones_productos');
        $db_expor_import->empty_table('productos');
        $db_expor_import->empty_table('modelos');
        $db_expor_import->empty_table('marcas');
        $db_expor_import->empty_table('tipos_productos');
        $db_expor_import->empty_table('tipos_motores');

        // eliminamos las imagenes
        delete_dir_and_file('./media/products/');
    }

    public function get_json_tipos_productos(){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $query = $db_expor_import->query("SELECT id_tipo_producto AS id,nombre_en,nombre_es
                                   FROM tipos_productos 
                                   WHERE estatus=1
                                   ORDER BY nombre_en ASC");
        if($query->num_rows()>0){
            $arrDatos = array();
            foreach($query->result() as $row){
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->nombre_en . ' - ' . $row->nombre_es,ENT_QUOTES)
                );
            }
            $query->free_result();
            return json_encode($arrDatos);
        }else{
            return json_encode(array(1=> array("-- no se encontraron registros..")));
        }
    }

    # funciones de procesamiento de xls
    # product ------------------------------------------------------------------
    function existe_producto($sku){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_producto AS id')
            ->from('productos')
            ->where('sku',$sku);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    # product type -------------------------------------------------------------
    function existe_product_type($nombre_en){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_tipo_producto AS id')
            ->from('tipos_productos')
            ->where('nombre_en',$nombre_en);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_tipo_producto($nombre_en,$nombre_es){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->insert('tipos_productos',
            array(
                'nombre_en' => $nombre_en,
                'nombre_es' => $nombre_es
            ));
        return $db_expor_import->insert_id();
    }
    function update_tipo_producto($id,$nombre_en,$nombre_es){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $arrUPD['nombre_en'] = $nombre_en;
        $arrUPD['nombre_es'] = $nombre_es;
        $db_expor_import->where('id_tipo_producto',$id);
        $db_expor_import->update('tipos_productos',$arrUPD);
        return true;
    }
    # manufacturer -------------------------------------------------------------
    function existe_marca($marca){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_marca AS id')
            ->from('marcas')
            ->where('nombre',$marca);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_marca($marca){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->insert('marcas',
            array(
                'nombre' => $marca
            ));
        return $db_expor_import->insert_id();
    }
    # modelo--------------------------------------------------------------------
    function existe_modelo($id_marca,$modelo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_modelo AS id')
            ->from('modelos')
            ->where('id_marca',$id_marca)
            ->where('nombre',$modelo);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_modelo($id_marca,$modelo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->insert('modelos',
            array(
                'id_marca' => $id_marca,
                'nombre'   => $modelo
            ));
        return $db_expor_import->insert_id();
    }
    # tipo de motor ------------------------------------------------------------
    function existe_tipo_motor($tipo_motor){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_tipo_motor AS id')
            ->from('tipos_motores')
            ->where('nombre_en',$tipo_motor);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_tipo_motor($tipo_motor){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->insert('tipos_motores',
            array(
                'nombre_en' => $tipo_motor
            ));
        return $db_expor_import->insert_id();
    }
    # aplicacion producto ------------------------------------------------------
    function existe_aplicacion_producto($id_producto,$id_tipo_motor,$id_marca,$id_modelo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('id_aplicaciones_producto AS id')
            ->from('aplicaciones_productos')
            ->where('id_producto',$id_producto)
            ->where('id_tipo_motor',$id_tipo_motor)
            ->where('id_marca',$id_marca)
            ->where('ref_id_modelo',$id_modelo);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $row = $query->row_array();//nl2br
            return (int)$row['id'];
        }else{
            return FALSE;
        }
    }
    function registrar_aplicacion_producto($id_producto,$id_tipo_motor,$id_marca,$id_modelo,$years){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->insert('aplicaciones_productos',
            array(
                'id_producto'   => $id_producto,
                'id_tipo_motor' => $id_tipo_motor,
                'id_marca'      => $id_marca,
                'ref_id_modelo' => $id_modelo,
                'years'         => $years
            ));
        return $db_expor_import->insert_id();
    }

    function get_data_export(){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('p.id_producto,p.oem,p.new_release,
                           tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es,
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
            ->group_by("p.id_producto");



        //$db_expor_import->limit(1);
        $query = $db_expor_import->get();
        if ($query->num_rows()>0){
            $arrResult = array();
            foreach ($query->result() as $row){
                $osw = $this->getOemSmpWells($row->id_producto);
                if ($row->linea == "emg") {
                    $row->linea = 1;
                }elseif($row->linea == "perfection"){
                    $row->linea = 2;
                }else{
                    $row->linea = 3;
                }
                $arrResult[] = array(
                    'oem'              => html_escape(($osw->oemn =='') ? $row->oem : str_replace("???", ",", $osw->oemn)),
                    'new_release'      => html_escape($row->new_release),
                    'tipo_producto_en' => html_escape($row->tipo_producto_en),
                    'tipo_producto_es' => html_escape($row->tipo_producto_es),
                    'imagen'           => '',
                    'smp'              => html_escape(($osw->smpn =='') ? $row->smp : str_replace("???", ",", $osw->smpn)),
                    'wells'            => html_escape(($osw->wellsn =='') ? $row->wells : str_replace("???", ",", $osw->wellsn)),
                    'sku'              => html_escape($row->sku),
                    'sell_price'       => html_escape($row->precio),
                    'marca'            => html_escape($row->marca),
                    'modelo'           => get_modelo($row->ref_id_modelo),
                    'motor'            => html_escape($row->motor),
                    'years'            => html_escape($row->years),
                    'linea'            => html_escape($row->linea),
                    'flywheel'         => html_escape($row->flywheel),
                    'cover'            => html_escape($row->cover),
                    'disc_info'        => html_escape($row->disc_info),
                    'notes_1'          => html_escape($row->notes_1),
                    'notes_2'          => html_escape($row->notes_2),
                    'dai'              => html_escape($row->dai)
                );
            }
            return $arrResult;
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
    # --------------------------------------------------------------------------
    # admin search caracter
    #---------------------------------------------------------------------------

    function caracter($cadena){

        $cadena= str_replace(" ", "", $cadena);
        $cadena= str_replace("-", "", $cadena);
        $cadena= str_replace("_", "", $cadena);
        $cadena= str_replace("/", "", $cadena);
        return $cadena;
    }
    # --------------------------------------------------------------------------
    # admin search user control
    #---------------------------------------------------------------------------
    function set_field($campo,$valor){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        if($this->if_exists_field($campo)){
            $db_expor_import->where('campo',$campo);
            $db_expor_import->where('hash',$this->get_hash());
            $db_expor_import->update('admin_search',array('valor' => $valor));
        }else{
            $db_expor_import->insert('admin_search',array(
                'campo' => $campo,
                'valor' => $valor,
                'hash'  => $this->get_hash()
            ));
        }
        $this->delete_fields();
        //exit($db_expor_import->last_query());
    }

    function if_exists_field($campo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('valor')->from('admin_search')
            ->where('campo',$campo)
            ->where('hash',$this->get_hash());
        $query = $db_expor_import->get();
        //exit($db_expor_import->last_query());
        if ($query->num_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_field($campo){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->select('valor')->from('admin_search')
            ->where('campo',$campo)
            ->where('hash',$this->get_hash());
        $query = $db_expor_import->get();
        //exit($db_expor_import->last_query());
        if ($query->num_rows()>0){
            $row = $query->row_array();
            return $row['valor'];
        }else{
            return FALSE;
        }
    }

    function get_hash(){
        return md5($this->input->ip_address());
    }

    function delete_fields(){
        $db_expor_import = $this->load->database('emg_expor_import', TRUE);
        $db_expor_import->where('hash',$this->get_hash());
        //dAVID LOCAL CAMBIO
        //$db_expor_import->where('WEEK(fecha_registro) <','WEEK(curdate())');
        $db_expor_import->delete('admin_search');
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
