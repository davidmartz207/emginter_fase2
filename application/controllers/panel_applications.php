<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_applications extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation','upload','pagination','table'));
        $this->load->model('panel_applications_m','modelo');
        $this->arrDatos['title']     = 'Panel Products applications';
        $this->arrDatos['vista']     = 'panel_applications_ins_v';
        $this->arrDatos['sMsjError'] = '';
        $this->id_producto             = $this->modelo->get_field('id_producto');//$this->session->flashdata('id_producto');
        $this->id_producto             = (int)$this->id_producto;
        $this->arrDatos['id_producto'] = $this->id_producto;
        if(empty($this->id_producto)){
            redirect('panel_products');
        }
        $arrProducto           = get_data_producto($this->id_producto);
        $this->arrDatos['url'] = $arrProducto['url_post'];
        $this->arrDatos['sku'] = $arrProducto['sku'];
    }

    public function index(){
        // mensaje que viene del modulo de prodcutos
        if($msjConf = $this->session->userdata('msjconf_register_product')){
            $this->session->set_userdata('msjconf_register_product','');
            $this->arrDatos['sMsjConf'] = $msjConf;
            $this->session->set_userdata('submit',0);
        }
        
        #inicio de envio de mensajes
        // enviamos un mensaje de confirmacion personalizado si existe
        if($this->session->userdata('submit')==1){
            $this->arrDatos['sMsjConf'] = $this->session->userdata('msj');

        // enviamos un mensaje de error personalizado si existe
        }elseif($this->session->userdata('submit')==2){
            $this->arrDatos['sMsjError'] = $this->session->userdata('msj');

        // enviamos un mensaje de advertencia personalizado si existe
        }elseif($this->session->userdata('submit')==3){
            $this->arrDatos['sMsjWarning'] = $this->session->userdata('msj');
        }
        $this->session->set_userdata('msj','');
        $this->session->set_userdata('submit',0);
        #fin de envio de mensajes

        // configuracion de la tabla
        $config['base_url']            = site_url('panel_applications').'/index/';
        $config['total_rows']          = $this->modelo->get_rows($this->id_producto);
        $config['per_page']            = 50;// rows por tabla
        $config['uri_segment']         = 4;// de donde saca el #de pagina

        // controla el ordenamiento de la tabla
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_order_by')){
            $selOrderBy = $this->session->userdata('panel_order_by');
        }else{
            $this->session->set_userdata('panel_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_selUpDown')){
            $selUpDown = $this->session->userdata('panel_selUpDown');
        }else{
            $this->session->set_userdata('panel_selUpDown',$selUpDown);
        }
        // desde donde comienza la consulta segun la paginacion
        $desde                         = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
        // se obtienen los datos con los filtros iniciales
        $this->arrDatos['arrDatos']    = $this->modelo->get_datos($this->id_producto,$config['per_page'],$desde,$selOrderBy,$selUpDown);
        //echo 'id: '.$this->id_artista.' --> <pre>',print_r($this->arrDatos['arrDatos']),'</pre>';

        // configuracion de la paginacion
        $config['num_links']            = round($config['total_rows']/$config['per_page']);
        //$config['page_query_string']    = false;
        //$config['use_page_numbers']     = false;
        //$config['query_string_segment'] = '';//'page';
        $config['first_link']           = false;
        $config['last_link']            = false;
        $config['full_tag_open']        = '<ul class="pagination">';
        $config['full_tag_close']       = '</ul>';
        $config['first_link']           = '&laquo; Primero';
        $config['first_tag_open']       = '<li>';
        $config['first_tag_close']      = '</li>';
        $config['last_link']            = 'Último &raquo;';
        $config['last_tag_open']        = '<li>';
        $config['last_tag_close']       = '</li>';
        $config['next_link']            = 'Próximo <span class="fa fa-arrow-circle-o-right"></span>';
        $config['next_tag_open']        = '<li>';
        $config['next_tag_close']       = '</li>';
        $config['prev_link']            = '<span class="fa fa-arrow-circle-o-left"></span> Anterior';
        $config['prev_tag_open']        = '<li>';
        $config['prev_tag_close']       = '</li>';
        $config['cur_tag_open']         = '<li class="active"><a href="#">';
        $config['cur_tag_close']        = '</a></li>';
        $config['num_tag_open']         = '<li>';
        $config['num_tag_close']        = '</li>';
        //$config['display_pages']        = FALSE;
        //$config['anchor_class']         = 'follow_link';
        #-------------------------------------------------------------
        // se inicializa la clase con la configuracion
        $this->pagination->initialize($config);
        // se enviara la paginacion en html a la vista
        $this->arrDatos['pagination']  = $this->pagination->create_links();

        // indico en que controlador estoy..
        $this->session->set_userdata('controller','panel_applications');
	$this->load->view('panel/includes/template',$this->arrDatos);
    }
    
    function _valid_marcas($cadena){
        //por no ser obligatorio, no validamos si esta vacio
        if(empty($cadena)){
           $this->form_validation->set_message('_valid_marcas', 'Debe seleccionar una Marca.');
           return FALSE;
         }else{
           return TRUE;
         }
    }
    
    function _valid_tipo_motor($cadena){
        //por no ser obligatorio, no validamos si esta vacio
        if(empty($cadena)){
           $this->form_validation->set_message('_valid_tipo_motor', 'Debe seleccionar un Tipo de Motor.');
           return FALSE;
         }else{
           return TRUE;
         }
    }

    public function ins(){        
        //echo '<pre>',print_r($_POST),'</pre>';
        //echo '<pre>',print_r($_FILES),'</pre>';exit;
        $arrValidaciones = array(
            array(
                'field' => 'selMarcas',
                'label' => 'Marca',
                'rules' => 'required|integer|callback__valid_marcas'
            ),
            array(
                'field' => 'selModelos',
                'label' => 'Modelo',
                'rules' => 'integer'
            ),
            array(
                'field' => 'selTiposMotores',
                'label' => 'Tipo de Motor',
                'rules' => 'required|integer|callback__valid_tipo_motor'
            ),
            array(
                'field' => 'txtYears',
                'label' => 'Años',
                'rules' => 'required|max_length[255]'
            )
        );
        $this->form_validation->set_rules($arrValidaciones);
        $this->arrDatos['selMarcas']            = $this->input->post('selMarcas');
        $this->arrDatos['selModelos']           = $this->input->post('selModelos');
        $this->arrDatos['selTiposMotores']      = $this->input->post('selTiposMotores');
        $this->arrDatos['txtYears']             = $this->input->post('txtYears');
        $this->arrDatos['id_producto']          = $this->id_producto;

        $this->arrDatos['selMarcas']            = (int)$this->arrDatos['selMarcas'];
        $this->arrDatos['selModelos']           = (int)$this->arrDatos['selModelos'];
        $this->arrDatos['selTiposMotores']      = (int)$this->arrDatos['selTiposMotores'];
                
        if($this->form_validation->run() == FALSE){
            if(form_error('selMarcas')){
                $this->arrDatos['sMsjError'] = form_error('selMarcas');
            }elseif(form_error('selModelos')){
                $this->arrDatos['sMsjError'] = form_error('selModelos');
            }elseif(form_error('selTiposMotores')){
                $this->arrDatos['sMsjError'] = form_error('selTiposMotores');
            }elseif(form_error('txtYears')){
                $this->arrDatos['sMsjError'] = form_error('txtYears');  
            }
        }else{
            if($this->modelo->registrar($this->arrDatos)){
                //$this->session->set_userdata('id_artista',$id);ya esta circulando
                $this->session->set_userdata('controller','panel_applications');
                $this->session->set_userdata('submit',1);
                $this->session->set_userdata('msj','¡Data successfully added!');
                redirect('panel_applications');
            }else{
                $this->arrDatos['sMsjError'] = 'Unable to added.';
            }
        }
        $this->arrDatos['title'] = 'Create Applications';
        $this->arrDatos['vista'] = 'panel_applications_ins_v';
        $this->index();
    }

    // se encarga de todo el proceso de validacion y 
    // llamado a la modificacion en la DB
    public function upd($data=''){        
        //echo '<pre>',print_r($_POST),'</pre>';exit;
       //echo '<pre>',print_r($_FILES),'</pre>';
        
        // este parametro es obligatorio, debe ser el id del registro
        if(!empty($data)){
            // para el select de la vista
            $this->arrDatos['arrEstatus'] = array(1 => 'Enabled ',0 => 'Disabled');
            
            // forzamos el valor integer
            $id                           = (int)$data;
            
            // hicieron click en el boton de Edit...
            if($this->input->post('btEditar')){
                $arrValidaciones = array(
                    array(
                        'field' => 'selMarcas',
                        'label' => 'Marca',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'selModelos',
                        'label' => 'Modelo',
                        'rules' => 'integer'
                    ),
                    array(
                        'field' => 'selTiposMotores',
                        'label' => 'Tipo de Motor',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'txtYears',
                        'label' => 'Años',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                $this->arrDatos['selMarcas']            = $this->input->post('selMarcas');
                $this->arrDatos['selModelos']           = $this->input->post('selModelos');
                $this->arrDatos['selTiposMotores']      = $this->input->post('selTiposMotores');
                $this->arrDatos['txtYears']             = $this->input->post('txtYears');
                $this->arrDatos['id_producto']          = $this->id_producto;
                $this->arrDatos['estatus']              = $this->input->post('selEstatus');

                $this->arrDatos['selMarcas']            = (int)$this->arrDatos['selMarcas'];
                $this->arrDatos['selModelos']           = (int)$this->arrDatos['selModelos'];
                $this->arrDatos['selTiposMotores']      = (int)$this->arrDatos['selTiposMotores'];

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id']                 = (int)$this->input->post('hddId');
                
                //
                $this->arrDatos['url']                = $this->input->post('hddUrl');
                
                if($this->form_validation->run() == FALSE){
                    if(form_error('selMarcas')){
                        $this->arrDatos['sMsjError'] = form_error('selMarcas');
                    }elseif(form_error('selModelos')){
                        $this->arrDatos['sMsjError'] = form_error('selModelos');
                    }elseif(form_error('selTiposMotores')){
                        $this->arrDatos['sMsjError'] = form_error('selTiposMotores');
                    }elseif(form_error('txtYears')){
                        $this->arrDatos['sMsjError'] = form_error('txtYears');
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }
                }else{
                    
                    $this->arrDatos['id_producto'] = $this->id_producto;

                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_applications');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_applications/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']                  = $id;
                    $this->arrDatos['url']                 = $arrDatos['url'];

                    $this->id_producto                     = $arrDatos['id_producto'];
                    $this->arrDatos['id_producto']         = $this->id_producto;

                    $this->session->set_userdata('sku',$arrDatos['sku']);
                    $this->arrDatos['sku']                 = $arrDatos['sku'];

                    $this->arrDatos['selMarcas']           = $arrDatos['id_marca'];
                    $this->arrDatos['selModelos']          = $arrDatos['id_modelo'];
                    $this->arrDatos['selTiposMotores']     = $arrDatos['id_tipo_motor'];
                    $this->arrDatos['txtYears']            = $arrDatos['years'];
                
                    $this->arrDatos['selEstatus']          = $arrDatos['estatus'];
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

            $this->arrDatos['title'] = 'Edit Application';
            $this->arrDatos['vista'] = 'panel_applications_upd_v';
            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_applications');
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
        redirect('panel_applications');
    }
}