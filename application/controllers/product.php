<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('product_m', 'modelo');
        $this->arrDatos['title'] = '';
        $this->arrDatos['vista'] = 'product_v';
        $this->arrDatos['sMsjError'] = '';
    }

    public function index($url_post = '')
    {

        if ($url_post) {
            $this->session->set_userdata('product_url_post', $url_post);
        } elseif ($this->session->userdata('product_url_post')) {
            $url_post = $this->session->userdata('product_url_post');
        } else {
            redirect('home');
        }
        $this->arrDatos['arrContent'] = $this->modelo->get_product($url_post);
        $this->arrDatos['meta_descripcion'] = $this->arrDatos['title'] = $this->arrDatos['arrContent']['titulo'] . " - " . $this->arrDatos['arrContent']['tipo_prod'];
        $this->arrDatos['meta_keywords'] = $this->arrDatos['arrContent']['titulo'] . ", " . str_replace(" ", ", ", $this->arrDatos['arrContent']['tipo_prod']);
        $this->load->view('includes/template', $this->arrDatos);
    }

    public function pdf($id = 0)
    {

        $sesion_data = $this->sesion_data;
        if ($sesion_data["id_usuario"] > 0) {
            $this->_print('pdf', $id);
        }
        $this->index();
    }

    public function print_out($id = 0)
    {
        $this->_print('screen', $id);
    }

    public function _print($type = 'pdf', $id = 0)
    {
        if (!empty($id)) {
            $arrDataPDF = $this->modelo->get_data_pdf($id);
            if (is_array($arrDataPDF)) {
                //echo "<pre>",print_r($arrDataPDF),"</pre>";exit;
                $titulo = generar_url($arrDataPDF['titulo']) . '_' . date('d-m-Y');
                $html = '<html><head>
                          <meta charset="utf-8">
                          <title>EMG International</title>
                          <link rel="stylesheet" href="' . base_url() . 'includes/css/pdf.css">
                          </head><body>';
                $html .= '<div id="pdf-product">
                          <div class="logo"><img src="' . (base_url() . 'includes/images/logo.png') . '"></div>';
                $html .= '<div class="row">
                                <div class="imagen">';
                if (file_exists('./' . $arrDataPDF['path_img'])) {
                    $html .= '<img src="' . image($arrDataPDF['path_img'], 'product') . '">';
                }

                //si producto es emg-->
                if ($arrDataPDF['linea'] == "emg") {
                    $tablalinea = '<div class="panel panel-default">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>' . lang('line') . '</th>
                                                    <th>OEM</th>
                                                    <th>WELLS</th>
                                                    <th>SMP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-transform: uppercase;">' . $arrDataPDF['linea'] . '</td>
                                                    <td>' . $arrDataPDF['oem'] . '</td>
                                                    <td>' . $arrDataPDF['wells'] . '</td>
                                                    <td>' . $arrDataPDF['smp'] . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>';
                }

                //si producto es perfection-->
                if ($arrDataPDF['linea'] == "perfection") {
                    $tablalinea = '<div class="panel panel-default">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-transform: uppercase;">' . lang('line') . '</th>
                                                    <th>FLYWHEEL</th>
                                                    <th>COVER</th>
                                                    <th>DISC INFO</th>
                                                    <th>NOTES 1</th>
                                                    <th>NOTES 2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-transform: uppercase;">' . $arrDataPDF['linea'] . '</td>
                                                    <td>' . $arrDataPDF['flywheel'] . '</td>
                                                    <td>' . $arrDataPDF['cover'] . ' </td>
                                                    <td>' . $arrDataPDF['disc_info'] . '</td>
                                                    <td>' . $arrDataPDF['wells'] . '</td>
                                                    <td>' . $arrDataPDF['smp'] . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>';
                }

                //si producto es mrc-->
                if ($arrDataPDF['linea'] == "mrc") {
                    $tablalinea = '<div class="panel panel-default">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>' . lang('line') . '</th>
                                                    <th>OEM</th>
                                                    <th>ANCHOR</th>
                                                    <th>WESTER</th>
                                                    <th>DAI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-transform: uppercase;">' . $arrDataPDF['linea'] . '</td>
                                                    <td>' . $arrDataPDF['oem'] . '</td>
                                                    <td>' . $arrDataPDF['wells'] . '</td>
                                                    <td>' . $arrDataPDF['smp'] . '</td>
                                                    <td>' . $arrDataPDF['dai'] . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>';
                }


                $html .= '</div>
                            <div class="titulo">
                                <a href="' . site_url('product') . '/index/' . $arrDataPDF['url_post'] . '">' . $arrDataPDF['titulo'] . '</a>
                            </div>
                            <div class="datos-linea">' . $arrDataPDF['datos_linea'] . '</div>';
                if ($arrDataPDF['descripcion']) {
                    echo '<div class="descripcion">' . $arrDataPDF['descripcion'] . '<div>';
                }
                $html .= $tablalinea;
                if (isset($arrDataPDF['arrApplicaciones']) and is_array($arrDataPDF['arrApplicaciones'])) {
                    $html .= '<div class="sub-titulo">' . lang('applications') . '</div>
                                     <div class="tabla-content">
                                        <table>
                                           <thead>
                                               <tr>
                                                   <th>' . lang('field_manufactute_model') . '</th>
                                                   <th>' . lang('field_engine_type') . '</th>
                                                   <th>' . lang('field_years') . '</th>
                                               </tr>
                                           </thead>
                                           <tbody>';
                    foreach ($arrDataPDF['arrApplicaciones'] as $arrData) {
                        $html .= '<tr>
                                                       <td>' . $arrData['marca_modelo'] . '</td>
                                                       <td>' . $arrData['tipo_motor'] . '</td>
                                                       <td>' . $arrData['years'] . '</td>
                                                       </tr>';
                    }
                    $html .= '</tbody>
                                       </table>
                                  <div>
                            </div>';
                }#end tabla
                $html .= '</div>
                </div>';
                $html .= '</div>';
                $html .= '<div><br><h5 align="center">EMG International ' . date('d/m/Y H:i:s') . '</h5></div>';
                $html .= '</body></html>';

                if ($type == 'screen') {
                    exit($html);
                } elseif ($type == 'pdf') {
                    generar_pdf_dompdf($html, $titulo, TRUE);
                }
            }
        }
    }
}