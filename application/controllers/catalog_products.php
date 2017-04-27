<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_products extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('downloads_m');
        $this->load->model('products_m', 'modelo');

        $this->arrDatos['title'] 	  = '';
        $this->arrDatos['vista'] 	  = 'catalog_prodcuts_v';
        $this->arrDatos['sMsjError']  = '';

        // productos
        $this->arrDatos['SESS_searcht'] = '';
        $this->arrDatos['send_post']    = FALSE;
        $this->arrDatos['arrYears']     = $this->get_years_by_applications();

        $this->arrDatos['arrCatalog'] = get_catalog_products();

        // productos
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
     
    public function index(){

        //echo "<pre>",print_r($_POST),"</pre>";
            
        # si estan buscando
        $arrParam             = array();
        $error                = FALSE;
        $type                 = '';
        $search_by            = '';
        $sess_id_product_type = $this->modelo->get_field('sess_id_product_type');
        //echo "sess_id_product_type: $sess_id_product_type";
        
        # reset
        if($this->input->post('btReset')){
            $this->_clear_form1();
            $this->_clear_form2();
            $this->_clear_form3();
            $this->_clear_form4();
            $this->modelo->set_field('SESS_searcht','');
            $SESS_searcht = '';
        }
        
        # determina si se ha hecho una busqueda presioando el boton correspondiente
        # limpiamos el campo para que no intervenga
        if($this->input->post('btSearchProductType') or
           $this->input->post('btSearchSKU') or
           $this->input->post('btSearchCompetitor') or     
           $this->input->post('btSearchPpal')){
            
           $this->modelo->set_field('SESS_searcht','');
           $SESS_searcht = '';
           $this->arrDatos['send_post'] = TRUE;
        }else{
           $SESS_searcht = $this->modelo->get_field('SESS_searcht');
        }

        if($this->input->post('btSearchPpal') or $SESS_searcht=='btSearchPpal'){
            
            $search_by = '4.- by vehicle';
            $type      = 'by_vehicle_search';

            $this->_clear_form1();
            $this->_clear_form2();
            $this->_clear_form3();
            $this->modelo->set_field('SESS_searcht','btSearchPpal');
            $this->arrDatos['SESS_searcht'] = 'btSearchPpal';
            
            # lo toma de la sesion
            $selYears = $this->modelo->get_field('selYears');
            if($this->input->post('selYears') or (!$this->arrDatos['send_post'] and $selYears)){
                $arrParam['years'] = $this->input->post('selYears');
                if(empty($arrParam['years'])){
                    $arrParam['years'] = $this->modelo->get_field('selYears');
                }else{
                    $this->modelo->set_field('selYears',$arrParam['years']);
                }
                $this->arrDatos['selYears'] = html_escape($arrParam['years']);
                $this->modelo->set_field('selYears',$this->arrDatos['selYears']);
            }         
            
            # -- begin filter applications
            $selMarcas= $this->modelo->get_field('selMarcas');
            if($this->input->post('selMarcas') or (!$this->arrDatos['send_post'] and $selMarcas)){
                $arrParam['marca']           = $this->input->post('selMarcas');
                if(empty($arrParam['marca'])){
                    $arrParam['marca'] = $this->modelo->get_field('selMarcas');
                }else{
                    $this->modelo->set_field('selMarcas',$arrParam['marca']);
                }
                $arrParam['marca']           = (int)$arrParam['marca'];
                $this->arrDatos['selMarcas'] = $arrParam['marca'];
            }
            
            $selModelos = $this->modelo->get_field('selModelos');
            if($this->input->post('selModelos') or (!$this->arrDatos['send_post'] and $selModelos)){
                $arrParam['modelo'] = $this->input->post('selModelos');
                if(empty($arrParam['modelo'])){
                    $arrParam['modelo'] = $this->modelo->get_field('selModelos');
                }else{
                    $this->modelo->set_field('selModelos',$arrParam['modelo']);
                }
                $arrParam['modelo']           = (int)$arrParam['modelo'];
                $this->arrDatos['selModelos'] = $arrParam['modelo'];
            }
            
            $selTiposMotores = $this->modelo->get_field('selTiposMotores');
            if($this->input->post('selTiposMotores') or (!$this->arrDatos['send_post'] and $selTiposMotores)){
                $arrParam['tipo_motor'] = $this->input->post('selTiposMotores');
                if(empty($arrParam['tipo_motor'])){
                    $arrParam['tipo_motor'] = $this->modelo->get_field('selTiposMotores');
                }else{
                    $this->modelo->set_field('selTiposMotores',$arrParam['tipo_motor']);
                }
                $arrParam['tipo_motor']            = (int)$arrParam['tipo_motor'];
                $this->arrDatos['selTiposMotores'] = $arrParam['tipo_motor'];
            }
            # -- end filter applications
            
            $selTiposProductos = $this->modelo->get_field('selTiposProductos');
            if($this->input->post('selTiposProductos') or (!$this->arrDatos['send_post'] and $selTiposProductos)){
                $arrParam['tipo_producto']           = $this->input->post('selTiposProductos');
                if(empty($arrParam['tipo_producto'])){
                    $arrParam['tipo_producto'] = $this->modelo->get_field('selTiposProductos');
                }else{
                    $this->modelo->set_field('selTiposProductos',$arrParam['tipo_producto']);
                }
                $arrParam['tipo_producto']           = (int)$arrParam['tipo_producto'];
                $this->arrDatos['selTiposProductos'] = $arrParam['tipo_producto'];
            }
            
            //echo "<pre>",print_r($this->arrDatos),"</pre>";exit;
        # default search DESACTIVADO
        /*}elseif(!empty($id_tipo_producto)){
            $arrParam['tipo_producto']           = $id_tipo_producto;
            $arrParam['tipo_producto']           = (int)$arrParam['tipo_producto'];
            $this->arrDatos['selTiposProductos'] = $arrParam['tipo_producto'];
            $this->modelo->set_field('id_tipo_producto',0);*/
        }else{
            $type      = '';
            $search_by = 'default: empty';

            $this->modelo->get_field('SESS_searcht','');
            $this->arrDatos['SESS_searcht'] = '';
            $this->_clear_form1();
            $this->_clear_form2();
            $this->_clear_form3();
            //
            $this->_clear_form4();
        }


        # ----------------------------------------------------------------------
        # **********************************************************************
        # ----------------------------------------------------------------------
        if(!empty($type)){
            $this->modelo->set_field('type_search',$type);
            $config['base_url']            = site_url('products').'/index/';
            $config['per_page']            = 4;#max
            $config['uri_segment']         = 4;
            $selOrderBy                    = $this->input->post('selOrderBy');
            $selUpDown                     = $this->input->post('selUpDown');
            if(empty($selOrderBy) and $this->modelo->get_field('products_order_by')){
                $selOrderBy = $this->modelo->get_field('products_order_by');
            }else{
                $this->modelo->set_field('products_order_by',$selOrderBy);
            }

            if(empty($selUpDown) and $this->modelo->get_field('products_selUpDown')){
                $selUpDown = $this->modelo->get_field('products_selUpDown');
            }else{
                $this->modelo->set_field('products_selUpDown',$selUpDown);
            }
            $desde                         = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
            $this->arrDatos['arrProducts'] = $this->modelo->get_products($type,$arrParam,$config['per_page'],$desde,$selOrderBy,$selUpDown);
            //echo "<pre>",print_r($this->arrDatos['arrProducts']),"</pre>";
            $config['total_rows']          = $this->modelo->get_rows($type,$arrParam);//exit("-- ".$config['total_rows']);
            $config['num_links']           = 4;//round($config['total_rows']/$config['per_page']);
            $config['first_link']          = false;
            $config['full_tag_open']       = '<ul class="pagination">';
            $config['full_tag_close']      = '</ul>';
            $config['last_link']           = lang('last_row').' &raquo;';
            $config['last_tag_open']       = '<li>';
            $config['last_tag_close']      = '</li>';
            $config['next_link']           = lang('next').' <span class="fa fa-arrow-circle-o-right"></span>';
            $config['next_tag_open']       = '<li>';
            $config['next_tag_close']      = '</li>';
            $config['prev_link']           = '<span class="fa fa-arrow-circle-o-left"></span> '.lang('last');
            $config['prev_tag_open']       = '<li>';
            $config['prev_tag_close']      = '</li>';
            $config['cur_tag_open']        = '<li class="active"><a href="#">';
            $config['cur_tag_close']       = '</a></li>';
            $config['num_tag_open']        = '<li>';
            $config['num_tag_close']       = '</li>';
            #-------------------------------------------------------------
            //echo "<pre>",print_r($this->arrDatos['arrProducts']),"</pre>";
            if(!is_array($this->arrDatos['arrProducts']) or count($this->arrDatos['arrProducts'])==0){
                $this->arrDatos['msjError']   = lang('search_not_found');
                $this->arrDatos['pagination'] = '';
            }else{
                $this->pagination->initialize($config);
                $this->arrDatos['pagination'] = $this->pagination->create_links();
            }
        }
        $this->arrDatos['type_search'] = "$search_by - $type";
    	
		$this->load->view('includes/template', $this->arrDatos);
    }

    /* llamado desde catalog_prodcuts */
    public function view(){
        # chequeamos el GET ------------------------------------------------
        $this->_check_get();
        # ------------------------------------------------------------------

        redirect('products');
    }
    
    /* llamado desde home */
    public function type($id_tipo_producto=0){
        $id_tipo_producto = (int)$id_tipo_producto;
        if(empty($id_tipo_producto)){
            show_404();
        }else{
            # chequeamos el GET ------------------------------------------------
            $this->_check_get();
            # ------------------------------------------------------------------

            $this->modelo->set_field('sess_id_product_type',$id_tipo_producto);
            redirect('products');
        }
    }
    
    /* ejecuta la limpieza del formulario (session) 
     * si viene de las paginas mencionadas
     */
    public function _check_get(){
        //echo "<pre>",print_r($_GET),"</pre>";exit();
        # paginas que al 
        $arrP = array('home','products_guide');
        if(isset($_GET['p']) and in_array($_GET['p'],$arrP)){
            $this->modelo->set_field('SESS_searcht','');
            $this->_clear_form1();
            $this->_clear_form2();
            $this->_clear_form3();
            $this->_clear_form4();
        }
    }
    
    public function _clear_form1(){
        $this->modelo->set_field('btSearchProductType','');
        $this->modelo->set_field('selTiposProductosDownloads','');
        $this->modelo->set_field('sess_id_product_type','');
    }
    
    public function _clear_form2(){
        $this->modelo->set_field('txtSKU','');
    }
    
    public function _clear_form3(){
        $this->modelo->set_field('selCompetitor','');
        $this->modelo->set_field('txtCompetitor','');
    }
    
    public function _clear_form4(){
        $this->modelo->set_field('selYears','');
        $this->modelo->set_field('selMarcas','');
        $this->modelo->set_field('selModelos','');
        $this->modelo->set_field('selTiposMotores','');
        $this->modelo->set_field('selTiposMotores','');
        $this->modelo->set_field('selTiposProductos','');        
    }
    
    public function get_json_sku(){
        $q = isset($_GET["term"]) ? $_GET["term"] : "";
        if(!empty($q)){
            echo $this->modelo->get_json_sku($q);
        }
    }
    
    public function get_json_competitor(){
        $q = isset($_GET["term"]) ? $_GET["term"] : "";
        if(!empty($q)){
            echo $this->modelo->get_json_competitor($q);
        }
    }
    
    public function get_json_oem(){
        $q = isset($_GET["term"]) ? $_GET["term"] : "";
        if(!empty($q)){
            echo $this->modelo->get_json_oem($q);
        }
    }
    
    public function get_json_smp(){
        $q = isset($_GET["term"]) ? $_GET["term"] : "";
        if(!empty($q)){
            echo $this->modelo->get_json_smp($q);
        }
    }
    
    public function get_json_wells(){
        $q = isset($_GET["term"]) ? $_GET["term"] : "";
        if(!empty($q)){
            echo $this->modelo->get_json_wells($q);
        }
    }
    
    public function pdf($param_ids=''){
        $this->_print('pdf',$param_ids);
    }

    public function print_out(){
        $this->_print('screen');
    }
    
    public function _print($type='screen',$param_ids=''){
        $type_search = $this->modelo->get_field('type_search');
        $arrParam    = array();        
        if($type_search=='default_search'){
            # search by product type -----------------------------------------------
            $arrParam['tipo_producto'] = $this->modelo->get_field('selTiposProductosDownloads');

            # search by sku --------------------------------------------------------
            $arrParam['sku'] = $this->modelo->get_field('txtSKU');

            # search by competitor -------------------------------------------------
            $txtCompetitor = $this->modelo->get_field('txtCompetitor');
            if(!empty($txtCompetitor)){
                $hddCompetitor = $this->modelo->get_field('selCompetitor');
                switch($hddCompetitor){
                    case 'OE':{
                        $arrParam['oem'] = $txtCompetitor;
                        break;
                    }
                    case 'SMP':{
                        $arrParam['smp'] = $txtCompetitor;
                        break;
                    }
                    case 'Wells':{
                        $arrParam['wells'] = $txtCompetitor;
                        break;
                    }
                }
            }
        }elseif($type_search=='by_vehicle_search'){
            # search by vehicle ------------------------------------------------
            $arrParam['years'] = $this->modelo->get_field('selYears');
            $arrParam['marca'] = $this->modelo->get_field('selMarcas');
            $arrParam['modelo'] = $this->modelo->get_field('selModelos');
            $arrParam['tipo_motor'] = $this->modelo->get_field('selTiposMotores');
            $arrParam['tipo_producto'] = $this->modelo->get_field('selTiposProductos');
            # ------------------------------------------------------------------
        }
        
        //echo "data: [$type_search] <pre>",print_r($arrParam),"</pre>";
        $arrDataPDF = $this->modelo->get_products($type_search,$arrParam);
        //echo "result: <pre>",print_r($arrDataPDF),"</pre>";exit;
        if(is_array($arrDataPDF)){
            //titulo
            $titulo = isset($arrDataPDF[0]['tipo_producto']) ? (generar_url($arrDataPDF[0]['tipo_producto'])) : '';
            $html   = '<html><head>
                       <meta charset="utf-8">
                       <title>EMG International</title>
                       <link rel="stylesheet" href="'.base_url().'includes/css/pdf.css">
                       </head><body>';
            $html  .= '<div id="pdf-product">
                          <div class="logo"><img src="'.(base_url().'includes/images/logo.png').'"></div>';
            foreach($arrDataPDF as $item){
                   $html .= '
                       <div class="row">
                            <div class="imagen">';
                        if(file_exists($item['path_img'])){
                            $html .= '<img src="'.image($item['path_img'], 'product').'">';
                            //$html .= '<img src="'.base_url().$item['path_img'].'">';
                        }
                    $html .= '</div>
                            <div class="content">
                                 <div class="titulo">
                                    <a href="'.site_url('product').'/index/'.$item['url_post'].'">'.$item['titulo'].'</a>
                                </div>
                                <div class="datos-linea">'.$item['datos_linea'].'</div>';
                                if($item['descripcion']){
                                     echo '<div class="descripcion">'.$item['descripcion'].'<div>';
                                }
                                if(isset($item['arrApplicaciones']) and is_array($item['arrApplicaciones'])){
                                    $html .= '
                                    <div class="sub-titulo">'.lang('applications').'</div>
                                        <div class="tabla-content">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>'.lang('field_manufactute_model').'</th>
                                                        <th>'.lang('field_engine_type').'</th>
                                                        <th>'.lang('field_years').'</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    foreach($item['arrApplicaciones'] as $arrData){
                                                        $html .= '<tr>
                                                            <td>'.$arrData['marca_modelo'].'</td>
                                                            <td>'.$arrData['tipo_motor'].'</td>
                                                            <td>'.$arrData['years'].'</td>
                                                            </tr>';
                                                    }
                                                $html .= '</tbody>
                                            </table>
                                      </div>';
                           }#end tabla
                      $html .= '</div>
                   </div>';
            }#endforeach
            $html .= '</div>';
            $html .= '<div><br><h5 align="center">EMG International '.date('d/m/Y H:i:s').'</h5></div>';
            $html .= '</body></html>';

            if($type=='screen'){
                exit($html);
            }elseif($type=='pdf'){
                //exit($html." ----entre");
                generar_pdf_dompdf($html,$titulo,TRUE);
        //generar_pdf_tcpdf($html,$titulo,TRUE);
            }
        }else{
            $this->modelo->set_field('sMsjError', lang('search_not_found'));
        }
    }
    
    public function get_json_all_products_type(){
        echo get_json_tipos_productos();
    }
    
    public function get_years_by_applications(){
        return $this->modelo->get_years_by_applications();
    }
    public function get_json_marcas(){
        $year = $this->input->post('year');
        $year = (int)$year;
        echo $this->modelo->get_json_marcas($year);
    }
    public function get_json_modelos(){
        $year       = $this->input->post('year');
        $year       = (int)$year;
        $id_marca   = $this->input->post('id_marca');
        $id_marca   = (int)$id_marca;
        echo $this->modelo->get_json_modelos($year,$id_marca);
    }
    public function get_json_tipos_motores(){
        $year       = $this->input->post('year');
        $year       = (int)$year;
        $id_marca   = $this->input->post('id_marca');
        $id_marca   = (int)$id_marca;
        $id_modelo   = $this->input->post('id_modelo');
        $id_modelo   = (int)$id_modelo;
        echo $this->modelo->get_json_tipos_motores($year,$id_marca,$id_modelo);
    }
    public function get_json_tipos_productos(){
        $year           = $this->input->post('year');
        $year           = (int)$year;
        $id_marca       = $this->input->post('id_marca');
        $id_marca       = (int)$id_marca;
        $id_modelo      = $this->input->post('id_modelo');
        $id_modelo      = (int)$id_modelo;
        $id_tipo_motor  = $this->input->post('id_tipo_motor');
        $id_tipo_motor  = (int)$id_tipo_motor;
        echo $this->modelo->get_json_tipos_productos($year,$id_marca,$id_modelo,$id_tipo_motor);
    }
    
    public function get_json_product_type_name_by_sku(){
        $sku = $this->input->post('txtSKU');
        echo $this->modelo->get_json_product_type_name_by_sku($sku);
    }
}