<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_articulos extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_articulos_m','modelo');
        $this->arrDatos['title']      = 'Contenidos';
        $this->arrDatos['vista']      = 'panel_articulos_v';
        $this->arrDatos['sMsjError']  = '';
        $this->arrDatos['sMsjConf']   = '';
        $this->arrDatos['restringir'] =  0;
        $this->arrDatos['limit_caracteres']   = 60000;
        $this->arrDatos['arrTiposContenidos'] = array('1'=>'Texto del Home');//$this->modelo->get_tipos_contenidos();
        
        // tamaño maximo de las imagenes a subir
        $this->max_size_file     = 3000000;#2mb
        $this->str_max_size_file = '3MB';
        $this->width_image       = 300;#px
        $this->hight_image       = 300;#px;

        if(!user_is_admin() and !user_is_operador()){
            $this->arrDatos['restringir'] = (int)get_id_usuario();
        }

        # ckEditor *************************************************************
        # fuente: http://nukium.com/developpement/php-frameworks/codeigniter/ckeditor-helper-for-codeigniter/
        $this->load->helper('ckeditor');
        //Ckeditor's configuration
	$this->arrDatos['ckeditor_en'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaDescripcion_en',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'400px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_es'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaDescripcion_es',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'400px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        # ckEditor *************************************************************
    }
     
    public function index(){
        $config['base_url']            = site_url('panel_articulos').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 50;
        $config['uri_segment']         = 4;
        if($this->session->userdata('result')==1){
            $this->arrDatos['sMsjConf'] = 'Creado con éxito.';
        }elseif($this->session->userdata('result')==2){
            $this->arrDatos['sMsjConf'] = 'Actualizado con éxito.';
        }elseif($this->session->userdata('result')==3){
            $this->arrDatos['sMsjConf'] = 'Eliminado con éxito.';
        }
        $this->session->set_userdata('result',0);

        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_articulos_order_by')){
            $selOrderBy = $this->session->userdata('panel_articulos_order_by');
        }else{
            $this->session->set_userdata('panel_articulos_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_articulos_selUpDown')){
            $selUpDown = $this->session->userdata('panel_articulos_selUpDown');
        }else{
            $this->session->set_userdata('panel_articulos_selUpDown',$selUpDown);
        }
        $desde                         = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
        $this->arrDatos['arrDatos']    = $this->modelo->get_datos($this->arrDatos['restringir'],$config['per_page'],$desde,$selOrderBy,$selUpDown);

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
        
        //echo '<pre>',print_r($this->arrDatos),'</pre>';
	$this->load->view('panel/includes/template',$this->arrDatos);
    }
    
    public function ins(){
        //echo '<pre>',print_r($_POST),'</pre>';
        $index = false;
        if($this->input->post('btSubmit')){
            $arrValidaciones = array(
                array(
                    'field' => 'selTiposContenidos',
                    'label' => 'Tipo de Contenido',
                    'rules' => 'required|integer'
                ),
                array(
                    'field' => 'txtAsunto_en',
                    'label' => 'Título en Ingles',
                    'rules' => 'required|max_length[255]|callback__valida_cadena'
                ),
                array(
                    'field' => 'txtaDescripcion_en',
                    'label' => 'Contenido en Ingles',
                    'rules' => 'required|min_length[3]|max_length['.$this->arrDatos['limit_caracteres'].']'
                ),
                array(
                    'field' => 'txtAsunto_es',
                    'label' => 'Título en Español',
                    'rules' => 'required|max_length[255]|callback__valida_cadena'
                ),
                array(
                    'field' => 'txtaDescripcion_es',
                    'label' => 'Contenido en Español',
                    'rules' => 'required|min_length[3]|max_length['.$this->arrDatos['limit_caracteres'].']'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            
            # define as global - $pre_filter['key'];
            global $pre_filter;
            //$this->config->set_item('global_xss_filtering',FALSE);
                
            $this->arrDatos['selTiposContenidos']  = (int)$this->input->post('selTiposContenidos');
            $this->arrDatos['txtAsunto_en']        = html_escape($this->input->post('txtAsunto_en'));
            $this->arrDatos['txtaDescripcion_en']  = $pre_filter['txtaDescripcion_en'];
            $this->arrDatos['txtAsunto_es']        = html_escape($this->input->post('txtAsunto_es'));
            $this->arrDatos['txtaDescripcion_es']  = $pre_filter['txtaDescripcion_es'];
            $arrFILE                               = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;
            
            # verifivcamos si se subio una imagen
            $image_upload = false;
            if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                $image_upload = true;
            }

            if($this->form_validation->run() == FALSE){
                if(form_error('selTiposContenidos')){
                    $this->arrDatos['sMsjError'] = form_error('selTiposContenidos');
                }elseif(form_error('txtAsunto_en')){
                    $this->arrDatos['sMsjError'] = form_error('txtAsunto_en');
                }elseif(form_error('txtaDescripcion_en')){
                    $this->arrDatos['sMsjError'] = form_error('txtaDescripcion_en');
                }elseif(form_error('txtAsunto_ens')){
                    $this->arrDatos['sMsjError'] = form_error('txtAsunto_es');
                }elseif(form_error('txtaDescripcion_es')){
                    $this->arrDatos['sMsjError'] = form_error('txtaDescripcion_es');
                }
                
            // validamos el tipo de archivo
            }elseif(!$image_upload){
                $this->arrDatos['sMsjError'] = 'The image file is requerid';

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
                        $top_name = encode_link($this->arrDatos['txtAsunto_en'].'-'.$id);
                        // subimos la imagen
                        $image    = procesar_imagenes($arrFILE,('contenidos/'.$id.'/'),false,array('top_name'=>$top_name));
                        
                        // actualizamos la imagen al perfil
                        $this->modelo->set_image($id,$image);
                        // -- fin del procesamiento de imagen
                    }
                    
                    $this->arrDatos['selTiposContenidos'] = '';
                    $this->arrDatos['txtAsunto_en']       = '';
                    $this->arrDatos['txtaDescripcion_en'] = '';
                    $this->arrDatos['txtAsunto_es']       = '';
                    $this->arrDatos['txtaDescripcion_es'] = '';
                    $this->session->set_userdata('result',1);
                    redirect('panel_articulos');
                }else{
                    $this->arrDatos['sMsjError'] = 'Imposible agregar el registro.';
                }
            }
        }else{
            $this->arrDatos['selTiposContenidos'] = '';
            $this->arrDatos['txtAsunto_en']       = '';
            $this->arrDatos['txtaDescripcion_en'] = '';
            $this->arrDatos['txtAsunto_es']       = '';
            $this->arrDatos['txtaDescripcion_es'] = '';
        }
        if(!$index){
            $this->arrDatos['title']      = 'Crear contenido';
            $this->arrDatos['vista']      = 'panel_articulos_ins_v';
            //echo '<pre>',print_r($this->arrDatos),'</pre>';
            $this->load->view('panel/includes/template',$this->arrDatos);
        }
    }
    
    function upd($data=''){
        //echo '<pre>',print_r($_POST),'</pre>';
        if($this->session->userdata('result')==2){
            $this->arrDatos['sMsjConf'] = 'Actualizado con éxito.';
        }
        if(!empty($data)){
            $vista_edit                   = true;
            $this->arrDatos['arrEstatus'] = array(1 => 'Activo',0 => 'Inactivo');
            $id                           = (int)$data;
            if($this->input->post('btEditar')){
                //echo '<pre>',print_r($_POST),'</pre>';exit;
                $arrValidaciones = array(
                    array(
                        'field' => 'selTiposContenidos',
                        'label' => 'Tipo de Contenido',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'txtAsunto_en',
                        'label' => 'Título en Ingles',
                        'rules' => 'required|max_length[255]|callback__valida_cadena'
                    ),
                    array(
                        'field' => 'txtaDescripcion_en',
                        'label' => 'Contenido en Ingles',
                        'rules' => 'required|min_length[3]|max_length['.$this->arrDatos['limit_caracteres'].']'
                    ),
                    array(
                        'field' => 'txtAsunto_es',
                        'label' => 'Título en Español',
                        'rules' => 'required|max_length[255]|callback__valida_cadena'
                    ),
                    array(
                        'field' => 'txtaDescripcion_es',
                        'label' => 'Contenido en Español',
                        'rules' => 'required|min_length[3]|max_length['.$this->arrDatos['limit_caracteres'].']'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required|integer'
                    )
                );
                
                $this->form_validation->set_rules($arrValidaciones);
                
                # define as global - $pre_filter['key'];
                global $pre_filter;
                //$this->config->set_item('global_xss_filtering',FALSE);
            
                $this->arrDatos['id']                  = (int)$this->input->post('hddId');
                $this->arrDatos['url_post']            = html_escape($this->input->post('hddURL'));
                $this->arrDatos['imagen_actual']       = html_escape($this->input->post('hddImage'));
                $this->arrDatos['selTiposContenidos']  = (int)$this->input->post('selTiposContenidos');
                $this->arrDatos['txtAsunto_en']        = html_escape($this->input->post('txtAsunto_en'));
                $this->arrDatos['txtaDescripcion_en']  = $pre_filter['txtaDescripcion_en'];
                $this->arrDatos['txtAsunto_es']        = html_escape($this->input->post('txtAsunto_es'));
                $this->arrDatos['txtaDescripcion_es']  = $pre_filter['txtaDescripcion_es'];
                $this->arrDatos['selEstatus']          = html_escape($this->input->post('selEstatus'));
                $arrFILE                               = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;
                //echo '<pre>',print_r($arrFILE),'</pre>';exit;
                
                # verifivcamos si se subio una imagen
                $image_upload = false;
                if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                    $image_upload = true;
                }
                
                if($this->form_validation->run() == FALSE){
                    if(form_error('selTiposContenidos')){
                        $this->arrDatos['sMsjError'] = form_error('selTiposContenidos');
                    }elseif(form_error('txtAsunto_en')){
                        $this->arrDatos['sMsjError'] = form_error('txtAsunto_en');
                    }elseif(form_error('txtaDescripcion_en')){
                        $this->arrDatos['sMsjError'] = form_error('txtaDescripcion_en');
                    }elseif(form_error('txtAsunto_es')){
                        $this->arrDatos['sMsjError'] = form_error('txtAsunto_es');
                    }elseif(form_error('txtaDescripcion_es')){
                        $this->arrDatos['sMsjError'] = form_error('txtaDescripcion_es');
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
                        $top_name = encode_link($this->arrDatos['txtAsunto_en'].'-'.$id);

                        // subimos la nueva imagen
                        $this->arrDatos['Imagen'] = procesar_imagenes($arrFILE,('contenidos/'.$id.'/'),false,array('top_name'=>$top_name));
                    }

                    if($this->modelo->set_data($this->arrDatos)){
                        //$this->arrDatos['sMsjConf'] = 'Artículo actualizado con éxito.';
                        $this->session->set_userdata('result',2);
                        redirect('panel_articulos/upd/'.$id);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Imposible actualizar el articulo.';
                    }
                }
            }elseif(user_is_admin() or user_is_operador()){
                if(is_numeric($id)){#super admin
                    $arrDatos = $this->modelo->get_data($id);
                    if(is_array($arrDatos) and count($arrDatos)>0){
                        $this->arrDatos['id']                  = $id;
                        $this->arrDatos['url_post']            = $arrDatos['url_post'];
                        $this->arrDatos['txtAsunto_en']        = $arrDatos['titulo_en'];
                        $this->arrDatos['txtaDescripcion_en']  = $arrDatos['descripcion_en'];
                        $this->arrDatos['txtAsunto_es']        = $arrDatos['titulo_es'];
                        $this->arrDatos['txtaDescripcion_es']  = $arrDatos['descripcion_es'];
                        $this->arrDatos['selEstatus']          = $arrDatos['id_estatus'];
                        $this->arrDatos['imagen_actual']       = $arrDatos['path_img'];
                    }else{
                        $vista_edit = false;
                    }
                }else{
                    $vista_edit = false;
                }
            }
            if($vista_edit){
                $this->arrDatos['title'] = 'Editar contenido';
                $this->arrDatos['vista'] = 'panel_articulos_upd_v';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';
                $this->load->view('panel/includes/template',$this->arrDatos);
            }else{
                $this->index();
            }
        }else{
            $this->index();
        }
    }
    
    function del($id){
        $id = (int)$id;
        if(user_is_admin() or user_is_operador()){
            $this->modelo->eliminar_articulo($id);
            $this->session->set_userdata('result',3);
            redirect('panel_articulos');
        }        
    }

    function _valida_cadena($cadena){
        if(empty($cadena)) return;
        if(!es_correcto_nombre($cadena)){
           $this->form_validation->set_message('_valida_cadena', 'El <b>Título</b> es Inválido. No se aceptan caracteres especiales.');
           return false;
         }else{
           return true;
         }
    }
}