<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('orders_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'orders_v';
        $this->arrDatos['sMsjError'] = '';
        
        $this->arrDatos['limit_table_items'] = 300;//500;
        
        #
        ini_set('max_input_vars', 10000);
        
        $this->max_size_file     = 2000000;#2mb
        $this->str_max_size_file = '2MB';
        
        $this->session->set_userdata('controller','orders');
    }
     
    public function index(){
        //echo "<pre>",print_r($_POST),"</pre>";
        if($this->input->post('hddItem') and 
           ($this->input->post('btProcess') or $this->input->post('btSaveOrder'))){
            
            $hddItem = $this->input->post('hddItem');
            if(is_array($hddItem)){
                $table = '<table border="1" style="text-align:center;width:100%;">
                            <thead><tr>
                                <th>'.lang('label_item').'</th>
                                <th>'.lang('field_quantity').'</th>
                                <th>'.lang('field_emg').'</th>
                                <th>'.lang('field_customer').'</th>
                                <th>'.lang('field_description').'</th>
                                <th>'.lang('field_unit_price').'</th>
                                <th>'.lang('field_total_price').'</th>
                            </tr></thead>
                          <tbody>';
                
                // borramos las ordenes previas del usuario
                $this->modelo->delete();
            
                $this->arrDatos['po_co']            = $this->input->post('txtPO_CO');
                $this->arrDatos['customer_name']    = $this->input->post('txtCustomer_name');
                $this->arrDatos['ship_to']          = $this->input->post('txtaShip_to');
                $this->arrDatos['customer_address'] = $this->input->post('txtaCustomer_address');
                $this->arrDatos['address']          = $this->input->post('txtaAddress');
                $this->arrDatos['customer_po_num']  = $this->input->post('txtCustomer_po_num');
                $this->arrDatos['sales_rep']        = $this->input->post('txtSales_rep');
                $this->arrDatos['total_pedido']     = $this->input->post('hddPrecio_total_pedido');

                if($id_order = $this->modelo->registrar_pedido($this->arrDatos)){
                    
                    $arrDatos                       = array();
                    $arrDatos['id_order']           = $id_order;
                    $arrResult                      = array();
                    $arrResult['po_co']             = $this->arrDatos['po_co'] ;
                    $arrResult['customer_name']     = $this->arrDatos['customer_name'] ;
                    $arrResult['ship_to']           = $this->arrDatos['ship_to'] ;
                    $arrResult['customer_address']  = $this->arrDatos['customer_address'];
                    $arrResult['address']           = $this->arrDatos['address'] ;
                    $arrResult['customer_po_num']   = $this->arrDatos['customer_po_num'];

                    foreach($hddItem as $i => $item){
                        $arrDatosItems           = explode('|',$item);
                        $arrDatos['item']        = (isset($arrDatosItems[0]) ? (int)$arrDatosItems[0] : 0);
                        $arrDatos['cantidad']    = (isset($arrDatosItems[1]) ? (int)$arrDatosItems[1] : 0);
                        $arrDatos['sku']         = (isset($arrDatosItems[2]) ? extract_string($arrDatosItems[2],255) : '');
                        $arrDatos['customer']    = (isset($arrDatosItems[3]) ? extract_string($arrDatosItems[3],255) : '');
                        $arrDatos['descripcion'] = (isset($arrDatosItems[4]) ? extract_string($arrDatosItems[4],255) : '');
                        $arrDatos['unit_price']  = (isset($arrDatosItems[5]) ? extract_string($arrDatosItems[5],255) : '');
                        $arrDatos['total_price'] = (isset($arrDatosItems[6]) ? extract_string($arrDatosItems[6],255) : '');
                        $arrResult['arrProductos'][] = array(
                            'cantidad'         => $arrDatos['cantidad'],
                            'sku'              => $arrDatos['sku'],
                            'customer'         => $arrDatos['customer'],
                            'descripcion'      => $arrDatos['descripcion'],
                            'unit_price'       => $arrDatos['unit_price'],
                            'total_price'      => $arrDatos['total_price']
                        );
                        #
                        $this->modelo->registrar_item($arrDatos);
                        
                        $table .= '<tr>';
                        $table .= '<td>'.$arrDatos['item'] .'</td>';
                        $table .= '<td>'.$arrDatos['cantidad'] .'</td>';
                        $table .= '<td>'.$arrDatos['sku'].'</td>';
                        $table .= '<td>'.$arrDatos['customer'].'</td>';
                        $table .= '<td>'.$arrDatos['descripcion'].'</td>';
                        $table .= '<td>'.$arrDatos['unit_price'].'</td>';
                        $table .= '<td>'.$arrDatos['total_price'].'</td>';
                        $table .= '</tr>';
                    }
                    $table .= '</tbody></table>';
                    if($this->input->post('btProcess')){
                        $file = $this->_create_xls($arrDatos['id_order'],$arrResult);
                        if($file){
                            $arrUsuario = get_data_usuario();
                            $mensaje = 'Orders send by: '.$arrUsuario['nombre_completo']
                                .'<br>Date: '.date('d/m/Y H:i:s')
                                .'<br>Email: '.$arrUsuario['email']
                                .'<br>Company: '.$arrUsuario['empresa'];
                            
                            $mensaje .= '<br><br>Order detalis: ';
                            $mensaje .='<br>P.O. No.: '.$this->arrDatos['po_co'];
                                    
                            if(user_is_representante()){
                                $mensaje .= '<br>Customer Name: '.$this->arrDatos['customer_name']
                                            .'<br>Ship to: '.$this->arrDatos['ship_to']
                                            .'<br>Customer Address: '.$this->arrDatos['customer_address']
                                            .'<br>Address: '.$this->arrDatos['address']
                                            .'<br>Ccustomer P.O. #: '.$this->arrDatos['customer_po_num']
                                            .'<br>Sales Rep.: '.$this->arrDatos['sales_rep'];
                                $mensaje .= '<br>Total Order: $'.$this->arrDatos['total_pedido'];
                            }
                                
                            //$mensaje .= $table;
                            $mensaje_footer = '<br><hr><br>
                                                Thank you / Gracias, 
                                                <br>EMG International
                                                <br><a target="_blank" href="'.site_url('login').'">Emginter.com</a>';

                            $EMG_email_contacto = get_config_db('email_contact');
                            $debug              = FALSE;
                            $arrAdjunto         = array($file);
                            $email_send         = get_config_db('email_public');
                            # ------------------------------------------------------
                            # mensaje al administrador -----------------------------
                            # ------------------------------------------------------
                            
                            if(send_email($email_send
                                          ,('Orders from EMG International by '.$arrUsuario['nombre_completo'])
                                          ,$mensaje,$arrUsuario['nombre_completo'],$arrUsuario['email'],$debug,$arrAdjunto)){

                                # --------------------------------------------------
                                # mensaje al usuario -------------------------------
                                # --------------------------------------------------
                                $mensaje .= '<br><br>
                                             This is a copy of your order, do not respond to this message.
                                             <br>
                                             <br>After submitting your request, one of our associates will send a confirmation quote for your review and approval
                                             <br>Saved orders will be automatically deleted after 15 days.';

                                $mensaje .= '<br><br>
                                             Este es una copia de su pedido, no responda a este mensaje.
                                             <br>
                                             <br>Después de enviar su solicitud, uno de nuestros agentes le enviará una cotización de confirmación para su revisión y aprobación.
                                             <br>Las órdenes guardadas se eliminarán automáticamente después de 15 días.';

                                $mensaje .= $mensaje_footer;
                                send_email($arrUsuario['email']
                                           ,'My Orders in EMG International'
                                          ,$mensaje,'EMG International',$EMG_email_contacto,FALSE);

                                $this->arrDatos['sMsjConf'] = lang('msjconf_orders');
                                $this->modelo->delete();
                                if(file_exists($file)){unlink($file);}
                            }else{
                                $this->arrDatos['sMsjError'] = 'Err1: '.lang('msjerror_orders');
                            }                  
                        }else{
                             $this->arrDatos['sMsjError'] = 'Err2: '.lang('msjerror_orders');
                        }
                    }#end btProcess
                    elseif($this->input->post('btSaveOrder')){
                        $this->arrDatos['sMsjConf'] = lang('msjconf_orders_save');
                    }
                }else{
                    $this->arrDatos['sMsjError'] = 'Err3: '.lang('msjerror_orders');
                }
            }else{
                $this->arrDatos['sMsjError'] = 'Err99: '.lang('msjerror_orders');
            }
        }
        
        # toda la data
        $this->arrDatos['arrResult'] = $this->modelo->get_orders_by_user(TRUE);
        //echo "<pre>",print_r($this->arrDatos['arrResult']),"</pre>";exit;
	$this->load->view('includes/template',$this->arrDatos);
    }
    
    public function _create_xls($id_order,$arrResult){
        ini_set("memory_limit","512M");
        $file = "EMG_order_$id_order.xlsx";
        $path = './includes/csv/orders/';
        
        //max_input_vars
        //echo '<pre>',print_r($arrDatos),'</pre>';exit;

        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
                die('This example should only be run from a Web Browser');

        /** PHPExcel */
        require_once './includes/php/excel/PHPExcel.php';
        /** PHPExcel_IOFactory */
        require_once './includes/php/excel/PHPExcel/IOFactory.php';
        #EXPORTANDO:


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                    ->setLastModifiedBy("EMG Products")
                                    ->setTitle("EMG Products")
                                    ->setSubject("EMG Products")
                                    ->setDescription("EMG Products")
                                    ->setKeywords("EMG, Products")
                                    ->setCategory("Products");
        # comenzamos a armar el xls
        # row 1: titulos
        /*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'EMG INTERNATIONAL')
            ->setCellValue('A3', 'Customer Name*:')
            ->setCellValue('C3', $arrResult['customer_name'])
            ->setCellValue('E3', 'Ship to*:')
            ->setCellValue('F3', $arrResult['ship_to'])
            ->setCellValue('A4', 'Customer Address*:')
            ->setCellValue('C4', $arrResult['customer_address'])
            ->setCellValue('E4', 'Address*:')
            ->setCellValue('F4', $arrResult['address'])
            ->setCellValue('A6', 'Customer P.O. No.*:')
            ->setCellValue('C6', $arrResult['customer_po_num'])
            ->setCellValue('A9', 'Item')
            ->setCellValue('B9', 'Qty*')
            ->setCellValue('C9', 'EMG P/N')
            ->setCellValue('D9', 'Customer P/N*')
            ->setCellValue('E9', 'Description')
            ->setCellValue('F9', 'Unit Price*')
            ->setCellValue('G9', 'Ext. Price');
         */
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Item')
            ->setCellValue('B1', 'Qty*')
            ->setCellValue('C1', 'EMG P/N')
            ->setCellValue('D1', 'Customer P/N*')
            ->setCellValue('E1', 'Description')
            ->setCellValue('F1', 'Unit Price*')
            ->setCellValue('G1', 'Ext. Price');

        $c = 2;#primera fila usada para los productos
        if(isset($arrResult['arrProductos']) and is_array($arrResult['arrProductos'])){
            foreach($arrResult['arrProductos'] as $i => $item){
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$c, ($i+1))
                        ->setCellValue('B'.$c, $item['cantidad'])
                        ->setCellValue('C'.$c, $item['sku'])
                        ->setCellValue('D'.$c, $item['customer'])
                        ->setCellValue('E'.$c, $item['descripcion'])
                        ->setCellValue('F'.$c, $item['unit_price'])
                        ->setCellValue('G'.$c, $item['total_price']);

                $c++;
            }# end foreach
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('EMG INTERNATIONAL ');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        
        //$objWriter->save('php://output');
        $objWriter->save(str_replace(__FILE__,$path.$file,__FILE__));
        
        return file_exists($path.$file) ? ($path.$file) : FALSE;
    }
    
    public function orders_upload(){
        if(user_is_representante()){
            $this->arrDatos['title']     = '';
            $this->arrDatos['vista']     = 'orders_upload_v';
            $this->load->view('includes/template',$this->arrDatos);
        }else{
            $this->index();
        }
    }

    public function import(){
        if(user_is_representante() and $this->input->post('btUpload')){
            ini_set("memory_limit","512M");
            $arrFILE = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($_POST),print_r($_FILES),'</pre>';exit;
            if(!isset($arrFILE['name']) or empty($arrFILE['name'])){
                $this->arrDatos['sMsjError_upload'] = lang('orders_file_upload_required');

            #'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 
            }elseif(!in_array($arrFILE['type'],array('application/octet-stream','application/excel', 'application/vnd.msexcel','application/excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))){
                $this->arrDatos['sMsjError_upload'] = lang('orders_allow_format');

            }elseif($arrFILE['size']>$this->max_size_file){
                $this->arrDatos['sMsjError_upload'] = lang('orders_size_file').$this->str_max_size_file;
            }else{
                $path_destino = './upload/orders/';
                if(isset($arrFILE)){
                    $new_name  = 'order_'.get_id_usuario();
                    $old_name  = $arrFILE["name"];
                    $trozos    = explode('.',basename($old_name)); 
                    $new_name .= '.'.end($trozos);
                    $file      = $path_destino.$new_name;
                    #eliminamos los existentes
                    clear_dirctory($path_destino);
                    if(move_uploaded_file($arrFILE['tmp_name'],$file)){
                        $arrProceso = $this->_procesar_excel_import($file);
                        //echo '<pre>',print_r($arrProceso),'</pre>';exit;

                        if($arrProceso['process'] == TRUE){                    
                            if(isset($arrProceso['arrOrder']) and is_array($arrProceso['arrOrder'])){
                                $this->arrDatos['po_co']            = '';
                                $this->arrDatos['customer_name']    = $arrProceso['arrOrder']['customer_name'];
                                $this->arrDatos['ship_to']          = $arrProceso['arrOrder']['ship_to'];
                                $this->arrDatos['customer_address'] = $arrProceso['arrOrder']['customer_address'];
                                $this->arrDatos['address']          = $arrProceso['arrOrder']['address'];
                                $this->arrDatos['customer_po_num']  = $arrProceso['arrOrder']['customer_po_no'];
                                $this->arrDatos['sales_rep']        = get_name_usuario();
                                $this->arrDatos['total_pedido']     = '';
                                
                                // borramos las ordenes previas del usuario
                                $this->modelo->delete();
                                if($id_order = $this->modelo->registrar_pedido($this->arrDatos)){
                                    if(is_array($arrProceso['arrOrder']['arrData'])){
                                        //echo "<pre>",print_r($arrProceso['arrOrder']['arrData']),"</pre>";exit;
                                        foreach($arrProceso['arrOrder']['arrData'] as$i => $item){
                                            $cantidad = (int)$item['qty'];
                                            if(empty($cantidad) or empty($item['customer_pn']) or
                                               empty($item['unit_price'])){
                                                continue;
                                            }
                                            $arrDatos = array(
                                                'id_order'         => $id_order,
                                                'cantidad'         => $cantidad,
                                                'sku'              => $item['emg_pn'],
                                                'customer'         => $item['customer_pn'],
                                                'descripcion'      => $item['description'],
                                                'unit_price'       => $item['unit_price'],
                                                'total_price'      => $item['ext_price']
                                            );
                                            $this->modelo->registrar_item($arrDatos);
                                        }
                                    }
                                }
                                $this->arrDatos['sMsjConf_upload'] = lang('orders_file_upload_full');
                                redirect('orders');
                            }
                        }elseif($arrProceso['process'] == FALSE){
                            $this->arrDatos['sMsjError_upload'] = $arrProceso['msjError'];
                        }else{
                            $this->arrDatos['sMsjError_upload'] = lang('orders_file_upload_error').' Err[1]';
                        }
                    }else{
                        $this->arrDatos['sMsjError_upload'] = lang('orders_file_upload_error').' Err[2]';
                    }
                }
            }
            $this->arrDatos['title']     = '';
            $this->arrDatos['vista']     = 'orders_upload_v';
            $this->load->view('includes/template',$this->arrDatos);
        }else{
            $this->index();
        }
    }
    
    function _procesar_excel_import($file){
        /** PHPExcel */
        require_once './includes/php/excel/PHPExcel.php';
        /** PHPExcel_IOFactory */
        require_once './includes/php/excel/PHPExcel/IOFactory.php';
        #IMPORTANDO:
        //creando un objeto lector y cargando el fichero
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($file);

        //iterando por el contenido de las celdas
        $objWorksheet          = $objPHPExcel->getActiveSheet();
        $arrOrder              = array(
            'customer_name'     => '',
            'ship_to'           => '',
            'customer_address'  => '',
            'address'           => '',
            'customer_po_no'    => '',
            'arrData'           => array()
        );
        $arrProceso            = array();
        $arrFormatoCamposExcel = array(
            'Customer Name*:',#0
            'Ship to*:',#1
            'Customer Address*:',#2
            'Address*:',#3
            'Customer P.O. No.*:',#4
            'Item',#5
            'Qty*',#6
            'EMG P/N',#7
            'Customer P/N*',#8
            'Description',#9
            'Unit Price*',#10
            'Ext. Price',#11
        );

        $Formato_correcto      = TRUE;
        $arrProceso['process'] = TRUE;
        $error_obligatorio     = FALSE;
        $error_campo_numerico  = FALSE;
        $contador              = 0;
        
        # - filas --------------------------------------------------------------
        foreach ($objWorksheet->getRowIterator() as $fila => $row){
            
            $contador++;
            if($contador >($this->arrDatos['limit_table_items'] + 10)){break;}
            
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(TRUE);
            
            # - columnas -------------------------------------------------------
            //echo "<pre>",print_r($cellIterator),"</pre>";exit;
            foreach ($cellIterator as $columna => $cell){
                $celda = $cell->getValue();
                $celda = trim($celda);
                
                $lang = get_lang();
                if($lang=='en'){
                    $fila_columna = " (row:$fila-column:$columna)";
                    $valor_incorrecto = " - incorrect value: $celda)";
                }elseif($lang=='es'){
                    $fila_columna     = " (fila:$fila-columna:$columna)";
                    $valor_incorrecto = " - valor incorrecto: $celda)";
                }                
                //echo $fila_columna." - $celda<br>";
                
                # fila 3
                if($fila == 3){
                    switch($columna){
                        case 0:{#Customer Name*:
                            $id = 0;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 2:{#*** valid Customer Name data
                            $valor_incorrecto = " - Customer Name ($celda)";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['customer_name'] = html_escape($celda);
                            }
                            break;
                        }
                        case 4:{#Ship to*:
                            $id = 1;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 5:{#*** valid Ship to data
                            $valor_incorrecto = " - Ship to";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['ship_to'] = html_escape($celda);
                            }
                            break;
                        }
                    }
                }
                
                # fila 4
                if($fila == 4){
                    switch($columna){
                        case 0:{#Customer Address*:
                            $id = 2;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 2:{#*** valid Customer Address data
                            $valor_incorrecto = " - Customer Address";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['customer_address'] = html_escape($celda);
                            }
                            break;
                        }
                        case 4:{#Address*:
                            $id = 3;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 5:{#*** valid Address data
                            $valor_incorrecto = " - Address";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['address'] = html_escape($celda);
                            }
                            break;
                        }
                    }
                }
                
                # fila 6
                if($fila == 6){
                    switch($columna){
                        case 0:{#Customer P.O. No.*:
                            $id = 4;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 2:{#*** valid Customer P.O. No. data
                            $valor_incorrecto = " - Customer P.O. No.";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['customer_po_no'] = html_escape($celda);
                            }
                            break;
                        }
                    }
                }
                
                # fila 9 - headers de la tabla
                if($fila == 9){
                    switch($columna){
                        case 0:{#Item
                            $id = 5;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 1:{#Qty*
                            $id = 6;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 2:{#EMG P/N
                            $id = 7;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 3:{#Customer P/N*
                            $id = 8;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 4:{#Description
                            $id = 9;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 5:{#Unit Price*
                            $id = 10;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                        case 6:{#Ext. Price
                            $id = 11;
                            //echo $fila."|".$columna ." -- ".$arrFormatoCamposExcel[$id] ." - ". $celda ." -- ". ($arrFormatoCamposExcel[$id] <> $celda ? " son diferentes" : " son iguales") ."<br>";
                            if(isset($arrFormatoCamposExcel[$id]) and $arrFormatoCamposExcel[$id] <> $celda){
                                $Formato_correcto = FALSE;
                            }
                            break;
                        }
                    }
                }
                
                # fila 10 - datos de la tabla
                if($fila >= 10){
                    switch($columna){
                        case 0:{#*** valid Item data
                            // si detecta un campo Item vacio, sale de la matriz
                            if(empty($celda)){
                                break 3;#switch + for row + for colum
                            }else{
                                $arrOrder['arrData'][$fila]['item'] = html_escape($celda);
                            }
                            break;
                        }
                        case 1:{#*** valid Qty data
                            $valor_incorrecto = " - Qty";
                            if(empty($celda)){
                                break 3;#switch + for row + for colum
                                //$error_obligatorio = TRUE;
                            }elseif(!is_numeric($celda)){
                                $error_campo_numerico = TRUE;
                            }else{
                                $arrOrder['arrData'][$fila]['qty'] = html_escape($celda);
                            }
                            break;
                        }
                        case 2:{#*** valid EMG P/N data
                            $arrOrder['arrData'][$fila]['emg_pn'] = html_escape($celda);
                            break;
                        }
                        case 3:{#*** valid Customer P/N data
                            $valor_incorrecto = " - Customer P/N";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['arrData'][$fila]['customer_pn'] = html_escape($celda);
                            }
                            break;
                        }
                        case 4:{#*** valid Description data
                            $arrOrder['arrData'][$fila]['description'] = html_escape($celda);
                            break;
                        }
                        case 5:{#*** valid Unit Price data
                            $valor_incorrecto = " - Unit Price";
                            if(empty($celda)){
                                $error_obligatorio = TRUE;
                            }else{
                                $arrOrder['arrData'][$fila]['unit_price'] = html_escape($celda);
                            }
                            break;
                        }
                        case 6:{#*** valid Ext. Price data
                            $arrOrder['arrData'][$fila]['ext_price'] = html_escape($celda);
                            break;
                        }
                    }
                }
                
                if($Formato_correcto == FALSE){
                    $arrProceso['msjError'] = lang('orders_file_incorrect_format').$fila_columna.$valor_incorrecto;
                    $arrProceso['process']  = FALSE;
                    break 2;#for row + for colum

                }elseif($error_obligatorio == TRUE){
                    $arrProceso['msjError'] = lang('orders_file_field_required').$fila_columna.$valor_incorrecto;
                    $arrProceso['process']  = FALSE;
                    break 2;#for row + for colum
                }elseif($error_campo_numerico == TRUE){
                    $arrProceso['msjError'] = lang('orders_file_field_numeric').$fila_columna.$valor_incorrecto;
                    $arrProceso['process']  = FALSE;
                    break 2;#for row + for colum
                }
                # validamos el contenido
            }# end foreach columnas --------------------------------------------

            # si no hay errores en la carga de datos, continuamos..
            if($arrProceso['process'] == TRUE){
                $arrProceso['arrOrder'] = $arrOrder;
            }# -----------------------------------------------------------------            
         }#end foreach filas ---------------------------------------------------
         
         //echo "<pre>",print_r($arrProceso),"</pre>";exit;
         return $arrProceso;
    }
}