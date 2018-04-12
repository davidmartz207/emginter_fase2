<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_catalogs extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_catalogs_m','modelo');
        $this->arrDatos['title']     = 'Panel Products Type';
        $this->arrDatos['vista']     = 'panel_catalogs_list_v';
        $this->arrDatos['sMsjError'] = '';
        
		$megaByte                = 1048576;
		$upload_max_size         = ini_get('upload_max_filesize');
		$upload_max_size         = (int)$upload_max_size;
        $this->max_size_file     = ($upload_max_size * $megaByte);
        $this->str_max_size_file = $upload_max_size ;
        
        // tamaño maximo de las imagenes a subir
        $this->max_size_image     = 3000000;#3mb 3millones
        $this->str_max_size_image = '3MB';
        $this->width_image        = 200;#px
        $this->hight_image        = 200;#px;

        $this->arrDatos['lineas'] = $this->modelo->get_lineas();

    }
     
    public function index(){
        if($this->session->userdata('result')==1){
            $this->arrDatos['sMsjConf'] = 'Creado con éxito.';
        }elseif($this->session->userdata('result')==2){
            $this->arrDatos['sMsjConf'] = 'Actualizado con éxito.';
        }elseif($this->session->userdata('result')==3){
            $this->arrDatos['sMsjConf'] = 'Eliminado con éxito.';
        }elseif($this->session->userdata('result')==4){
            $this->arrDatos['sMsjConf'] = 'No se encontraron datos para exportar';
        }
        $this->session->set_userdata('result',0);
        
        $config['base_url']            = site_url('panel_catalogs').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_catalogs_order_by')){
            $selOrderBy = $this->session->userdata('panel_catalogs_order_by');
        }else{
            $this->session->set_userdata('panel_catalogs_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_catalogs_selUpDown')){
            $selUpDown = $this->session->userdata('panel_catalogs_selUpDown');
        }else{
            $this->session->set_userdata('panel_catalogs_selUpDown',$selUpDown);
        }
        $desde                       = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
        $this->arrDatos['arrResult'] = $this->modelo->get_all_data($config['per_page'],$desde,$selOrderBy,$selUpDown);
        
        $config['num_links']           = round($config['total_rows']/$config['per_page']);
        //$config['page_query_string'] = TRUE;
        // $config['use_page_numbers'] = TRUE;
        //$config['query_string_segment'] = 'page';
        $config['first_link'] = false;
        //$config['last_link'] = false;

        $config['full_tag_open']       = '<ul class="pagination">';
        $config['full_tag_close']      = '</ul>';

        /*$config['first_link'] = '&laquo; Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
         * 
         */
        $config['last_link'] = 'Último &raquo;';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Próximo <span class="fa fa-arrow-circle-o-right"></span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<span class="fa fa-arrow-circle-o-left"></span> Anterior';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        // $config['display_pages'] = FALSE;
        //$config['anchor_class'] = 'follow_link';
        #-------------------------------------------------------------
        $this->pagination->initialize($config);
        $this->arrDatos['pagination']  = $this->pagination->create_links();
	$this->load->view('panel/includes/template',$this->arrDatos);
    }

    public function ins(){
        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';
        //exit("Zona Deshabilitada..");
        if($this->input->post('btSubmit')){
            $arrValidaciones = array(
                array(
                    'field' => 'linea',
                    'label' => 'Linea',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtNombre_en',
                    'label' => 'Nombre Ingles',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtNombre_es',
                    'label' => 'Nombre Español',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtLinkExterno',
                    'label' => 'Enlace Externo',
                    'rules' => 'max_length[255]|callback__invalida_url'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $selLinea                = $this->input->post('linea');
            $txtNombre_en            = $this->input->post('txtNombre_en');
            $txtNombre_es            = $this->input->post('txtNombre_es');
            $txtLinkExterno          = $this->input->post('txtLinkExterno');

            $this->arrDatos['selLinea']             = html_escape($selLinea);
            $this->arrDatos['txtNombre_en']         = html_escape($txtNombre_en);
            $this->arrDatos['txtNombre_es']         = html_escape($txtNombre_es);
            $this->arrDatos['txtLinkExterno']       = html_escape($txtLinkExterno);
            $arrFILE                                = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            # verifivcamos si se subio el archivo
            $file_upload = FALSE;
            if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                $file_upload = true;
            }

            if($this->form_validation->run() == FALSE){
                if(form_error('txtNombre_en')){
                    $this->arrDatos['sMsjError'] = form_error('txtNombre_en');
                }elseif(form_error('txtNombre_es')){
                    $this->arrDatos['sMsjError'] = form_error('txtNombre_es');
                }elseif(form_error('txtLinkExterno')){
                    $this->arrDatos['sMsjError'] = form_error('txtLinkExterno');
                }elseif(form_error('linea')){
                    $this->arrDatos['sMsjError'] = form_error('linea');
                }
                
            // validamos el tipo de archivo
            }elseif(empty($txtLinkExterno) and !$file_upload){
                $this->arrDatos['sMsjError'] = 'Debe seleccionar un archivo PDF o introducir una ruta externa';
                
            }elseif($file_upload and !in_array($arrFILE['type'],array('application/pdf','application/x-pdf'))){
                $this->arrDatos['sMsjError'] = 'El archivo seleccionado debe ser en formato PDF';

            // validamos el tamaño de archivo
            }elseif($file_upload and $arrFILE['size']>$this->max_size_file){
                $this->arrDatos['sMsjError'] = 'The image size can not exceed '.$this->str_max_size_file;
            # --- fin validaciones del archivo a subir..

            }else{
                if($id=$this->modelo->registrar($this->arrDatos)){
                    # solo procede si se subio el archivo
                    if($file_upload){
                        $top_name  = generar_url($this->arrDatos['txtNombre_en']);
                        // subimos el archivo
                        $archivo   = procesar_archivo($arrFILE,('catalog/'.$id.'/'),array('top_name'=>$top_name));
                        
                        // actualizamos el archivo en el registro
                        $this->modelo->set_file($id,$archivo);
                        // -- fin del procesamiento
                    }
                    
                    $this->session->set_userdata('result',1);
                    $this->session->set_userdata('id_catalogs',$id);

                    redirect('panel_catalogs');
                }else{
                    $this->arrDatos['sMsjError'] = lang('msjerror_register');
                }
            }
        }else{
            $this->arrDatos['selLinea']      = '';
            $this->arrDatos['txtNombre_en']      = '';
            $this->arrDatos['txtNombre_es']      = '';
            $this->arrDatos['txtLinkExterno']    = '';
        }
        
        $this->arrDatos['vista'] = 'panel_catalogs_ins_v';
        $this->load->view('panel/includes/template',$this->arrDatos);
    }

    // se encarga de todo el proceso de validacion y 
    // llamado a la modificacion en la DB
    public function upd($data=''){        
       // echo '<pre>',print_r($_POST),'</pre><pre>',print_r($_FILES),'</pre>';

        // este parametro es obligatorio, debe ser el id del registro
        if(!empty($data)){
            // forzamos el valor integer
            $id = (int)$data;
            
            // hicieron click en el boton de Edit...
            if($this->input->post('btEditar')){

                $arrValidaciones = array(
                    array(
                        'field' => 'linea',
                        'label' => 'Linea',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtNombre_en',
                        'label' => 'Nombre Ingles',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtNombre_es',
                        'label' => 'Nombre Español',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtLinkExterno',
                        'label' => 'Enlace Externo',
                        'rules' => 'max_length[255]|callback__invalida_url'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                $selLinea                = $this->input->post('linea');
                $txtNombre_en            = $this->input->post('txtNombre_en');
                $txtNombre_es            = $this->input->post('txtNombre_es');
                $txtLinkExterno          = $this->input->post('txtLinkExterno');
                $estatus                 = $this->input->post('selEstatus');

                $this->arrDatos['selLinea']          = html_escape($selLinea);
                $this->arrDatos['txtNombre_en']      = html_escape($txtNombre_en);
                $this->arrDatos['txtNombre_es']      = html_escape($txtNombre_es);
                $this->arrDatos['selEstatus']        = html_escape($estatus);
                $this->arrDatos['txtLinkExterno']    = html_escape($txtLinkExterno);
                $arrIMAGE                            = isset($_FILES['filImage']) ? $_FILES['filImage'] : '';
                $arrFILE                             = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

                # verificamos si se subio una imagen
                $image_upload = FALSE;
                if(isset($arrIMAGE['name']) and !empty($arrIMAGE['name'])){
                    $image_upload = true;
                }

                # verifivcamos si se subio el archivo
                $file_upload = FALSE;
                if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                    $file_upload = true;
                }

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id']              = (int)$this->input->post('hddId');
                
                $this->arrDatos['imagen_actual']     = $this->input->post('hddImage');
                $this->arrDatos['imagen_actual']     = html_escape($this->arrDatos['imagen_actual']);

                $this->arrDatos['file_actual']     = $this->input->post('hddFile');
                $this->arrDatos['file_actual']     = html_escape($this->arrDatos['file_actual']);

                if($this->form_validation->run() == FALSE){
                    if(form_error('txtNombre_en')){
                        $this->arrDatos['sMsjError'] = form_error('txtNombre_en');
                    }elseif(form_error('txtNombre_es')){
                        $this->arrDatos['sMsjError'] = form_error('txtNombre_es');
                   }elseif(form_error('txtLinkExterno')){
                        $this->arrDatos['sMsjError'] = form_error('txtLinkExterno');
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }elseif(form_error('selLinea')){
                        $this->arrDatos['sMsjError'] = form_error('selLinea');
                    }
                
                # **************************************************************
                # validamos el tipo de imagen **********************************
                # **************************************************************
                }elseif($image_upload and !in_array($arrIMAGE['type'],array('image/jpeg','image/png','image/gif','image/jpeg'))){
                    $this->arrDatos['sMsjError'] = 'Sólo se permiten las extensiones de imágenes JPG,PNG,JPEG,GIF';

                // validamos el tamaño de archivo
                }elseif($image_upload and $arrIMAGE['size']>$this->max_size_image){
                    $this->arrDatos['sMsjError'] = 'El tamaño de la imagen no puede exceder de '.$this->str_max_size_image;
                # fin validaciones de la imagen a subir ************************

                # **************************************************************
                # validamos el tipo de archivo *********************************
                # **************************************************************
                }elseif(empty($txtLinkExterno) and !$file_upload and !$this->arrDatos['file_actual']){
                    $this->arrDatos['sMsjError'] = 'Debe seleccionar un archivo PDF o introducir una ruta externa';

                }elseif($file_upload and !in_array($arrFILE['type'],array('application/pdf','application/x-pdf'))){
                    $this->arrDatos['sMsjError'] = 'El archivo seleccionado debe ser en formato PDF';

                // validamos el tamaño de archivo
                }elseif($file_upload and $arrFILE['size']>$this->max_size_file){
                    $this->arrDatos['sMsjError'] = 'El tamaño del archivo no puede exceder de '.$this->str_max_size_file;
                # fin validaciones del archivo a subir *************************

                }else{
                    // obtenemos el nombre del artista y lo pasamos para renombrar las imagenes
                    $top_name = generar_url($this->arrDatos['txtNombre_en']);
                        
                    // si seleccionaron una imagen. la subimos
                    if($image_upload){
                        // subimos la nueva imagen
                        $this->arrDatos['Imagen'] = procesar_imagenes($arrIMAGE,('catalogos/'. $this->arrDatos['id']  .'/'),FALSE,array('top_name'=>$top_name));
                    }

                    // si seleccionaron un archivo
                    if($file_upload){
                        // eliminamos los archivos actuales
                        delete_dir_and_file('./downloads/catalog/'.$this->arrDatos['id']);

                        // subimos el archivo
                        $this->arrDatos['archivo'] = procesar_archivo($arrFILE,('catalog/'.$this->arrDatos['id'].'/'),array('top_name'=>$top_name));
                    }

                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_catalogs');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_catalogs/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']                = $id;
                    $this->arrDatos['selLinea']          = $arrDatos['linea'];
                    $this->arrDatos['txtNombre_en']      = $arrDatos['nombre_en'];
                    $this->arrDatos['txtNombre_es']      = $arrDatos['nombre_es'];
                    $this->arrDatos['file_actual']       = $arrDatos['ruta_interna'];
                    $this->arrDatos['txtLinkExterno']    = $arrDatos['ruta_externa'];
                    $this->arrDatos['imagen_actual']     = $arrDatos['path_img'];
                    $this->arrDatos['selEstatus']        = $arrDatos['estatus'];
                    //echo '<pre>',print_r($arrDatos),'</pre>';exit;
                    
                    // cuando actualizamos, recargamos la web con los nuevos datos
                    // en ese caso mostramos un mensaje de confirmacion
                    if($this->session->userdata('submit')==1){
                        $this->arrDatos['sMsjConf'] = $this->session->userdata('msj');
                        // reset
                        $this->session->set_userdata('submit',0);
                    }
                }
            }

            $this->arrDatos['title'] = 'Edit Product Type';
            $this->arrDatos['vista'] = 'panel_catalogs_upd_v';
            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_catalogs');
	    $this->load->view('panel/includes/template',$this->arrDatos);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';
        }else{
            redirect('panel_inicio');
        }
    }

    // gestiona la eliminacion
    function del($id=''){
        // si esta vacio el parametros, lo enviamos al admin
        $id = (int)$id;
        // solo los administradores y operadores pueden eliminar
        if(empty($id)){
            // indicamos el tipo de problema y redireccionamos
            $this->session->set_userdata('submit',2);
            $this->session->set_userdata('msj','¡Incorrect parameters.!');
        }elseif(!user_is_admin() and !user_is_operador()){
            // indicamos el tipo de problema y redireccionamos
            $this->session->set_userdata('submit',2);
            $this->session->set_userdata('msj','¡Your account does not have permission to delete data!');  
        }else{
            // eliminamos el registro de la base de datos
            $this->modelo->delete($id);
            // indicamos que se elimino el registro
            $this->session->set_userdata('submit',1);
            $this->session->set_userdata('msj','¡Successfully delete data');            
        }
        redirect('panel_catalogs');
    }

    function _invalida_url($url){
        if(empty($url)){return TRUE;}
        $pattern='|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i';
        if(preg_match($pattern, $url) > 0){
            return TRUE;
        }else{
            $this->form_validation->set_message('_invalida_url', 'La URL es incorrecta, debe ser del tipo <b>http://servidor.com/file.pdf</b>');
            return FALSE;
        }
    }
}
