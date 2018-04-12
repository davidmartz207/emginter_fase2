<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_banners extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_banners_m','modelo');
        $this->arrDatos['title']     = 'Panel Products';
        $this->arrDatos['vista']     = 'panel_banners_list_v';
        $this->arrDatos['sMsjError'] = '';
        
        // tamaño maximo de las imagenes a subir
        $this->max_size_file     = 3000000;#2mb
        $this->str_max_size_file = '3MB';
        $this->width_image       = 300;#px
        $this->hight_image       = 300;#px;
        
        $this->arrDatos['arrIdiomas'] = get_idiomas();
    }
     
    public function index(){
        $config['base_url']            = site_url('panel_banners').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_banners_order_by')){
            $selOrderBy = $this->session->userdata('panel_banners_order_by');
        }else{
            $this->session->set_userdata('panel_banners_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_banners_selUpDown')){
            $selUpDown = $this->session->userdata('panel_banners_selUpDown');
        }else{
            $this->session->set_userdata('panel_banners_selUpDown',$selUpDown);
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
        
        $this->arrDatos['arrTiposProductos'] = get_tipos_productos();        
        if($this->input->post('btSubmit')){
            $arrValidaciones = array(
                array(
                    'field' => 'selIdiomas',
                    'label' => 'idioma',
                    'rules' => 'required|integer'
                ),
                array(
                    'field' => 'txtNombre',
                    'label' => 'Nombre',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtEnlace',
                    'label' => 'Enlace',
                    'rules' => 'required|max_length[255]'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $selIdiomas           = $this->input->post('selIdiomas');
            $txtNombre            = $this->input->post('txtNombre');
            $txtEnlace            = $this->input->post('txtEnlace');

            $this->arrDatos['selIdiomas']        = (int)$selIdiomas;
            $this->arrDatos['txtNombre']         = html_escape($txtNombre);
            $this->arrDatos['txtEnlace']         = html_escape($txtEnlace);
            $arrFILE                             = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            # verifivcamos si se subiouna imagen
            $image_upload = false;
            if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                $image_upload = true;
            }
            
            if($this->form_validation->run() == FALSE){
                if(form_error('selIdiomas')){
                    $this->arrDatos['sMsjError'] = form_error('selIdiomas');
                }elseif(form_error('txtNombre')){
                    $this->arrDatos['sMsjError'] = form_error('txtNombre');
                }elseif(form_error('txtEnlace')){
                    $this->arrDatos['sMsjError'] = form_error('txtEnlace');
                }

            // valid upload image
            }elseif(!$image_upload){
                $this->arrDatos['sMsjError'] = 'The image field is required';

            // validamos el tipo de archivo
            }elseif($image_upload and !in_array($arrFILE['type'],array('image/jpeg','image/png','image/gif','image/jpeg'))){
                $this->arrDatos['sMsjError'] = 'Only image formats are allowed JPG,PNG,JPEG,GIF';

            // validamos el tamaño de archivo
            }elseif($image_upload and $arrFILE['size']>$this->max_size_file){
                $this->arrDatos['sMsjError'] = 'The image size can not exceed '.$this->str_max_size_file;
            # --- fin validaciones del archivo a subir..

            }else{                
                if($id=$this->modelo->registrar($this->arrDatos)){
                    
                    // -- procesamos la imagen
                    # solo procede si se subio una imagen
                    if($image_upload){
                        $top_name                 = generar_url($txtNombre.'-'.$selIdiomas);
                        // subimos la imagen
                        $image                    = procesar_imagenes($arrFILE,('banners/'.$id.'/'),false,array('top_name'=>$top_name));
                        
                        // actualizamos la imagen al perfil
                        $this->modelo->set_image($id,$image);
                        // -- fin del procesamiento de imagen
                    }

                    $this->session->set_userdata('msjconf_register','Imagen registrada con éxito.');
                    $this->session->set_userdata('id_banner',$id);
                    
                    # nos vamos al modulo de aplicaciones de productos
                    redirect('panel_banners');
                }else{
                    $this->arrDatos['sMsjError'] = lang('msjerror_register');
                }
            }
        }else{
            $this->arrDatos['selIdiomas']     = '';
            $this->arrDatos['txtNombre']      = '';
            $this->arrDatos['txtEnlace']      = '';
        }
        
        $this->arrDatos['vista'] = 'panel_banners_ins_v';
        $this->load->view('panel/includes/template',$this->arrDatos);
    }

    // se encarga de todo el proceso de validacion y 
    // llamado a la modificacion en la DB
    public function upd($data=''){        
        //echo '<pre>',print_r($_POST),'</pre>';exit;
       //echo '<pre>',print_r($_FILES),'</pre>';

        // este parametro es obligatorio, debe ser el id del registro
        if(!empty($data)){
            // forzamos el valor integer
            $id = (int)$data;
            
            // hicieron click en el boton de Edit...
            if($this->input->post('btEditar')){
                $arrValidaciones = array(
                    array(
                        'field' => 'selIdiomas',
                        'label' => 'idioma',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'txtNombre',
                        'label' => 'Nombre',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtEnlace',
                        'label' => 'Enlace',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);

                $selIdiomas           = $this->input->post('selIdiomas');
                $txtNombre            = $this->input->post('txtNombre');
                $txtEnlace            = $this->input->post('txtEnlace');
                $estatus                             = $this->input->post('selEstatus');

                $this->arrDatos['selIdiomas']        = (int)$selIdiomas;
                $this->arrDatos['txtNombre']         = html_escape($txtNombre);
                $this->arrDatos['txtEnlace']         = html_escape($txtEnlace);
                $this->arrDatos['selEstatus']        = html_escape($estatus);
                $arrFILE                             = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

                # verificamos si se subio una imagen
                $image_upload = false;
                if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                    $image_upload = true;
                }

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id']                = (int)$this->input->post('hddId');
                $this->arrDatos['imagen_actual']     = $this->input->post('hddImage');
                $this->arrDatos['imagen_actual']     = html_escape($this->arrDatos['imagen_actual']);

                if($this->form_validation->run() == FALSE){
                    if(form_error('selIdiomas')){
                        $this->arrDatos['sMsjError'] = form_error('selIdiomas');
                    }elseif(form_error('txtNombre')){
                        $this->arrDatos['sMsjError'] = form_error('txtNombre');
                    }elseif(form_error('txtEnlace')){
                        $this->arrDatos['sMsjError'] = form_error('txtEnlace');
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }
                    
                // validamos el tipo de archivo
                }elseif($image_upload and !in_array($arrFILE['type'],array('image/jpeg','image/png','image/gif','image/jpeg'))){
                    $this->arrDatos['sMsjError'] = 'Only image formats are allowed JPG,PNG,JPEG,GIF';

                // validamos el tamaño de archivo
                }elseif($image_upload and $arrFILE['size']>$this->max_size_file){
                    $this->arrDatos['sMsjError'] = 'The image size can not exceed '.$this->str_max_size_file;
                # --- fin validaciones del archivo a subir..

                }else{
                     // si seleccionaron una imagen. la subimos
                    if($image_upload){
                        // obtenemos el nombre del artista y lo pasamos para renombrar las imagenes
                        $top_name = generar_url($txtNombre.'-'.$id);

                        // subimos la nueva imagen
                        $this->arrDatos['Imagen'] = procesar_imagenes($arrFILE,('banners/'.$id.'/'),false,array('top_name'=>$top_name));
                    }

                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_banners');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_banners/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']             = $id;
                    $this->arrDatos['selIdiomas']     = $arrDatos['id_idioma'];
                    $this->arrDatos['txtNombre']      = $arrDatos['nombre'];
                    $this->arrDatos['txtEnlace']      = $arrDatos['enlace'];
                    $this->arrDatos['imagen_actual']  = $arrDatos['path_img'];
                    $this->arrDatos['selEstatus']     = $arrDatos['estatus'];
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

            $this->arrDatos['title'] = 'Edit Image';
            $this->arrDatos['vista'] = 'panel_banners_upd_v';
            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_banners');
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
        redirect('panel_banners');
    }
}