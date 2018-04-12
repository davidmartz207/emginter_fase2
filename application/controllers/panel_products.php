<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('pagination', 'form_validation'));
        $this->load->model('panel_products_m', 'modelo');
        $this->arrDatos['title'] = 'Panel Products';
        $this->arrDatos['vista'] = 'panel_products_list_v';
        $this->arrDatos['sMsjError'] = '';

        $this->arrDatos['arrOpcionesImportacion'] = array(
            'delete_database' => 'Seleccione esta opción si desea eliminar el contenido de la base de datos antes de importar el archivo seleccionado'
        );

        // tamaño maximo de las imagenes a subir
        $this->max_size_file = 3000000;#2mb
        $this->str_max_size_file = '3MB';
        $this->width_image = 400;#px
        $this->hight_image = 300;#px;

        # archivo q se importara
        $file = $this->session->userdata('file');
        $this->arrDatos['file'] = (!empty($file) ? $file : '');

        #
        $this->arrDatos['arrNewRelease'] = array(
            array('id' => 0, 'nombre' => 'No destacar'),
            array('id' => 1, 'nombre' => 'Destacar este Producto')
        );

        $this->arrDatos['lineas'] = $this->modelo->get_lineas();
    }

    public function index($variable=null,$pag = 0)
    {
		$decodifica = stripslashes($variable);
		$decodifica = urldecode($decodifica);
        $decodifica = unserialize($decodifica); 
		
		//si hay un valor de busqueda decodificado
		if($decodifica){
			//la variable post se cambia por ese valor
			$_POST = $decodifica;
		}

       // echo "pagina: ".$pag."<pre>",print_r($decodifica),"</pre>";
        if ($this->input->post('btBuscar')) {
            $selLinea = $this->input->post('selLinea');
            $selTiposProductos = $this->input->post('selTiposProductos');
            $txtSKU = $this->input->post('txtSKU');
            $txtOEM = $this->input->post('txtOEM');
            $txtSMP = $this->input->post('txtSMP');
            $txtWELLS = $this->input->post('txtWELLS');
            $txtYears = $this->input->post('txtYears');
            $selMarcas = $this->input->post('selMarcas');
            $selModelos = $this->input->post('selModelos');
            $selTiposMotores = $this->input->post('selTiposMotores');

            $this->arrDatos['selLinea'] = $selLinea;
            $this->arrDatos['selTiposProductos'] = (int)$selTiposProductos;
            $this->arrDatos['txtSKU'] = html_escape($txtSKU);
            $this->arrDatos['txtOEM'] = html_escape($txtOEM);
            $this->arrDatos['txtSMP'] = html_escape($txtSMP);
            $this->arrDatos['txtWELLS'] = html_escape($txtWELLS);
            $this->arrDatos['txtYears'] = html_escape($txtYears);
            $this->arrDatos['selMarcas'] = html_escape($selMarcas);
            $this->arrDatos['selModelos'] = html_escape($selModelos);
            $this->arrDatos['selTiposMotores'] = html_escape($selTiposMotores);

            $selOrderBy = $this->input->post('selOrderBy');
            $selUpDown = $this->input->post('selUpDown');
            $selOrderBy = (int)$selOrderBy;
            $selUpDown = (int)$selUpDown;

            $this->modelo->set_field('linea', $this->arrDatos['selLinea']);
            $this->modelo->set_field('selTiposProductos', $this->arrDatos['selTiposProductos']);
            $this->modelo->set_field('txtSKU', $this->modelo->caracter($this->arrDatos['txtSKU']));
            $this->modelo->set_field('txtOEM', $this->modelo->caracter($this->arrDatos['txtOEM']));
            $this->modelo->set_field('txtSMP', $this->modelo->caracter($this->arrDatos['txtSMP']));
            $this->modelo->set_field('txtWELLS', $this->modelo->caracter($this->arrDatos['txtWELLS']));
            $this->modelo->set_field('txtYears', $this->arrDatos['txtYears']);
            $this->modelo->set_field('selMarcas', $this->arrDatos['selMarcas']);
            $this->modelo->set_field('selModelos', $this->arrDatos['selModelos']);
            $this->modelo->set_field('selTiposMotores', $this->arrDatos['selTiposMotores']);
            $this->modelo->set_field('selOrderBy', $selOrderBy);
            $this->modelo->set_field('selUpDown', $selUpDown);
            $this->modelo->set_field('btBuscar', 1);

            // la paginacion..
        } elseif (!$this->input->post('btReset') and (!empty($pag) or $this->modelo->get_field('btBuscar') == 1)) {
            //si viene una linea
            $selLinea = $this->modelo->get_field('linea');

            $selTiposProductos = $this->modelo->get_field('selTiposProductos');
            $txtSKU = $this->modelo->get_field('txtSKU');
            $txtOEM = $this->modelo->get_field('txtOEM');
            $txtSMP = $this->modelo->get_field('txtSMP');
            $txtWELLS = $this->modelo->get_field('txtWELLS');
            $txtYears = $this->modelo->get_field('txtYears');
            $selMarcas = $this->modelo->get_field('selMarcas');
            $selModelos = $this->modelo->get_field('selModelos');
            $selTiposMotores = $this->modelo->get_field('selTiposMotores');

            $this->arrDatos['selLinea'] = $selLinea;
            $this->arrDatos['selTiposProductos'] = (int)$selTiposProductos;
            $this->arrDatos['txtSKU'] = html_escape($txtSKU);
            $this->arrDatos['txtOEM'] = html_escape($txtOEM);
            $this->arrDatos['txtSMP'] = html_escape($txtSMP);
            $this->arrDatos['txtWELLS'] = html_escape($txtWELLS);
            $this->arrDatos['txtYears'] = html_escape($txtYears);
            $this->arrDatos['selMarcas'] = html_escape($selMarcas);
            $this->arrDatos['selModelos'] = html_escape($selModelos);
            $this->arrDatos['selTiposMotores'] = html_escape($selTiposMotores);

            $selOrderBy = $this->modelo->get_field('panel_products_order_by');
            $selUpDown = $this->modelo->get_field('panel_products_selUpDown');
            $selOrderBy = (int)$selOrderBy;

            $selUpDown = (int)$selUpDown;

        } elseif ($this->input->post('btReset')) {
            $this->modelo->set_field('linea', '');
            $this->modelo->set_field('selTiposProductos', '');
            $this->modelo->set_field('txtSKU', '');
            $this->modelo->set_field('txtOEM', '');
            $this->modelo->set_field('txtSMP', '');
            $this->modelo->set_field('txtWELLS', '');
            $this->modelo->set_field('txtYears', '');
            $this->modelo->set_field('selMarcas', '');
            $this->modelo->set_field('selModelos', '');
            $this->modelo->set_field('selTiposMotores', '');

            $this->modelo->set_field('panel_products_order_by', '');
            $this->modelo->set_field('panel_products_selUpDown', '');


            $selOrderBy = '';
            $selUpDown = '';

            $this->modelo->set_field('btBuscar', 0);
        } else {
            $selOrderBy = '';
            $selUpDown = '';
        }

        //si viene una linea
		
		$variablebusqueda = serialize($_POST);
		$variablebusqueda = urlencode($variablebusqueda);
        $config['base_url'] = site_url('panel_products') . '/index/'.$variablebusqueda.'/';


        $config['total_rows'] = $this->modelo->get_rows($this->arrDatos);
        $config['per_page'] = 40;#max
        $config['uri_segment'] = 5;
        if ($this->modelo->get_field('result') == 1) {
            $this->arrDatos['sMsjConf'] = 'Creado con éxito.';
        } elseif ($this->modelo->get_field('result') == 2) {
            $this->arrDatos['sMsjConf'] = 'Actualizado con éxito.';
        } elseif ($this->modelo->get_field('result') == 3) {
            $this->arrDatos['sMsjConf'] = 'Eliminado con éxito.';
        } elseif ($this->modelo->get_field('result') == 4) {
            $this->arrDatos['sMsjConf'] = 'No se encontraron datos para exportar';
        }
        $this->modelo->set_field('result', 0);

        $this->arrDatos['selOrderBy'] = $selOrderBy;
        $this->arrDatos['selUpDown'] = $selUpDown;

        $temp_order_by = $this->modelo->get_field('panel_products_order_by');
        if (empty($selOrderBy) and $temp_order_by) {
            $selOrderBy = $temp_order_by;
        } else {
            $this->modelo->set_field('panel_products_order_by', $selOrderBy);
        }

        $temp_selUpDown = $this->modelo->get_field('panel_products_selUpDown');
        if (empty($selUpDown) and $temp_selUpDown) {
            $selUpDown = $temp_selUpDown;
        } else {
            $this->modelo->set_field('panel_products_selUpDown', $selUpDown);
        }
        $desde = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;


        $this->arrDatos['arrProducts'] = $this->modelo->get_products($this->arrDatos, $config['per_page'], $desde, $selOrderBy, $selUpDown);


        $config['num_links'] = round($config['total_rows'] / $config['per_page']);
        //$config['page_query_string'] = TRUE;
        // $config['use_page_numbers'] = TRUE;
        //$config['query_string_segment'] = 'page';
        $config['first_link'] = FALSE;
        //$config['last_link'] = FALSE;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

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
        $this->arrDatos['pagination'] = $this->pagination->create_links();

        $this->load->view('panel/includes/template', $this->arrDatos);
    }

    public function ins()
    {


        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';

        $this->arrDatos['arrTiposProductos'] = get_tipos_productos();
        if ($this->input->post('btSubmit')) {
            $arrValidaciones = array(
                array(
                    'field' => 'linea',
                    'label' => 'Línea',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'txtSKU',
                    'label' => 'Código SKU',
                    'rules' => 'required|min_length[2]|max_length[255]'
                ),
                array(
                    'field' => 'selTiposProductos',
                    'label' => 'Tipo de Producto',
                    'rules' => 'required|integer'
                ),
                array(
                    'field' => 'selNewRelease',
                    'label' => 'New Release',
                    'rules' => 'required|integer'
                ),
                array(
                    'field' => 'txtaDescripcionEn',
                    'label' => 'Descripcion(Ingles)',
                    'rules' => 'min_length[3]|max_length[10000]'
                ),
                array(
                    'field' => 'txtaDescripcionEs',
                    'label' => 'Descripcion(Español)',
                    'rules' => 'min_length[3]|max_length[10000]'
                ),
                array(
                    'field' => 'txtSellPrice',
                    'label' => 'Precio / Sell Price',
                    'rules' => 'max_length[255]'
                ),
                array(
                    'field' => 'txtSMP',
                    'label' => 'SMP',
                    'rules' => 'max_length[255]'
                ),
                array(
                    'field' => 'txtWells',
                    'label' => 'Wells',
                    'rules' => 'max_length[255]'
                ),
                array(
                    'field' => 'txtDai',
                    'label' => 'DAI',
                    'rules' => 'max_length[255]'
                )
            );
            if($this->input->post('linea') != "perfection"){
                //agregamos el OEM
                $arrValidaciones[] = array(
                    'field' => 'txtOEM',
                    'label' => 'OEM',
                    'rules' => 'required|min_length[1]|max_length[255]'
                );
            }
            $this->form_validation->set_rules($arrValidaciones);

            $flywheel = $this->input->post('flywheel');
            $cover = $this->input->post('cover');
            $disc_info = $this->input->post('disc_info');
            $notes = $this->input->post('notes');
            $linea = $this->input->post('linea');
           // $cylinder = $this->input->post('cylinder');
            $txtSKU = $this->input->post('txtSKU');
            $selTiposProductos = $this->input->post('selTiposProductos');
            $selNewRelease = $this->input->post('selNewRelease');
            $txtaDescripcionEn = $this->input->post('txtaDescripcionEn');
            $txtaDescripcionEs = $this->input->post('txtaDescripcionEs');
            $txtOEM = $this->input->post('txtOEM');
            $txtSMP = $this->input->post('txtSMP');
            $txtWells = $this->input->post('txtWells');
            $txtDai = $this->input->post('txtDai');
            $txtSellPrice = $this->input->post('txtSellPrice');

            $this->arrDatos['txtLinea'] = html_escape($linea);
            $this->arrDatos['txtFlywheel'] = html_escape($flywheel);
            $this->arrDatos['txtCover'] = html_escape($cover);
            $this->arrDatos['txtDisc_info'] = html_escape($disc_info);
          //  $this->arrDatos['txtCylinder'] = html_escape($cylinder);
            $this->arrDatos['txtNotes_1'] = html_escape($notes);
            $this->arrDatos['txtSKU'] = html_escape($txtSKU);
            $this->arrDatos['selTiposProductos'] = (int)$selTiposProductos;
            $this->arrDatos['selNewRelease'] = (int)$selNewRelease;
            $this->arrDatos['txtaDescripcionEn'] = html_escape($txtaDescripcionEn);
            $this->arrDatos['txtaDescripcionEs'] = html_escape($txtaDescripcionEs);
            $this->arrDatos['txtOEM'] = html_escape($txtOEM);
            $this->arrDatos['txtSMP'] = html_escape($txtSMP);
            $this->arrDatos['txtWells'] = html_escape($txtWells);
            $this->arrDatos['txtDai'] = html_escape($txtDai);
            $this->arrDatos['txtSellPrice'] = html_escape($txtSellPrice);
            $arrFILE = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

            # verifivcamos si se subio una imagen
            $image_upload = FALSE;
            if (isset($arrFILE['name']) and !empty($arrFILE['name'])) {
                $image_upload = true;
            }

            if ($this->form_validation->run() == FALSE) {		
		if (form_error('linea')) {
                        $this->arrDatos['sMsjError'] = form_error('linea');      
		}elseif (form_error('txtSKU')) {
                    $this->arrDatos['sMsjError'] = form_error('txtSKU');
                } elseif (form_error('selTiposProductos')) {
                    $this->arrDatos['sMsjError'] = form_error('selTiposProductos');
                } elseif (form_error('selNewRelease')) {
                    $this->arrDatos['sMsjError'] = form_error('selNewRelease');
                } elseif (form_error('txtaDescripcionEn')) {
                    $this->arrDatos['sMsjError'] = form_error('txtaDescripcionEn');
                } elseif (form_error('txtaDescripcionEs')) {
                    $this->arrDatos['sMsjError'] = form_error('txtaDescripcionEs');
                } elseif (form_error('txtSellPrice')) {
                    $this->arrDatos['sMsjError'] = form_error('txtSellPrice');
                } elseif (form_error('txtOEM')) {
                    $this->arrDatos['sMsjError'] = form_error('txtOEM');
                } elseif (form_error('txtSMP')) {
                    $this->arrDatos['sMsjError'] = form_error('txtSMP');
                } elseif (form_error('txtWells')) {
                    $this->arrDatos['sMsjError'] = form_error('txtWells');
                } elseif (form_error('flywheel')) {
                        $this->arrDatos['sMsjError'] = form_error('flywheel');
                }


                // validamos el tipo de archivo
            } elseif ($image_upload and !in_array($arrFILE['type'], array('image/jpeg', 'image/png', 'image/gif', 'image/jpeg'))) {
                $this->arrDatos['sMsjError'] = 'Only image formats are allowed JPG,PNG,JPEG,GIF';

                // validamos el tamaño de archivo
            } elseif ($image_upload and $arrFILE['size'] > $this->max_size_file) {
                $this->arrDatos['sMsjError'] = 'The image size can not exceed ' . $this->str_max_size_file;
                # --- fin validaciones del archivo a subir..

            } else {

                if ($id_producto = $this->modelo->registrar($this->arrDatos)) {

                    // -- procesamos la imagen
                    # solo procede si se subio una imagen
                    if ($image_upload) {
                        $top_name = generar_url($txtOEM . '-' . $txtSKU);
                        // subimos la imagen
                        $image = procesar_imagenes($arrFILE, ('products/' . $id_producto . '/'), FALSE, array('top_name' => $top_name));

                        // actualizamos la imagen al perfil
                        $this->modelo->set_image($id_producto, $image);
                        // -- fin del procesamiento de imagen
                    }

                    $this->modelo->set_field('msjconf_register_product', 'Producto registrado con éxito, ahora puede agregar las aplicaciones.');
                    $this->modelo->set_field('id_producto', $id_producto);

                    # nos vamos al modulo de aplicaciones de productos
                    $this->session->set_userdata('id_producto', $id_producto);

                    redirect('panel_applications');
                } else {
                    $this->arrDatos['sMsjError'] = lang('msjerror_register');
                }
            }
        } else {
            $this->arrDatos['txtLinea'] = '';
            $this->arrDatos['txtFlywheel'] = '';
            $this->arrDatos['cover'] = '';
            $this->arrDatos['disc_info'] = '';
           // $this->arrDatos['cylinder'] = '';
            $this->arrDatos['notes'] = '';
            $this->arrDatos['txtSKU'] = '';
            $this->arrDatos['selTiposProductos'] = '';
            $this->arrDatos['selNewRelease'] = '';
            $this->arrDatos['txtaDescripcionEn'] = '';
            $this->arrDatos['txtaDescripcionEs'] = '';
            $this->arrDatos['txtOEM'] = '';
            $this->arrDatos['txtSMP'] = '';
            $this->arrDatos['txtWells'] = '';
            $this->arrDatos['txtSellPrice'] = '';
            $this->arrDatos['txtDai'] = '';

        }

        $this->arrDatos['vista'] = 'panel_products_ins_v';
        $this->load->view('panel/includes/template', $this->arrDatos);
    }

    // se encarga de todo el proceso de validacion y
    // llamado a la modificacion en la DB
    public function upd($data = '')
    {
       // echo '<pre>',print_r($_POST),'</pre>';die();
        //echo '<pre>',print_r($_FILES),'</pre>';

        // este parametro es obligatorio, debe ser el id del registro
        if (!empty($data)) {
            // para el select de la vista
            $this->arrDatos['arrEstatus'] = array(1 => 'Enabled ', 0 => 'Disabled');

            // forzamos el valor integer
            $id = (int)$data;

            // hicieron click en el boton de Edit...
            if ($this->input->post('btEditar')) {
                $arrValidaciones = array(
                    array(
                        'field' => 'selLinea',
                        'label' => 'Línea',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'txtSKU',
                        'label' => 'Código SKU',
                        'rules' => 'required|min_length[2]|max_length[255]'
                    ),
                    array(
                        'field' => 'selTiposProductos',
                        'label' => 'Tipo de Producto',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'selNewRelease',
                        'label' => 'New Release',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'txtaDescripcionEn',
                        'label' => 'Descripcion(Ingles)',
                        'rules' => 'min_length[3]|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaDescripcionEs',
                        'label' => 'Descripcion(Español)',
                        'rules' => 'min_length[3]|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtSellPrice',
                        'label' => 'Precio / Sell Price',
                        'rules' => 'max_length[255]'
                    ),
                    array(
                        'field' => 'txtSMP',
                        'label' => 'SMP',
                        'rules' => 'max_length[255]'
                    ),
                    array(
                        'field' => 'txtWells',
                        'label' => 'Wells',
                        'rules' => 'max_length[255]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'txtDai',
                        'label' => 'DAI',
                        'rules' => 'max_length[255]'
                    ),
                );


                if($this->input->post('selLinea') != "perfection"){

                    //agregamos el OEM
                    $arrValidaciones[] = array(
                        'field' => 'txtOEM',
                        'label' => 'OEM',
                        'rules' => 'required|min_length[1]|max_length[255]'
                    );
                }


                $this->form_validation->set_rules($arrValidaciones);

                $txtSKU = $this->input->post('txtSKU');
                $selTiposProductos = $this->input->post('selTiposProductos');
                $selNewRelease = $this->input->post('selNewRelease');
                $txtaDescripcionEn = $this->input->post('txtaDescripcionEn');
                $txtaDescripcionEs = $this->input->post('txtaDescripcionEs');
                $txtOEM = $this->input->post('txtOEM');
                $txtSMP = $this->input->post('txtSMP');
                $txtWells = $this->input->post('txtWells');
                $txtSellPrice = $this->input->post('txtSellPrice');
                $estatus = $this->input->post('selEstatus');
                $linea = $this->input->post('selLinea');
                $flywheel = $this->input->post('flywheel');
                $cover = $this->input->post('cover');
                $disc_info = $this->input->post('disc_info');
                $notes_1 = $this->input->post('notes') ? $this->input->post('notes') : $this->input->post('notesmrc') ;
                $notes_2 = "";
                $dai = $this->input->post('txtDai');


                //       $cylinder = $this->input->post('cylinder');

                $this->arrDatos['selLinea'] = html_escape($linea);
                $this->arrDatos['selEstatus'] = html_escape($estatus);
                $this->arrDatos['txtSKU'] = html_escape($txtSKU);
                $this->arrDatos['selTiposProductos'] = (int)$selTiposProductos;
                $this->arrDatos['selNewRelease'] = (int)$selNewRelease;
                $this->arrDatos['txtaDescripcionEn'] = html_escape($txtaDescripcionEn);
                $this->arrDatos['txtaDescripcionEs'] = html_escape($txtaDescripcionEs);
                $this->arrDatos['txtOEM'] = html_escape($txtOEM);
                $this->arrDatos['txtSMP'] = html_escape($txtSMP);
                $this->arrDatos['txtWells'] = html_escape($txtWells);
                $this->arrDatos['txtSellPrice'] = html_escape($txtSellPrice);
                $this->arrDatos['flywheel'] = $flywheel;
                $this->arrDatos['cover'] = $cover;
                $this->arrDatos['disc_info'] = $disc_info;
                $this->arrDatos['notes_1'] = $notes_1;
                $this->arrDatos['notes_2'] = $notes_2;
                $this->arrDatos['txtDai'] = $dai;

                $arrFILE = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

                # verificamos si se subio una imagen
                $image_upload = FALSE;
                if (isset($arrFILE['name']) and !empty($arrFILE['name'])) {
                    $image_upload = true;
                }

                // este es el id del registro original
                // el cual debemos mantener para poder actualizar
                $this->arrDatos['id'] = (int)$this->input->post('hddId');
                $this->arrDatos['imagen_actual'] = $this->input->post('hddImage');
                $this->arrDatos['imagen_actual'] = html_escape($this->arrDatos['imagen_actual']);

                if ($this->form_validation->run() == FALSE) {
                    if (form_error('txtSKU')) {
                        $this->arrDatos['sMsjError'] = form_error('txtSKU');
                    } elseif (form_error('selTiposProductos')) {
                        $this->arrDatos['sMsjError'] = form_error('selTiposProductos');
                    } elseif (form_error('selNewRelease')) {
                        $this->arrDatos['sMsjError'] = form_error('selNewRelease');
                    } elseif (form_error('txtaDescripcionEn')) {
                        $this->arrDatos['sMsjError'] = form_error('txtaDescripcionEn');
                    } elseif (form_error('txtaDescripcionEs')) {
                        $this->arrDatos['sMsjError'] = form_error('txtaDescripcionEs');
                    } elseif (form_error('txtSellPrice')) {
                        $this->arrDatos['sMsjError'] = form_error('txtSellPrice');
                    } elseif (form_error('txtOEM')) {
                        $this->arrDatos['sMsjError'] = form_error('txtOEM');
                    } elseif (form_error('txtSMP')) {
                        $this->arrDatos['sMsjError'] = form_error('txtSMP');
                    } elseif (form_error('txtWells')) {
                        $this->arrDatos['sMsjError'] = form_error('txtWells');
                    } elseif (form_error('selEstatus')) {
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    } elseif (form_error('selLinea')) {
                        $this->arrDatos['sMsjError'] = form_error('selLinea');
                    }elseif (form_error('flywheel')) {
                        $this->arrDatos['sMsjError'] = form_error('flywheel');
                    }

                    // validamos el tipo de archivo
                } elseif ($image_upload and !in_array($arrFILE['type'], array('image/jpeg', 'image/png', 'image/gif', 'image/jpeg'))) {
                    $this->arrDatos['sMsjError'] = 'Only image formats are allowed JPG,PNG,JPEG,GIF';

                    // validamos el tamaño de archivo
                } elseif ($image_upload and $arrFILE['size'] > $this->max_size_file) {
                    $this->arrDatos['sMsjError'] = 'The image size can not exceed ' . $this->str_max_size_file;
                    # --- fin validaciones del archivo a subir..

                } else {
                    // si seleccionaron una imagen. la subimos
                    if ($image_upload) {
                        // obtenemos el nombre del artista y lo pasamos para renombrar las imagenes
                        $top_name = generar_url($txtOEM . '-' . $txtSKU);

                        // subimos la nueva imagen
                        $this->arrDatos['Imagen'] = procesar_imagenes($arrFILE, ('products/' . $id . '/'), FALSE, array('top_name' => $top_name));
                    }

                    // actualizamos los datos
                    if ($this->modelo->set_data($this->arrDatos)) {
                        $this->modelo->set_field('controller', 'panel_applications');

                        // indicamos que se actualizo el registro
                        $this->modelo->set_field('submit', 1);
                        $this->modelo->set_field('msj', '¡Successfully updated data');

                        // recargamos la vista con lo nuevos datos
                        redirect('panel_products/upd/' . $this->arrDatos['id']);
                    } else {
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            } else {

                $arrDatos = $this->modelo->get_data($id);
                if (is_array($arrDatos) and count($arrDatos) > 0) {
                    $this->arrDatos['id'] = $id;
                    $this->arrDatos['url'] = $arrDatos['url_post'];

                    $this->id_producto = $id;
                    $this->arrDatos['id_producto'] = $this->id_producto;
                    $this->arrDatos['selLinea'] = $arrDatos['linea'];
                    $this->arrDatos['selTiposProductos'] = $arrDatos['id_tipo_producto'];
                    $this->arrDatos['txtNombre_en'] = $arrDatos['nombre_en'];
                    $this->arrDatos['txtNombre_es'] = $arrDatos['nombre_es'];
                    $this->arrDatos['selNewRelease'] = $arrDatos['new_release'];
                    $this->arrDatos['txtaDescripcionEn'] = $arrDatos['descripcion_en'];
                    $this->arrDatos['txtaDescripcionEs'] = $arrDatos['descripcion_es'];
                    $this->arrDatos['txtSKU'] = $arrDatos['sku'];
                    $this->arrDatos['txtOEM'] = $arrDatos['oem'];
                    $this->arrDatos['txtSMP'] = $arrDatos['smp'];
                    $this->arrDatos['txtWells'] = $arrDatos['wells'];
                    $this->arrDatos['txtSellPrice'] = $arrDatos['precio'];
                    $this->arrDatos['imagen_actual'] = $arrDatos['path_img'];
                    $this->arrDatos['selEstatus'] = $arrDatos['estatus'];
                    $this->arrDatos['flywheel'] = $arrDatos['flywheel'];
                    $this->arrDatos['cover'] = $arrDatos['cover'];
                    $this->arrDatos['disc_info'] = $arrDatos['disc_info'];
                    $this->arrDatos['txtDai'] = $arrDatos['txtDai'];
                    $this->arrDatos['notes'] = $arrDatos['notes1']." ".$arrDatos['notes2'];

                    // cuando actualizamos, recargamos la web con los nuevos datos
                    // en ese caso mostramos un mensaje de confirmacion
                    if ($this->modelo->get_field('submit') == 1) {
                        $this->arrDatos['sMsjConf'] = $this->modelo->get_field('msj');
                        // reset
                        $this->modelo->set_field('submit', 0);
                    }
                }
            }

            $this->arrDatos['title'] = 'Edit Product';
            $this->arrDatos['vista'] = 'panel_products_upd_v';
            // indico en que controlador estoy..
            $this->modelo->set_field('controller', 'panel_products');
            $this->load->view('panel/includes/template', $this->arrDatos);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';
        } else {
            redirect('panel_inicio');
        }
    }

    // gestiona la eliminacion
    function del($id_producto = '')
    {
        // si esta vacio el parametros, lo enviamos al admin
        $id_producto = (int)$id_producto;
        // solo los administradores y operadores pueden eliminar
        if (empty($id_producto)) {
            // indicamos el tipo de problema y redireccionamos
            $this->modelo->set_field('submit', 2);
            $this->modelo->set_field('msj', '¡Incorrect parameters.!');
        } elseif (!user_is_admin() and !user_is_operador()) {
            // indicamos el tipo de problema y redireccionamos
            $this->modelo->set_field('submit', 2);
            $this->modelo->set_field('msj', '¡Your account does not have permission to delete data!');
        } else {
            // eliminamos el registro de la base de datos
            $this->modelo->delete($id_producto);
            // indicamos que se elimino el registro
            $this->modelo->set_field('submit', 1);
            $this->modelo->set_field('msj', '¡Successfully delete data');
        }
        redirect('panel_products');
    }

    function app($id_producto)
    {
        //$this->modelo->set_field('id_producto', $id_producto);
        $this->session->set_userdata('id_producto', $id_producto);

        redirect('panel_applications');
    }

    public function get_json_tipos_productos()
    {
        echo $this->modelo->get_json_tipos_productos();
    }

    public function export()
    {


        ini_set("memory_limit", "512M");
        $arrDatos = $this->modelo->get_data_export();
        if (is_array($arrDatos)) {
            //echo '<pre>',print_r($arrDatos),'</pre>';exit;
            /*(
                [oem] => IC1049
                [new_release] => 0
                [tipo_producto_en] => Ignition Coil
                [tipo_producto_es] => Ignition Coil
                [imagen] =>
                [smp] => UF114
                [wells] => C1049
                [sku] => IC1049
                [sell_price] =>
                [marca] => Dodge
                [modelo] => Atos
                [motor] => L4-1.0L
                [years] => 2001,2002,2003,2004
            )
             */

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
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'OEM')
                ->setCellValue('B1', 'New Release?')
                ->setCellValue('C1', 'Product Type English')
                ->setCellValue('D1', 'Product Type Spanish')
                ->setCellValue('E1', 'SMP/Anchor')
                ->setCellValue('F1', 'Wells/Westar')
                ->setCellValue('G1', 'SKU')
                ->setCellValue('H1', 'Sell Price')
                ->setCellValue('I1', 'Marca')
                ->setCellValue('J1', 'Modelo')
                ->setCellValue('K1', 'Motor')
                ->setCellValue('L1', 'Año 1')
                ->setCellValue('M1', 'Año 2')
                ->setCellValue('N1', 'Año 3')
                ->setCellValue('O1', 'Año 4')
                ->setCellValue('P1', 'Año 5')
                ->setCellValue('Q1', 'Año 6')
                ->setCellValue('R1', 'Año 7')
                ->setCellValue('S1', 'Año 8')
                ->setCellValue('T1', 'Año 9')
                ->setCellValue('U1', 'Año 10')
                ->setCellValue('V1', 'Año 11')
                ->setCellValue('W1', 'Año 12')
                ->setCellValue('X1', 'Año 13')
                ->setCellValue('Y1', 'Año 14')
                ->setCellValue('Z1', 'Año 15')
                ->setCellValue('AA1', 'Año 16')
                ->setCellValue('AB1', 'Año 17')
                ->setCellValue('AC1', 'Año 18')
                ->setCellValue('AD1', 'Año 19')
                ->setCellValue('AE1', 'Año 20')
                ->setCellValue('AF1', 'Año 21')
                ->setCellValue('AG1', 'Año 22')
                ->setCellValue('AH1', 'Año 23')
                ->setCellValue('AI1', 'Año 24')
                ->setCellValue('AJ1', 'Año 25')
                ->setCellValue('AK1', 'Año 26')
                ->setCellValue('AL1', 'Año 27')
                ->setCellValue('AM1', 'Año 28')
                ->setCellValue('AN1', 'Año 29')
                ->setCellValue('AO1', 'Año 30')
                ->setCellValue('AP1', 'Año 31')
                ->setCellValue('AQ1', 'Año 32')
                ->setCellValue('AR1', 'Año 33')
                ->setCellValue('AS1', 'Año 34')
                ->setCellValue('AT1', 'Año 35')
                ->setCellValue('AU1', 'Año 36')
                ->setCellValue('AV1', 'Año 37')
                ->setCellValue('AW1', 'Año 38')
                ->setCellValue('AX1', 'Año 39')
                ->setCellValue('AY1', 'Año 40')
                ->setCellValue('AZ1', 'Linea')
                ->setCellValue('BA1', 'Flywheel')
                ->setCellValue('BB1', 'Cover')
                ->setCellValue('BC1', 'Disc Diameter - N. Disc Splines - Disc Hub Size')
                ->setCellValue('BD1', 'Notes 1')
                ->setCellValue('BE1', 'Notes 2')
                ->setCellValue('BF1', 'DAI');



            $c = 1;#l ultima fila usada
            foreach ($arrDatos as $i => $item) {
                $c++;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $c, $item['oem'])
                    ->setCellValue('B' . $c, ($item['new_release'] ? 'S' : 'N'))
                    ->setCellValue('C' . $c, $item['tipo_producto_en'])
                    ->setCellValue('D' . $c, $item['tipo_producto_es'])
                    ->setCellValue('E' . $c, $item['smp'])
                    ->setCellValue('F' . $c, $item['wells'])
                    ->setCellValue('G' . $c, $item['sku'])
                    ->setCellValue('H' . $c, $item['sell_price'])
                    ->setCellValue('I' . $c, $item['marca'])
                    ->setCellValue('J' . $c, $item['modelo'])
                    ->setCellValue('K' . $c, $item['motor'])
                    ->setCellValue('AZ' . $c, $item['linea'])
                    ->setCellValue('BA' . $c, $item['flywheel'])
                    ->setCellValue('BB' . $c, $item['cover'])
                    ->setCellValue('BC' . $c, $item['disc_info'])
                    ->setCellValue('BD' . $c, $item['notes_1'])
                    ->setCellValue('BE' . $c, $item['notes_2'])
                    ->setCellValue('BF' . $c, $item['dai']);

                #years
                # limpiamos los like
                $item['years'] = trim($item['years']);
                $item['years'] = trim($item['years'], ',');
                $arrLetter = array(
                    'L' . $c,#1
                    'M' . $c,#2
                    'N' . $c,#3
                    'O' . $c,#4
                    'P' . $c,#
                    'Q' . $c,#
                    'R' . $c,#7
                    'S' . $c,#8
                    'T' . $c,#9
                    'U' . $c,#10
                    'V' . $c,#11
                    'W' . $c,#12
                    'X' . $c,#13
                    'Y' . $c,#14
                    'Z' . $c,#
                    'AA' . $c,#
                    'AB' . $c,#17
                    'AC' . $c,#18
                    'AD' . $c,#19
                    'AE' . $c,#20
                    'AF' . $c,#21
                    'AG' . $c,#22
                    'AH' . $c,#23
                    'AI' . $c,#24
                    'AJ' . $c,#
                    'AK' . $c,#
                    'AL' . $c,#27
                    'AM' . $c,#28
                    'AN' . $c,#29
                    'AO' . $c,#30
                    'AP' . $c,#31
                    'AQ' . $c,#32
                    'AR' . $c,#33
                    'AS' . $c,#34
                    'AT' . $c,#
                    'AU' . $c,#
                    'AV' . $c,#37
                    'AW' . $c,#38
                    'AX' . $c,#39
                    'AY' . $c,#40
                );

                # armamos un array y lo recorremos
                $arrYears = explode(',', $item['years']);
                if (is_array($arrYears)) {
                    foreach ($arrYears as $index => $year) {
                        $year = trim($year);
                        if (isset($arrLetter[$index])) {
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arrLetter[$index], $year);
                        }
                    }
                }
            }# end foreach


            // Miscellaneous glyphs, UTF-8
            /*$objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A4', 'Miscellaneous glyphs')
                        ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('EMG Products & Applications');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="EMG_products.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;

        } else {
            ini_set("memory_limit", "512M");
            if ($this->input->post('btUpload')) {
                $arrFILE = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
                //echo '<pre>',print_r($_FILES),'</pre>';exit;

                if (!isset($arrFILE['name']) or empty($arrFILE['name'])) {
                    $this->arrDatos['sMsjError'] = 'Debe seleccionar un archivo.';

                    #'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv',
                } elseif (!in_array($arrFILE['type'], array('application/octet-stream', 'application/excel', 'application/vnd.msexcel', 'application/excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))) {
                    $this->arrDatos['sMsjError'] = 'Sólo se permiten los formatos XLS, XLSX. actual(' . $arrFILE['type'] . ')';

                } elseif ($arrFILE['size'] > $this->max_size_file) {
                    $this->arrDatos['sMsjError'] = 'The image size can not exceed ' . $this->str_max_size_file;
                } else {
                    $path_destino = './includes/csv/import/';
                    if (isset($arrFILE)) {
                        $new_name = encriptar_cadena(rnd_string(5) . rnd_string(5));
                        $old_name = $arrFILE["name"];
                        $trozos = explode('.', basename($old_name));
                        $new_name .= '.' . end($trozos);
                        $file = $path_destino . $new_name;
                        #eliminamos lso existentes
                        clear_dirctory($path_destino);
                        if (move_uploaded_file($arrFILE['tmp_name'], $file)) {
                            $this->session->set_userdata('file', $file);
                            $this->arrDatos['file'] = $file;
                            $this->arrDatos['sMsjConf'] = 'Archivo <strong>subido</strong> correctamente.';
                            $this->arrDatos['type'] = html_escape($arrFILE['type']);
                            $this->arrDatos['size'] = html_escape($arrFILE['size']);
                        } else {
                            $this->arrDatos['sMsjError'] = 'Error al subir el archivo, intentelo más tarde.';
                        }
                    }
                }
            } elseif ($this->input->post('btProcess')) {
                $file = $this->session->userdata('file');
                if (!empty($file)) {
                    if ($OpcionesImportacion = $this->input->post('selOpcionesImportacion')) {
                        switch ($OpcionesImportacion) {
                            case 'delete_database': {
                                $this->modelo->truncate_all_data_prodcuts();
                                break;
                            }
                            default: {
                            }
                        }
                    }

                    $arrProceso = $this->_procesar_excel_import($file);
                    //echo '<pre>',print_r($arrProceso),'</pre>';exit;

                    if ($arrProceso['process'] == TRUE) {
                        $this->arrDatos['arrEstadisticas'] = $arrProceso;
                        $this->arrDatos['sMsjConf'] = 'Archivo <strong>procesado</strong> correctamente.';

                    } elseif ($arrProceso['process'] == FALSE) {
                        $this->arrDatos['sMsjError'] = $arrProceso['msjError'];
                    } else {
                        $this->arrDatos['sMsjError'] = 'Error[2] al intentar procesar el archivo.';
                    }
                } else {
                    $this->arrDatos['sMsjError'] = 'Error[1] al intentar procesar el archivo.';
                }
                $this->session->set_userdata('file', '');
                $this->arrDatos['file'] = '';
            } else {
                $this->session->set_userdata('file', '');
                $this->arrDatos['file'] = '';
            }
            $this->arrDatos['title'] = 'Import Product';
            $this->arrDatos['vista'] = 'panel_products_import_v';
            $this->session->set_userdata('controller', 'panel_products');
            $this->load->view('panel/includes/template', $this->arrDatos);
        }


    }

    public function import(){
        ini_set("memory_limit","512M");
        if($this->input->post('btUpload')){
            $arrFILE = isset($_FILES['filFILE']) ? $_FILES['filFILE'] : '';
            //echo '<pre>',print_r($_FILES),'</pre>';exit;

            if(!isset($arrFILE['name']) or empty($arrFILE['name'])){
                $this->arrDatos['sMsjError'] = 'Debe seleccionar un archivo.';

                #'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv',
            }elseif(!in_array($arrFILE['type'],array('application/octet-stream','application/excel', 'application/vnd.msexcel','application/excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))){
                $this->arrDatos['sMsjError'] = 'Sólo se permiten los formatos XLS, XLSX. actual('.$arrFILE['type'].')';

            }elseif($arrFILE['size']>$this->max_size_file){
                $this->arrDatos['sMsjError'] = 'The image size can not exceed '.$this->str_max_size_file;
            }else{
                $path_destino='./includes/csv/import/';
                if(isset($arrFILE)){
                    $new_name  = encriptar_cadena(rnd_string(5).rnd_string(5));
                    $old_name  = $arrFILE["name"];
                    $trozos    = explode('.',basename($old_name));
                    $new_name .= '.'.end($trozos);
                    $file      = $path_destino.$new_name;
                    #eliminamos lso existentes
                    clear_dirctory($path_destino);
                    if(move_uploaded_file($arrFILE['tmp_name'],$file)){
                        $this->session->set_userdata('file',$file);
                        $this->arrDatos['file'] = $file;
                        $this->arrDatos['sMsjConf'] = 'Archivo <strong>subido</strong> correctamente.';
                        $this->arrDatos['type']     = html_escape($arrFILE['type']);
                        $this->arrDatos['size']     = html_escape($arrFILE['size']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Error al subir el archivo, intentelo más tarde.';
                    }
                }
            }
        }elseif($this->input->post('btProcess')){
            $file = $this->session->userdata('file');
            if(!empty($file)){
                if($OpcionesImportacion = $this->input->post('selOpcionesImportacion')){
                    switch($OpcionesImportacion){
                        case 'delete_database': {
                            $this->modelo->truncate_all_data_prodcuts();
                            break;
                        }
                        default:{}
                    }
                }

                $arrProceso = $this->_procesar_excel_import($file);
                //echo '<pre>',print_r($arrProceso),'</pre>';exit;

                if($arrProceso['process'] == TRUE){
                    $this->arrDatos['arrEstadisticas'] = $arrProceso;
                    $this->arrDatos['sMsjConf'] = 'Archivo <strong>procesado</strong> correctamente.';

                }elseif($arrProceso['process'] == FALSE){
                    $this->arrDatos['sMsjError'] = $arrProceso['msjError'];
                }else{
                    $this->arrDatos['sMsjError'] = 'Error[2] al intentar procesar el archivo.';
                }
            }else{
                $this->arrDatos['sMsjError'] = 'Error[1] al intentar procesar el archivo.';
            }
            $this->session->set_userdata('file','');
            $this->arrDatos['file'] = '';
        }else{
            $this->session->set_userdata('file','');
            $this->arrDatos['file'] = '';
        }
        $this->arrDatos['title'] = 'Import Product';
        $this->arrDatos['vista'] = 'panel_products_import_v';
        $this->session->set_userdata('controller','panel_products');
        $this->load->view('panel/includes/template',$this->arrDatos);
    }

    function _procesar_excel_import($file)
    {
        /** PHPExcel */
        require_once './includes/php/excel/PHPExcel.php';
        /** PHPExcel_IOFactory */
        require_once './includes/php/excel/PHPExcel/IOFactory.php';
        #IMPORTANDO:
        //creando un objeto lector y cargando el fichero
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($file);


        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();


        $arrProduct = array();

        #indicara si se ha hehco algun procedimiento en la base de datos
        $arrProceso = array(
            'msjError' => '',
            'msjConf' => '',
            'process' => FALSE,
            'tipos_productos_ins' => '0',
            'tipos_productos_upd' => '0',
            'tipos_productos_del' => '0',
            'tipos_motores_ins' => '0',
            'tipos_motores_upd' => '0',
            'tipos_motores_del' => '0',
            'productos_ins' => '0',
            'productos_upd' => '0',
            'productos_del' => '0',
            'productos_app_ins' => '0',
            'productos_app_upd' => '0',
            'productos_app_del' => '0',
            'marcas_ins' => '0',
            'marcas_upd' => '0',
            'marcas_del' => '0',
            'modelos_ins' => '0',
            'modelos_upd' => '0',
            'modelos_del' => '0'
        );
        //para validar la cabecera
        $arrFormatoCamposExcel = array(
            'OEM',#0
            'New Release?',#1
            'Product Type English',#Product Type #2
            'Product Type Spanish',#Product Type #3
            'SMP/Anchor',#4
            'Wells/Westar',#5
            'SKU',#6
            'Sell Price',#7
            'Marca',#8
            'Modelo',#9
            'Motor',#10
            'Año 1',#11
            'Año 2',#12
            'Año 3',#13
            'Año 4',#14
            'Año 5',#15
            'Año 6',#16
            'Año 7',#17
            'Año 8',#18
            'Año 9',#19
            'Año 10',#20
            'Año 11',#21
            'Año 12',#22
            'Año 13',#23
            'Año 14',#24
            'Año 15',#25
            'Año 16',#26
            'Año 17',#27
            'Año 18',#28
            'Año 19',#29
            'Año 20',#30
            'Año 21',#31
            'Año 22',#32
            'Año 23',#33
            'Año 24',#34
            'Año 25',#35
            'Año 26',#36
            'Año 27',#37
            'Año 28',#38
            'Año 29',#39
            'Año 30',#40
            'Año 31',#41
            'Año 32',#42
            'Año 33',#43
            'Año 34',#44
            'Año 35',#45
            'Año 36',#46
            'Año 37',#7
            'Año 38',#48
            'Año 39',#49
            'Año 40',#50
            'Linea',#51
            'Flywheel',#52
            'Cover',#53
            'Disc Diameter - N. Disc Splines - Disc Hub Size',#54
            //'No. Disc Splines',#55
            //'Disc Hub size',#56
            'Notes 1',#55
            'Notes 2', #56
            'DAI' #57

        );

        # default data
        $arrProduct = array(
            'oem' => '',
            'new_release' => '',
            'product_type_en' => '',
            'product_type_es' => '',
            'id_tipo_producto' => '',
            'smp' => '',
            'wells' => '',
            'precio' => '',
            'sku' => '',
            'marca' => '',
            'modelo' => '',
            'tipo_motor' => ''
        );
        //nuevo proceso de iteración mas simple y cerrado
        $columnas = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P' ,'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF');

        $years= array('M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY');

        $lineavacia=0;
        $linea=0;
        $marca=0;
        for ($fila = 1; $fila <= $highestRow; $fila++){
            foreach ($columnas as $columna ) {
                $celda = $sheet->getCell($columna.$fila)->getValue();
                //validacion de la cabecera
                if ($fila == 1) {
                    if (!in_array($celda, $arrFormatoCamposExcel)) {
                        $Formato_correcto = FALSE;
                        $arrProceso['process'] = FALSE;
                        $arrProceso['msjError'] .= 'Se ha detectado que el formato del XLS es incorrecto. ' . $columna.$fila."<br>";
                    }
                }elseif($fila > 1){
                //---------------------------------------------------------------------------------------------
                //--------------VALIDACIONES DE CAMPOS---------------------------------------------------------
                    # OEM
                    if ($columna == "A") {
                        $arrProduct['oem'] = $celda;
                        if (empty($arrProduct['oem'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El OEM es obligatorio. ' . $columna.$fila."<br>";
                        }
                    # New Release
                    }elseif($columna == "B"){
                        $arrProduct['new_release'] = ($celda == 'N' ? 0 : 1);
                    # product type ingles
                    }elseif ($columna == "C") {
                        $arrProduct['product_type_en'] = formatear_celda($celda);
                        if (empty($arrProduct['product_type_en'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El tipo de producto (Ingles) es obligatorio. ' . $columna.$fila."<br>";
                        }
                    # product type español
                    }elseif ($columna == "D") {
                        $arrProduct['product_type_es'] = formatear_celda($celda);
                        if (empty($arrProduct['product_type_es'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El tipo de producto (Español) es obligatorio. ' .$columna.$fila."<br>";
                        } else {
                            # si no existe el tipo de producto, se crea y se obtiene su ID,
                            # sino, se obtiene su ID
                            $id_tipo_producto = $this->modelo->existe_product_type($arrProduct['product_type_en']);
                            if (!$id_tipo_producto) {
                                $id_tipo_producto = $this->modelo->registrar_tipo_producto($arrProduct['product_type_en'], $arrProduct['product_type_es']);
                                $arrProceso['tipos_productos_ins']++;
                                $arrProceso['process'] = TRUE;
                            } else {
                                $this->modelo->update_tipo_producto($id_tipo_producto, $arrProduct['product_type_en'], $arrProduct['product_type_es']);
                                $arrProceso['tipos_productos_upd']++;
                                $arrProceso['process'] = TRUE;
                            }
                            $arrProduct['id_tipo_producto'] = $id_tipo_producto;
                        }
                    #smp
                    }elseif ($columna == "E") {
                        $arrProduct['smp'] = $celda;
                    #wells
                    }elseif ($columna == "F") {
                        $arrProduct['wells'] = $celda;
                    #sku
                    }elseif ($columna == "G") {
                        $arrProduct['sku'] = $celda;
                        if (empty($arrProduct['sku'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El código SKU es obligatorio. ' .$columna.$fila."<br>";
                        }  
                    #sell precio
                    }elseif ($columna == "H") {
                        $arrProduct['precio'] = $celda;
                    #marca
                    }elseif ($columna == "I") {
                        $arrProduct['marca'] = $celda;
                        $marca=1;
                        if (empty($arrProduct['marca'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Marca es obligatorio. ' .$columna.$fila."<br>";
                            $marca=0;
                        } else {
                            # si no existe la marca, se crea y se obtiene su ID,
                            # sino, se obtiene su ID
                            $id_marca = $this->modelo->existe_marca($arrProduct['marca']);
                            if (!$id_marca) {
                                $id_marca = $this->modelo->registrar_marca($arrProduct['marca']);
                                $arrProceso['marcas_ins']++;
                                $arrProceso['process'] = TRUE;
                            }
                            $arrProduct['id_marca'] = $id_marca;
                        }        
                    #modelo
                    }elseif ($columna == "J") {
                        $arrProduct['modelo'] = formatear_celda($celda);
                        if (empty($arrProduct['modelo'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Modelo es obligatorio. '.$columna.$fila."<br>";
                        } else {
                            # si no existe el modelo, se crea y se obtiene su ID,
                            # sino, se obtiene su ID
                            if ($marca==1) {
                                if (!$id_modelo = $this->modelo->existe_modelo($arrProduct['id_marca'], $arrProduct['modelo'])) {
                                    $id_modelo = $this->modelo->registrar_modelo($arrProduct['id_marca'], $arrProduct['modelo']);
                                    $arrProceso['process'] = TRUE;
                                    $arrProceso['modelos_ins']++;
                                }
                                $arrProduct['id_modelo'] = $id_modelo;
                            }else{
                                $arrProceso['process'] = FALSE;
                                $arrProceso['msjError'] .= 'El campo Marca es obligatorio para registrar el modelo ' .$columna.$fila."<br>";
                            }
                        }
                    #motor
                    }elseif ($columna == "K"){
                        $arrProduct['tipo_motor'] = formatear_celda($celda);
                        if (empty($arrProduct['tipo_motor'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Tipo de motor es obligatorio. '.$columna.$fila."<br>";
                        } else {
                            # si no existe el tipo de motor, se crea y se obtiene su ID,
                            # sino, se obtiene su ID
                            if (!$id_tipo_motor = $this->modelo->existe_tipo_motor($arrProduct['tipo_motor'])) {
                                $id_tipo_motor = $this->modelo->registrar_tipo_motor($arrProduct['tipo_motor']);
                                $arrProceso['process'] = TRUE;
                                $arrProceso['tipos_motores_ins']++;
                            }
                            $arrProduct['id_tipo_motor'] = $id_tipo_motor;
                        }
                    #years primer año
                    }elseif ($columna == "L") {
                        $arrProduct['years'] = '';
                        $val = $celda;
                        if (!empty($val)) {
                            $arrProduct['years'] = $celda;
                        }

                    #todos los demas años
                    }elseif (in_array($columna, $years)) {
                        if (!empty($celda)) {
                            $arrProduct['years'] .= ',' . $celda;
                        }
                        $arrProduct['years'] = trim($arrProduct['years'], ',');     
                    #Linea               #
                    }elseif ($columna == "AZ") {
                        $arrProduct['linea'] = $celda;
                        $linea=$celda;
                        if (empty($arrProduct['linea'])) {
                            $lineavacia++;
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Linea es obligatorio. ' .$columna.$fila."<br>";
                        }
                        if (!is_numeric($arrProduct['linea'])) {
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Linea Debe ser un número. ' .$columna.$fila."<br>";
                        }
                        if ($arrProduct['linea']>3 || $arrProduct['linea'] < 1) {
                            $arrProceso['process'] = FALSE;
                            $arrProceso['msjError'] .= 'El campo Linea Debe ser 1 o 2. ' .$columna.$fila."<br>";
                        }                    
                    #flywheel
                    }elseif ($columna == "BA") {
                        $arrProduct['flywheel'] = $celda;

                    }elseif ($columna == "BB") {
                        $arrProduct['cover'] = $celda;
                    }elseif ($columna == "BC") {
                        $arrProduct['disc_info'] = $celda;
                   /* } elseif ($columna == "BD") {
                        $arrProduct['disc_splines'] = $celda;
                    } elseif ($columna == "BE") {
                        $arrProduct['disc_hub_size'] = $celda;*/
                    } elseif ($columna == "BD") {
                        $arrProduct['notes_1'] = $celda;
                    } elseif ($columna == "BE") {
                        $arrProduct['notes_2'] = $celda;
                    } elseif ($columna == "BF") {
                        $arrProduct['dai'] = $celda;
                    }

                }
            }

            //si el proceso está correcto
            if ($arrProceso['process'] == TRUE) {
                $arrDatos['txtSKU'] = $arrProduct['sku'];
                $arrDatos['selTiposProductos'] = (int)$arrProduct['id_tipo_producto'];
                $arrDatos['selNewRelease'] = $arrProduct['new_release'];
                $arrDatos['txtOEM'] = $arrProduct['oem'];
                $arrDatos['txtSMP'] = $arrProduct['smp'];
                $arrDatos['txtWells'] = $arrProduct['wells'];
                $arrDatos['txtSellPrice'] = $arrProduct['precio'];
                $arrDatos['txtFlywheel'] = isset($arrProduct['flywheel']) ? $arrProduct['flywheel'] : "";
                $arrDatos['txtCover'] = isset($arrProduct['cover']) ? $arrProduct['cover'] : "";
                $arrDatos['txtDisc_info'] = isset($arrProduct['disc_info']) ? $arrProduct['disc_info'] : "";
                //$arrDatos['txtDisc_splines'] = isset($arrProduct['disc_splines']) ? $arrProduct['disc_splines'] : "";
                //$arrDatos['txtDisc_hub_size'] = isset($arrProduct['disc_hub_size']) ? $arrProduct['disc_hub_size'] : "";
                $arrDatos['txtNotes_1'] = isset($arrProduct['notes_1']) ? $arrProduct['notes_1'] : "";
                $arrDatos['txtNotes_2'] = isset($arrProduct['notes_2']) ? $arrProduct['notes_2'] : "";
             //   $arrDatos['txtCylinder'] = isset($arrProduct['cylinder']) ? $arrProduct['cylinder'] : "";
                $arrDatos['txtLinea'] = isset($arrProduct['linea']) ? $arrProduct['linea'] : "";
                $arrDatos['txtDai'] = isset($arrProduct['dai']) ? $arrProduct['dai'] : "";

                $id_producto = $this->modelo->existe_producto($arrProduct['sku']);
                $arrDatos['id'] = $id_producto;

                # productos
                if (!$id_producto) {
                    $id_producto = $this->modelo->registrar($arrDatos);
                    $arrProceso['productos_ins']++;
                } else {
                    $arrProceso['productos_upd']++;
                }

                # aplicaciones
                if (!empty($id_producto)) {
                    # ahora es el turno de la aplicacion del producto
                    # una misma aplicaciones se puede registrar varias veces porque cambian los años
                    $this->modelo->registrar_aplicacion_producto($id_producto, $arrProduct['id_tipo_motor'], $arrProduct['id_marca'], $arrProduct['id_modelo'], $arrProduct['years']);
                    $arrProceso['productos_app_ins']++;
                }
            }
            unset($arrDatos);
            unset($arrProduct);
        }

        if (!empty($arrProceso['msjError'])) {
            $arrProceso['process']=FALSE;
        }

        return $arrProceso;

    }

    public function get_json_marcas()
    {
        echo get_json_marcas();
    }

    public
    function get_json_modelos($id = '')
    {
        $id = (int)$id;
        echo get_json_modelos($id);
    }

    public
    function get_json_tipos_motores()
    {
        echo get_json_tipos_motores();
    }
}
