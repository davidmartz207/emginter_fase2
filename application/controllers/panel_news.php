<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_news extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_news_m','modelo');
        $this->arrDatos['title']     = 'Panel News';
        $this->arrDatos['vista']     = 'panel_news_list_v';
        $this->arrDatos['sMsjError'] = '';

        // tamaño maximo de las imagenes a subir
        $this->max_size_file     = 3000000;#2mb
        $this->str_max_size_file = '3MB';
        $this->width_image       = 800;#px
        $this->hight_image       = 800;#px;
    }
     
    public function index(){
        $config['base_url']            = site_url('panel_news').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_news_order_by')){
            $selOrderBy = $this->session->userdata('panel_news_order_by');
        }else{
            $this->session->set_userdata('panel_news_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_news_selUpDown')){
            $selUpDown = $this->session->userdata('panel_news_selUpDown');
        }else{
            $this->session->set_userdata('panel_news_selUpDown',$selUpDown);
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
    
    public function ins_ajax(){
        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';      
        if($this->input->post('btAjax')){
            $arrValidaciones = array(
                array(
                    'field' => 'txtTitulo_en',
                    'label' => 'Título Inglés',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtTitulo_es',
                    'label' => 'Título Español',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtTexto_es',
                    'label' => 'Texto Español',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtTexto_en',
                    'label' => 'Texto Inglés',
                    'rules' => 'required|max_length[255]'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $this->arrDatos['txtTitulo_en']         = $this->input->post('txtTitulo_en');
            $this->arrDatos['txtTitulo_es']         = $this->input->post('txtTitulo_es');
            $this->arrDatos['txtTexto_en']         = $this->input->post('txtTexto_en');
            $this->arrDatos['txtTexto_es']         = $this->input->post('txtTexto_es');
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            if($this->form_validation->run() == FALSE){
                if(form_error('txtTitulo_en')){
                    echo 'error|'.form_error('txtTitulo_en');
                }elseif(form_error('txtTitulo_es')){
                    echo 'error|'.form_error('txtTitulo_es');
                }elseif(form_error('txtTexto_es')){
                    echo 'error|'.form_error('txtTexto_es');
                }elseif(form_error('txtTexto_en')){
                    echo 'error|'.form_error('txtTexto_en');
                }
            }else{                
                if($this->modelo->registrar($this->arrDatos)){
                    echo 'conf|Registrado con éxito.';
                }else{
                    echo 'error|'.lang('msjerror_register');
                }
            }
        }
    }

    public function ins(){
        if($this->input->post('btSubmit')){
            $arrValidaciones = array(
                array(
                    'field' => 'txtTitulo_en',
                    'label' => 'Título Inglés',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtTitulo_es',
                    'label' => 'Título Español',
                    'rules' => 'required|max_length[255]'
                ),
                array(
                    'field' => 'txtTexto_es',
                    'label' => 'Texto Español',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'txtTexto_en',
                    'label' => 'Texto Inglés',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $txtTitulo_en            = $this->input->post('txtTitulo_en');
            $txtTitulo_es            = $this->input->post('txtTitulo_es');
            $txtTexto_en            = $this->input->post('txtTexto_en');
            $txtTexto_es            = $this->input->post('txtTexto_es');
            $arrFILE                 = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';

            # verifivcamos si se subio una imagen
            $image_upload = FALSE;
            if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                $image_upload = true;
            }


            $this->arrDatos['txtTitulo_en']         = html_escape($txtTitulo_en);
            $this->arrDatos['txtTitulo_es']         = html_escape($txtTitulo_es);
            $this->arrDatos['txtTexto_en']         = html_escape($txtTexto_en);
            $this->arrDatos['txtTexto_es']         = html_escape($txtTexto_es);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            if($this->form_validation->run() == FALSE){
                if(form_error('txtTitulo_en')){
                    $this->arrDatos['sMsjError'] = form_error('txtTitulo_en');
                }elseif(form_error('txtTitulo_es')){
                    $this->arrDatos['sMsjError'] = form_error('txtTitulo_es');
                }elseif(form_error('txtTexto_en')){
                    $this->arrDatos['sMsjError'] = form_error('txtTexto_en');
                }elseif(form_error('txtTexto_es')){
                    $this->arrDatos['sMsjError'] = form_error('txtTexto_es');
                }
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
                        $top_name  = generar_url("pt".$id);
                        // subimos la imagen
                        $image     = procesar_imagenes($arrFILE,('news/pt'.$id.'/'),FALSE,array('top_name'=>$top_name));

                        // actualizamos la imagen al perfil
                        $this->modelo->set_image($id,$image);
                        // -- fin del procesamiento de imagen
                    }


                    $this->session->set_userdata('msjconf_register','Registrado con éxito.');
                    $this->session->set_userdata('id_news',$id);

                    redirect('panel_news');
                }else{
                    $this->arrDatos['sMsjError'] = lang('msjerror_register');
                }
            }
        }else{
            $this->arrDatos['txtTitulo_en']      = '';
            $this->arrDatos['txtTitulo_es']      = '';
        }
        
        $this->arrDatos['vista'] = 'panel_news_ins_v';
        $this->load->view('panel/includes/template',$this->arrDatos);
    }

    // se encarga de todo el proceso de validacion y 
    // llamado a la modificacion en la DB
    public function upd($data=''){        

        // este parametro es obligatorio, debe ser el id del registro
        if(!empty($data)){
            // forzamos el valor integer
            $id = (int)$data;
            
            // hicieron click en el boton de Edit...
            if($this->input->post('btEditar')){
                $arrValidaciones = array(
                    array(
                        'field' => 'txtTitulo_en',
                        'label' => 'Título Inglés',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtTitulo_es',
                        'label' => 'Título Español',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                $txtTitulo_en            = $this->input->post('txtTitulo_en');
                $txtTitulo_es            = $this->input->post('txtTitulo_es');
                $txtTexto_es            = $this->input->post('txtTexto_es');
                $txtTexto_en            = $this->input->post('txtTexto_en');
                $estatus                             = $this->input->post('selEstatus');

                $this->arrDatos['txtTitulo_en']      = html_escape($txtTitulo_en);
                $this->arrDatos['txtTitulo_es']      = html_escape($txtTitulo_es);
                $this->arrDatos['txtTexto_es']      = html_escape($txtTexto_es);
                $this->arrDatos['txtTexto_en']      = html_escape($txtTexto_en);
                $this->arrDatos['selEstatus']        = html_escape($estatus);
                $arrFILE                 = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';

                # verificamos si se subio una imagen
                $image_upload = FALSE;
                if(isset($arrFILE['name']) and !empty($arrFILE['name'])){
                    $image_upload = true;
                }

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id']                = (int)$this->input->post('hddId');

                if($this->form_validation->run() == FALSE){
                    if(form_error('txtTitulo_en')){
                        $this->arrDatos['sMsjError'] = form_error('txtTitulo_en');
                    }elseif(form_error('txtTitulo_es')){
                        $this->arrDatos['sMsjError'] = form_error('txtTitulo_es');
                    }elseif(form_error('txtTexto_es')){
                        $this->arrDatos['sMsjError'] = form_error('txtTexto_es');
                    }elseif(form_error('txtTexto_en')){
                        $this->arrDatos['sMsjError'] = form_error('txtTexto_en');
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
                        $top_name  = generar_url("pt".$id);
                        // subimos la imagen
                        $image     = procesar_imagenes($arrFILE,('news/pt'.$id.'/'),FALSE,array('top_name'=>$top_name));

                        // actualizamos la imagen al perfil
                        $this->modelo->set_image($id,$image);
                        // -- fin del procesamiento de imagen
                    }


                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_news');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_news/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']             = $id;
                    $this->arrDatos['txtTitulo_en']      = $arrDatos['titulo_en'];
                    $this->arrDatos['txtTitulo_es']      = $arrDatos['titulo_es'];
                    $this->arrDatos['txtTexto_en']      = $arrDatos['texto_en'];
                    $this->arrDatos['txtTexto_es']      = $arrDatos['texto_es'];
                    $this->arrDatos['selEstatus']     = $arrDatos['estatus'];
                    $this->arrDatos['imagen_actual']     = $arrDatos['path_img'];

                    // cuando actualizamos, recargamos la web con los nuevos datos
                    // en ese caso mostramos un mensaje de confirmacion
                    if($this->session->userdata('submit')==1){
                        $this->arrDatos['sMsjConf'] = $this->session->userdata('msj');
                        // reset
                        $this->session->set_userdata('submit',0);
                    }
                }
            }

            $this->arrDatos['title'] = 'Edit News';
            $this->arrDatos['vista'] = 'panel_news_upd_v';

            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_news');
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
        redirect('panel_news');
    }
}