<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_model extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_model_m','modelo');
        $this->arrDatos['title']     = 'Panel Model';
        $this->arrDatos['vista']     = 'panel_model_list_v';
        $this->arrDatos['sMsjError'] = '';
        $this->arrDatos['arrMarcas'] = get_marcas();
    }
     
    public function index(){
        $config['base_url']            = site_url('panel_model').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_model_order_by')){
            $selOrderBy = $this->session->userdata('panel_model_order_by');
        }else{
            $this->session->set_userdata('panel_model_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_model_selUpDown')){
            $selUpDown = $this->session->userdata('panel_model_selUpDown');
        }else{
            $this->session->set_userdata('panel_model_selUpDown',$selUpDown);
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
        if($this->input->post('btSubmit')){
            $arrValidaciones = array(
                array(
                    'field' => 'selMarcas',
                    'label' => 'Marcas',
                    'rules' => 'required|integer'
                ),
                array(
                    'field' => 'txtNombre',
                    'label' => 'Nombre',
                    'rules' => 'required|max_length[255]'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $selMarcas = $this->input->post('selMarcas');
            $txtNombre = $this->input->post('txtNombre');

            $this->arrDatos['selMarcas'] = html_escape($selMarcas);
            $this->arrDatos['txtNombre'] = html_escape($txtNombre);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            if($this->form_validation->run() == FALSE){
                if(form_error('selMarcas')){
                    $this->arrDatos['sMsjError'] = form_error('selMarcas');
                }elseif(form_error('txtNombre')){
                    $this->arrDatos['sMsjError'] = form_error('txtNombre');
                }
            }else{                
                if($id=$this->modelo->registrar($this->arrDatos)){
                    $this->session->set_userdata('msjconf_register','Registrado con éxito.');
                    $this->session->set_userdata('id_model',$id);

                    redirect('panel_model');
                }else{
                    $this->arrDatos['sMsjError'] = lang('msjerror_register');
                }
            }
        }else{
            $this->arrDatos['selMarcas']  = '';
            $this->arrDatos['txtNombre']  = '';
        }
        
        $this->arrDatos['vista'] = 'panel_model_ins_v';
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
                        'field' => 'selMarcas',
                        'label' => 'Marcas',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'txtNombre',
                        'label' => 'Nombre',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required|integer'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                $selMarcas         = $this->input->post('selMarcas');
                $txtNombre         = $this->input->post('txtNombre');
                $estatus           = $this->input->post('selEstatus');

                $this->arrDatos['selMarcas']      = html_escape($selMarcas);
                $this->arrDatos['txtNombre']      = html_escape($txtNombre);
                $this->arrDatos['selEstatus']     = html_escape($estatus);
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id']  = (int)$this->input->post('hddId');

                if($this->form_validation->run() == FALSE){
                    if(form_error('selMarcas')){
                        $this->arrDatos['sMsjError'] = form_error('selMarcas');
                    }elseif(form_error('txtNombre')){
                        $this->arrDatos['sMsjError'] = form_error('txtNombre');
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }
                }else{
                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_model');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_model/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']          = $id;
                    $this->arrDatos['selMarcas']   = $arrDatos['id_modelo'];
                    $this->arrDatos['txtNombre']   = $arrDatos['modelo'];
                    $this->arrDatos['selEstatus']  = $arrDatos['estatus'];
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

            $this->arrDatos['title'] = 'Edit Model';
            $this->arrDatos['vista'] = 'panel_model_upd_v';
            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_model');
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
        redirect('panel_model');
    }
}