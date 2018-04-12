<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products_m extends CI_Model
{
    protected $db_search;

    public function __construct()
    {
        parent::__construct();
        $this->db_search = $this->load->database('emg_search', TRUE);
    }

    function get_products($type = 'default_search', $arrParam, $limit = 0, $desde = 0, $order_by = '', $selUpDown = '')
    {

        $order_by = (int)$order_by;
        switch ($order_by) {
            case 1: {#
                $order_by = 'tp.nombre_en';
                break;
            }
            case 2: {#t
                $order_by = 'tp.nombre_es';
                break;
            }
            case 3: {#sku
                $order_by = 'p.sku';
                break;
            }
            case 4: {#oem
                $order_by = 'oem.nombre';
                break;
            }
            case 5: {#id
                $order_by = 'p.id_tipo_producto';
                break;
            }
            default: {
                $order_by = 'p.sku';
            }
        }
        $selUpDown = (int)$selUpDown;
        switch ($selUpDown) {
            case 1: {#ascendente
                $selUpDown = 'ASC';
                break;
            }
            case 2: {#descendente
                $selUpDown = 'DESC';
                break;
            }
            default: {
                $selUpDown = 'ASC';
            }
        }


        # ----------------------------------------------------------------------
        # search_default -------------------------------------------------------
        # ----------------------------------------------------------------------
        $lang = get_lang();
        if ($type == 'default_search') {

            $this->db_search->select("p.id_producto,p.oem,p.url_post,p.path_img,
                                p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                                p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                                p.descripcion_es,p.precio,
                                tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es,GROUP_CONCAT(DISTINCT oem.nombre SEPARATOR'???') AS oemn,
                                GROUP_CONCAT(DISTINCT smp.nombre SEPARATOR'???') AS smpn,GROUP_CONCAT(DISTINCT wells.nombre SEPARATOR'???') AS wellsn")
                ->from('productos AS p')
                ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto')
                ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto', 'left')
                ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto', 'left')
                ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto', 'left')
                ->join('productos_dai AS dai', 'dai.id_producto = p.id_producto', 'left')
                ->where('p.estatus', '1')
                ->group_by("p.id_producto");


            # filtros del buscador
            if (is_array($arrParam)) {
                if (isset($arrParam['sku']) and !empty($arrParam['sku'])) {
                    $this->db_search->where("REPLACE(REPLACE(REPLACE(p.sku,'-', ''),'/',''),' ','')=", $this->caracter($arrParam['sku']));
                }
                if (isset($arrParam['oemSmpWells']) and !empty($arrParam['oemSmpWells'])) {
                    $this->db_search->where("REPLACE(REPLACE(REPLACE(oem.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.oem,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(smp.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.smp,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(wells.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.wells,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(dai.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));

                }
                if (isset($arrParam['linea']) and !empty($arrParam['linea'])) {
                    $this->db_search->where("p.linea", $arrParam['linea']);
                }

                /*if(isset($arrParam['smp']) and !empty($arrParam['smp'])){
                    $this->db_search->where("REPLACE(REPLACE(REPLACE(smp.nombre,'-', ''),'/',''),' ','') =",$this->caracter($arrParam['smp']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.smp,'-', ''),'/',''),' ','') =",$this->caracter($arrParam['smp']));
                }
                if(isset($arrParam['wells']) and !empty($arrParam['wells'])){
                    $this->db_search->where("REPLACE(REPLACE(REPLACE(wells.nombre,'-', ''),'/',''),' ','') =",$this->caracter($arrParam['wells']));
                    $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.wells,'-', ''),'/',''),' ','') =",$this->caracter($arrParam['wells']));
                }*/

                if (isset($arrParam['tipo_producto']) and !empty($arrParam['tipo_producto'])) {
                    $this->db_search->where('p.id_tipo_producto', $arrParam['tipo_producto']);
                }

                # -- filter applications
                $arrParam_app = '';
                if (isset($arrParam['tipo_motor']) and !empty($arrParam['tipo_motor'])) {
                    $arrParam_app['tipo_motor'] = $arrParam['tipo_motor'];
                    unset($arrParam['tipo_motor']);
                }
                if (isset($arrParam['marca']) and !empty($arrParam['marca'])) {
                    $arrParam_app['marca'] = $arrParam['marca'];
                    unset($arrParam['marca']);
                }
                if (isset($arrParam['modelo']) and !empty($arrParam['modelo'])) {
                    $arrParam_app['modelo'] = $arrParam['modelo'];
                    unset($arrParam['modelo']);
                }
                if (isset($arrParam['years']) and !empty($arrParam['years'])) {
                    $arrParam_app['years'] = $arrParam['years'];
                    unset($arrParam['years']);
                }
            }

            $this->db_search->order_by($order_by, $selUpDown);
            if (!empty($limit)) {
                $this->db_search->limit($limit, $desde);
            }

            $query = $this->db_search->get();

            //echo  $this->db_search->last_query(); die();

            if ($query->num_rows() > 0) {
                $arrResult = array();
                foreach ($query->result() as $row) {
                    $id_producto = (int)$row->id_producto;
                    # obtenemos las aplicaciones del producto
                    $arrApplicaciones = get_applications_by_product($arrParam_app, $id_producto);
                    //echo "<pre>",print_r($arrApplicaciones),"</pre>";
                    # si hay parametros y no hay resultados, no cargamos datos
                    if (is_array($arrParam_app) and !is_array($arrApplicaciones)) {
                        //continue;
                    }
                    # gestion del lenguaje
                    if ($lang == 'es') {
                        $datos_linea = 'Tipo: ' . $row->tipo_producto_es;
                        $descripcion = $row->descripcion_es;
                    } elseif ($lang == 'en') {
                        $datos_linea = 'Type: ' . $row->tipo_producto_en;
                        $descripcion = $row->descripcion_en;
                    }
                    $arrResult[] = array(
                        'id' => $id_producto,
                        'url_post' => html_escape($row->url_post),
                        'titulo' => html_escape($row->sku),
                        'path_img' => (!empty($row->path_img) ? 'media/products/' . html_escape($id_producto . '/' . $row->path_img) : 'media/default/producto.png'),
                        'datos_linea' => html_escape($datos_linea),
                        'descripcion' => html_escape(extract_string($descripcion, 200)),
                        'arrApplicaciones' => $arrApplicaciones
                    );
                }
                return $arrResult;
            } else {
                return FALSE;
            }

            # -----------------------------------------------------------------------
            # by_vechile_search -----------------------------------------------------
            # -----------------------------------------------------------------------
        } elseif ($type == 'by_vehicle_search') {

            $arrParam_app = [];
            $contador = 0;
            # filtros del buscador

            if (is_array($arrParam)) {

                # -- filter applications
                if (isset($arrParam['years']) and !empty($arrParam['years'])) {
                    $arrParam_app['years'] = $arrParam['years'];
                    unset($arrParam['years']);
                    $contador++;
                }
                if (isset($arrParam['marca']) and !empty($arrParam['marca'])) {
                    $arrParam_app['marca'] = $arrParam['marca'];
                    unset($arrParam['marca']);
                    $contador++;
                }
                if (isset($arrParam['modelo']) and !empty($arrParam['modelo'])) {
                    $arrParam_app['modelo'] = $arrParam['modelo'];
                    unset($arrParam['modelo']);
                    $contador++;
                }
                if (isset($arrParam['tipo_motor']) and !empty($arrParam['tipo_motor'])) {
                    $arrParam_app['tipo_motor'] = $arrParam['tipo_motor'];
                    // unset($arrParam['tipo_motor']);
                    $contador++;
                }

                # tipos de productos
                if (isset($arrParam['tipo_producto']) and !empty($arrParam['tipo_producto'])) {
                    $arrParam_app['tipo_producto'] = $arrParam['tipo_producto'];
                    unset($arrParam['tipo_producto']);
                    $contador++;
                }

                if (isset($arrParam['linea']) and !empty($arrParam['linea'])) {
                    $arrParam_app['linea'] = $arrParam['linea'];
                    unset($arrParam['linea']);
                    $contador++;
                }
            }

            # deben estar introducido al menos los dos primeros filtros
            # actualmente son solo 4 filtros
            if ($contador >= 2) {
                $arrApplicaciones = get_applications_by_product($arrParam_app);
                //echo "Busco.. <pre>",print_r($arrApplicaciones),"</pre>";exit;
                /*
                Array
                 [id_producto] => 605
                 [marca_modelo] => Chevrolet / Optra
                 [tipo_motor] => All
                 [years] => 2010
                 */
                $arrProductosProcesados = array();
                $arrResult = array();

                if (is_array($arrApplicaciones) and count($arrApplicaciones) > 0) {

                    # obtiene los id de los productos a filtrar
                    foreach ($arrApplicaciones as $item) {
                        if (!in_array($item['id_producto'], $arrProductosProcesados)) {
                            $arrProductosProcesados[] = $item['id_producto'];
                            /*if(isset($arrParam['tipo_producto']) and 
                               $arrParam['tipo_producto'] == $item['id_tipo_producto']){
                                    $arrProductosProcesados[] = $item['id_producto'];
                            }else{
                                $arrProductosProcesados[] = $item['id_producto'];
                            }*/
                        }
                    }

                    if (count($arrProductosProcesados) > 0) {
                        //exit("id_product: ".$item['id_producto']);
                        $this->db_search->select('p.id_producto,p.oem,p.url_post,p.path_img,
                                p.nombre_en AS producto_en,p.nombre_es AS producto_es,
                                p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
                                p.descripcion_es,p.precio,
                                tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
                            ->from('productos AS p')
                            ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto');

                        # filtra por id producto
                        foreach ($arrProductosProcesados AS $id_producto) {
                            $this->db_search->or_where('p.id_producto', $id_producto);
                        }

                        #
                        $this->db_search->where('p.estatus', '1');

                        $this->db_search->order_by($order_by, $selUpDown);
                        if (!empty($limit)) {
                            $this->db_search->limit($limit, $desde);
                        }
                        $query = $this->db_search->get();
                        if ($query->num_rows() > 0) {
                            foreach ($query->result() as $row) {
                                $id_producto = (int)$row->id_producto;
                                # obtenemos las aplicaciones del producto
                                $arrApplicaciones = get_applications_by_product($arrParam_app, $id_producto);
                                //echo "<pre>",print_r($arrApplicaciones),"</pre>";exit;
                                # gestion del lenguaje
                                if ($lang == 'es') {
                                    $datos_linea = 'Tipo: ' . $row->tipo_producto_es;
                                    $descripcion = $row->descripcion_es;
                                } elseif ($lang == 'en') {
                                    $datos_linea = 'Type: ' . $row->tipo_producto_en;
                                    $descripcion = $row->descripcion_en;
                                }

                                #
                                $arrResult[] = array(
                                    'id' => $id_producto,
                                    'url_post' => html_escape($row->url_post),
                                    'titulo' => html_escape($row->sku),
                                    'path_img' => (!empty($row->path_img) ? 'media/products/' . html_escape($id_producto . '/' . $row->path_img) : 'media/default/producto.png'),
                                    'datos_linea' => html_escape($datos_linea),
                                    'descripcion' => html_escape(extract_string($descripcion, 200)),
                                    'arrApplicaciones' => $arrApplicaciones
                                );
                            }
                        }
                        if (count($arrResult) > 0) {
                            return $arrResult;
                        } else {
                            return FALSE;
                        }
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function get_rows($type, $arrParam = array())
    {

        if ($type == 'default_search') {
            $this->db_search->select('count(*) AS total')
                ->from('productos AS p')
                ->join('productos_oem AS oem', 'oem.id_producto = p.id_producto', 'left')
                ->join('productos_smp AS smp', 'smp.id_producto = p.id_producto', 'left')
                ->join('productos_wells AS wells', 'wells.id_producto = p.id_producto', 'left')
                ->join('productos_dai AS dai', 'dai.id_producto = p.id_producto', 'left')
                ->where('p.estatus', '1')
                ->group_by("p.id_producto");

            if (isset($arrParam['sku'])) {
                $this->db_search->where('p.sku', $arrParam['sku']);
                /*$this->db_search->or_like('p.smp',$arrParam['sku']);
                $this->db_search->or_like('p.wells',$arrParam['sku']);*/
            }
            if (isset($arrParam['oemSmpWells']) and !empty($arrParam['oemSmpWells'])) {
                $this->db_search->where("REPLACE(REPLACE(REPLACE(oem.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.oem,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(smp.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.smp,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(wells.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(p.wells,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));
                $this->db_search->or_where("REPLACE(REPLACE(REPLACE(dai.nombre,'-', ''),'/',''),' ','') =", $this->caracter($arrParam['oemSmpWells']));

            }

            if (isset($arrParam['tipo_producto'])) {
                $this->db_search->where('p.id_tipo_producto', $arrParam['tipo_producto']);
            }

            if (isset($arrParam['linea']) and !empty($arrParam['linea'])) {
                $this->db_search->where("p.linea", $arrParam['linea']);
            }

            $query = $this->db_search->get();
            if ($query->num_rows() > 0) {
                return $query->num_rows();
                $row = $query->row_array();

                return (int)$row['total'];
            } else {
                return 0;
            }
            # -----------------------------------------------------------------------
        } else {
            $arrParam_app = [];
            # filtros del buscador
            if (is_array($arrParam)) {
                # -- filter applications
                if (isset($arrParam['tipo_motor'])) {
                    $arrParam_app['tipo_motor'] = $arrParam['tipo_motor'];
                    unset($arrParam['tipo_motor']);
                }
                if (isset($arrParam['marca'])) {
                    $arrParam_app['marca'] = $arrParam['marca'];
                    unset($arrParam['marca']);
                }
                if (isset($arrParam['modelo'])) {
                    $arrParam_app['modelo'] = $arrParam['modelo'];
                    unset($arrParam['modelo']);
                }
                if (isset($arrParam['years'])) {
                    $arrParam_app['years'] = $arrParam['years'];
                    unset($arrParam['years']);
                }
                if (isset($arrParam['linea'])) {
                    $arrParam_app['linea'] = $arrParam['linea'];
                    unset($arrParam['linea']);
                }
                # tipos de productos
                if (isset($arrParam['tipo_producto'])) {
                }
            }

            $arrApplicaciones = get_applications_by_product($arrParam_app);
            //echo "<pre>",print_r($arrApplicaciones),"</pre>";exit;
            /*
            Array
             [id_producto] => 605
             [id_tipo_producto] => 24
             [marca_modelo] => Chevrolet / Optra
             [tipo_motor] => All
             [years] => 2010
             */
            $arrProductosProcesados = array();
            if (is_array($arrApplicaciones)) {
                foreach ($arrApplicaciones as $item) {
                    if (!in_array($item['id_producto'], $arrProductosProcesados)) {
                        if (isset($arrParam['tipo_producto'])) {
                            if ($arrParam['tipo_producto'] == $item['id_tipo_producto']) {
                                $arrProductosProcesados[] = $item['id_producto'];
                            }
                        } else {
                            $arrProductosProcesados[] = $item['id_producto'];
                        }
                    }
                }
                return count($arrProductosProcesados);
            }
        }
        //return (int)$this->db_search->count_all('productos');
    }

    function get_json_product_type_name_by_sku($sku)
    {
        $this->db_search->select('tp.nombre_en,tp.nombre_es')
            ->from('productos AS p')
            ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto')
            ->where('p.sku', $sku);
        $this->db_search->limit(1);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $lang = get_lang();
            if ($lang == 'en') {
                return json_encode($row['nombre_en']);
            } elseif ($lang == 'es') {
                return json_encode($row['nombre_es']);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_sku($q, $linea)
    {
        $this->db_search->select('distinct(p.sku) as sku')
            ->from('productos AS p')
            ->order_by('p.sku', 'ASC')
            ->like('p.sku', $q);

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->sku));
            }
            return json_encode($arrResult);
        } else {
            return FALSE;
        }
    }

    function get_json_competitor($q, $linea)
    {
        $arrDatos = array();
        $arrOE = array();
        $arrSMP = array();
        $arrWells = array();
        $arrDai = array();


        # busca en oe
        $arrOE = $this->get_json_oem($q, TRUE, $linea);
        if (is_array($arrOE)) {
            $arrDatos = array_merge($arrDatos, $arrOE);
        }
        /* $arrOE = $this->get_json_oemnuevo($q,TRUE);
         if(is_array($arrOE)){
             $arrDatos = array_merge($arrDatos,$arrOE);
         }*/

        # busca en smp
        $arrSMP = $this->get_json_smp($q, TRUE, $linea);
        if (is_array($arrSMP)) {
            $arrDatos = array_merge($arrDatos, $arrSMP);
        }
        # busca en smp
        $arrSMP = $this->get_json_smpnuevo($q, TRUE, $linea);
        if (is_array($arrSMP)) {
            $arrDatos = array_merge($arrDatos, $arrSMP);
        }

        # busca en wells
        $arrWells = $this->get_json_wells($q, TRUE, $linea);
        if (is_array($arrWells)) {
            $arrDatos = array_merge($arrDatos, $arrWells);
        }
        # busca en wells
        $arrWells = $this->get_json_wellsnuevo($q, TRUE, $linea);
        if (is_array($arrWells)) {
            $arrDatos = array_merge($arrDatos, $arrWells);
        }

        # busca en DAI
        if ($linea == "mrc") {
            $arrDai = $this->get_json_DAI($q, TRUE, $linea);
            if (is_array($arrDai)) {
                $arrDatos = array_merge($arrDatos, $arrDai);
            }
        }


        if (count($arrDatos) > 0) {
            return json_encode($arrDatos);
        } else {
            return FALSE;
        }
    }

    function get_json_oem($q, $return_array = FALSE, $linea = null)
    {
        $filtro = "";
        if ($linea != null) {
            $filtro = " and p.linea = '" . $linea . "'  ";
        }

        $query = $this->db_search->query("
          (SELECT p.oem
          FROM productos AS p
          WHERE p.oem LIKE '" . $q . "%'
          $filtro
          ORDER BY p.oem ASC
          LIMIT 300)
          UNION
          (SELECT productos_oem.nombre
          FROM productos AS p
          LEFT JOIN productos_oem ON productos_oem.id_producto = p.id_producto
          WHERE productos_oem.nombre LIKE '" . $q . "%'
          $filtro
          ORDER BY p.oem ASC
          LIMIT 300)
          ");

        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->oem));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_oemnuevo($q, $return_array = FALSE, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->join('productos as p', 'p.id_producto = oem.id_producto');
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('distinct(oem.nombre) AS oem')
            ->from('productos_oem AS oem')
            ->order_by('oem.nombre', 'ASC')
            ->like('oem.nombre', $q);
        $this->db_search->limit(300);


        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->oem));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_smpnuevo($q, $return_array = FALSE, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->join('productos as p', 'p.id_producto = smp.id_producto');
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('distinct(smp.nombre) AS smp')
            ->from('productos_smp AS smp')
            ->order_by('smp.nombre', 'ASC')
            ->like('smp.nombre', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->smp));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_wellsnuevo($q, $return_array = FALSE, $linea = null)
    {
        if ($linea != null) {
            $this->db_search->join('productos as p', 'p.id_producto = wells.id_producto');
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('distinct(wells.nombre) AS wells')
            ->from('productos_wells AS wells')
            ->order_by('wells.nombre', 'ASC')
            ->like('wells.nombre', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->wells));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_DAI($q, $return_array = FALSE, $linea = null)
    {
        if ($linea != null) {
            $this->db_search->join('productos as p', 'p.id_producto = dai.id_producto');
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('distinct(dai.nombre) AS dai')
            ->from('productos_dai AS dai')
            ->order_by('dai.nombre', 'ASC')
            ->like('dai.nombre', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->dai));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_smp($q, $return_array = FALSE, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('p.smp')
            ->from('productos AS p')
            ->order_by('p.smp', 'ASC')
            ->like('p.smp', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->smp));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_wells($q, $return_array = FALSE, $linea)
    {

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('p.wells')
            ->from('productos AS p')
            ->order_by('p.wells', 'ASC')
            ->like('p.wells', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrResult = array();
            foreach ($query->result() as $row) {
                $arrResult[] = html_escape(extract_string($row->wells));
            }
            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    #---------------------------------------------------------------------------
    # busqueda por vehiculo
    #---------------------------------------------------------------------------
    function get_years_by_applications()
    {
        $year_now = date("Y");
        //$arrDatos = array('0' => lang('selected_year'));
        for ($i = 1927; $i <= $year_now; $i++) {
            $this->db_search->select('id_aplicaciones_producto');
            $this->db_search->distinct();
            $this->db_search->from('aplicaciones_productos');
            $this->db_search->where('estatus', 1);
            $this->db_search->like('years', $i);
            $this->db_search->limit(1);
            $query = $this->db_search->get();
            if ($query->num_rows() > 0) {
                $arrDatos[(int)$i] = (int)$i;
            }
        }
        if (count($arrDatos) > 0) {
            $yearrr = date("Y") + 1;
            $arrDatos[$yearrr] = lang('selected_year');
            return $arrDatos;
        } else {
            return FALSE;
        }
    }

    function get_json_marcas($year, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);

        }

        $this->db_search->select('m.id_marca AS id, m.nombre AS name');
        $this->db_search->distinct();
        $this->db_search->from('aplicaciones_productos AS app');
        $this->db_search->join('marcas AS m', 'm.id_marca = app.id_marca');
        $this->db_search->join('productos AS p', 'p.id_producto = app.id_producto');
        $this->db_search->where('m.estatus', 1);
        $this->db_search->like('app.years', $year);
        $this->db_search->order_by('name', 'ASC');
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrDatos[] = array('0', lang('selected_manufacture'));
            foreach ($query->result() as $row) {
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->name, ENT_QUOTES)
                );
            }
            $query->free_result();
            return json_encode($arrDatos);
        } else {
            return json_encode(array(1 => array(lang('search_not_found'))));
        }
    }

    function get_json_modelos($year, $id_marca, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);

        }

        $this->db_search->select('m.id_modelo AS id, m.nombre AS name');
        $this->db_search->distinct();
        $this->db_search->from('aplicaciones_productos AS app');
        $this->db_search->join('modelos AS m', 'm.id_modelo = app.ref_id_modelo');
        $this->db_search->join('productos AS p', 'p.id_producto = app.id_producto');
        $this->db_search->where('m.estatus', 1);
        $this->db_search->like('app.years', $year);
        $this->db_search->where('app.id_marca', $id_marca);
        $this->db_search->order_by('name', 'ASC');
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrDatos[] = array('0', lang('selected_model'));
            foreach ($query->result() as $row) {
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($row->name, ENT_QUOTES)
                );
            }
            $query->free_result();
            return json_encode($arrDatos);
        } else {
            return json_encode(array(1 => array(lang('search_not_found'))));
        }
    }

    function get_json_tipos_motores($year, $id_marca, $id_modelo, $linea = null)
    {
        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);

        }

        $this->db_search->select('tm.id_tipo_motor AS id,tm.nombre_en AS nombre_en,tm.nombre_es AS nombre_es');
        $this->db_search->distinct();
        $this->db_search->from('aplicaciones_productos AS app');
        $this->db_search->join('tipos_motores AS tm', 'tm.id_tipo_motor = app.id_tipo_motor');
        $this->db_search->join('productos AS p', 'p.id_producto = app.id_producto');
        $this->db_search->where('tm.estatus', 1);
        $this->db_search->like('app.years', $year);
        $this->db_search->where('app.id_marca', $id_marca);
        $this->db_search->where('app.ref_id_modelo', $id_modelo);
        $this->db_search->order_by('nombre_en', 'ASC');
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrDatos[] = array('0', lang('selected_engine_type'));
            foreach ($query->result() as $row) {
                $nombre = $row->nombre_en . (!empty($row->nombre_es) ? ' / ' . $row->nombre_es : '');
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($nombre, ENT_QUOTES)
                );
            }
            $query->free_result();
            return json_encode($arrDatos);
        } else {
            return json_encode(array(1 => array(lang('search_not_found'))));
        }
    }

    function get_json_tipos_productos($year, $id_marca, $id_modelo, $id_tipo_motor, $linea = null)
    {

        if ($linea != null) {
            $this->db_search->where('p.linea', $linea);
        }

        $this->db_search->select('tp.id_tipo_producto AS id,tp.nombre_en AS nombre_en,tp.nombre_es AS nombre_es');
        $this->db_search->distinct();
        $this->db_search->from('tipos_productos AS tp');
        $this->db_search->join('productos AS p', 'p.id_tipo_producto = tp.id_tipo_producto');
        $this->db_search->join('aplicaciones_productos AS app', 'app.id_producto = p.id_producto');
        $this->db_search->where('tp.estatus', 1);
        $this->db_search->like('app.years', $year);
        $this->db_search->where('app.id_marca', $id_marca);
        $this->db_search->where('app.ref_id_modelo', $id_modelo);
        $this->db_search->where('app.id_tipo_motor', $id_tipo_motor);
        $this->db_search->order_by('nombre_en', 'ASC');
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $arrDatos[] = array('0', lang('selected_product_type'));
            foreach ($query->result() as $row) {
                $nombre = $row->nombre_en . (!empty($row->nombre_es) ? ' / ' . $row->nombre_es : '');
                $arrDatos[] = array(
                    (int)$row->id,
                    htmlspecialchars($nombre, ENT_QUOTES)
                );
            }
            $query->free_result();
            return json_encode($arrDatos);
        } else {
            return json_encode(array(1 => array(lang('search_not_found'))));
        }
    }

    # --------------------------------------------------------------------------
    # search user control
    #---------------------------------------------------------------------------
    function set_field($campo, $valor)
    {
        if ($this->if_exists_field($campo)) {
            $this->db_search->where('campo', $campo);
            $this->db_search->where('hash', $this->get_hash());
            $this->db_search->update('search', array('valor' => $valor));
        } else {
            $this->db_search->insert('search', array(
                'campo' => $campo,
                'valor' => $valor,
                'hash' => $this->get_hash()
            ));
        }

        $this->delete_fields();
    }

    function if_exists_field($campo)
    {
        $this->db_search->select('valor')->from('search')
            ->where('campo', $campo)
            ->where('hash', $this->get_hash());
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_field($campo)
    {
        $this->db_search->select('valor')->from('search')
            ->where('campo', $campo)
            ->where('hash', $this->get_hash());
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['valor'];
        } else {
            return FALSE;
        }
    }

    function get_hash()
    {
        return md5($this->input->ip_address());
    }

    function delete_fields()
    {
        $this->db_search->where('hash', $this->get_hash());
        $this->db_search->where('WEEK(fecha_registro) <', 'WEEK(curdate())');
        $this->db_search->delete('search');
    }

    /*
        function get_json_oemnuevo_custom($q,$return_array=FALSE){
            $this->db_search->select('tp.nombre_en,tp.nombre_es')
                ->from('productos_oem AS oem')
                ->order_by('oem.nombre','ASC')
                ->where('oem.nombre',$q);
            $this->db_search->limit(300);
            $query = $this->db_search->get();
            if ($query->num_rows()>0){
                $row    = $query->row_array();
                $lang   = get_lang();
                if($lang=='en'){
                    $arrResult[] =$row['nombre_en'];
                }elseif($lang=='es'){
                    $arrResult[] =$row['nombre_es'];
                }

                if($return_array){
                    return $arrResult;
                }else{
                    return json_encode($arrResult);
                }
            }else{
                return FALSE;
            }
        }*/
    function get_json_smp_custom($q, $return_array = FALSE)
    {
        $this->db_search->select('tp.nombre_en,tp.nombre_es')
            ->from('productos AS p')
            ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto')
            ->order_by('p.smp', 'ASC')
            ->where('p.smp', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $lang = get_lang();
            if ($lang == 'en') {
                $arrResult[] = $row['nombre_en'];
            } elseif ($lang == 'es') {
                $arrResult[] = $row['nombre_es'];
            }

            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }


    }

    function get_json_oem_custom($q, $return_array = FALSE)
    {
        $this->db_search->select('tp.nombre_en,tp.nombre_es')
            ->from('productos AS p')
            ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto')
            ->order_by('p.oem', 'ASC')
            ->where('p.oem', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $lang = get_lang();
            if ($lang == 'en') {
                $arrResult[] = $row['nombre_en'];
            } elseif ($lang == 'es') {
                $arrResult[] = $row['nombre_es'];
            }

            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }
    }

    function get_json_wells_custom($q, $return_array = FALSE)
    {
        $this->db_search->select('tp.nombre_en,tp.nombre_es')
            ->from('productos AS p')
            ->join('tipos_productos AS tp', 'tp.id_tipo_producto=p.id_tipo_producto')
            ->order_by('p.wells', 'ASC')
            ->where('p.wells', $q);
        $this->db_search->limit(300);
        $query = $this->db_search->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $lang = get_lang();
            if ($lang == 'en') {
                $arrResult[] = $row['nombre_en'];
            } elseif ($lang == 'es') {
                $arrResult[] = $row['nombre_es'];
            }

            if ($return_array) {
                return $arrResult;
            } else {
                return json_encode($arrResult);
            }
        } else {
            return FALSE;
        }


    }

    function get_json_product_type_name_by_custo($q)
    {
        $arrDatos = array();
        $arrOE = array();
        $arrSMP = array();
        $arrWells = array();

        # busca en oe
        $arrOE = $this->get_json_oem_custom($q, TRUE);
        if (is_array($arrOE)) {
            $arrDatos = array_merge($arrDatos, $arrOE);
        }
        /*$arrOE = $this->get_json_oemnuevo_custom($q,TRUE);
        if(is_array($arrOE)){
            $arrDatos = array_merge($arrDatos,$arrOE);
        }*/

        # busca en smp
        $arrSMP = $this->get_json_smp_custom($q, TRUE);
        if (is_array($arrSMP)) {
            $arrDatos = array_merge($arrDatos, $arrSMP);
        }

        # busca en wells
        $arrWells = $this->get_json_wells_custom($q, TRUE);
        if (is_array($arrWells)) {
            $arrDatos = array_merge($arrDatos, $arrWells);
        }

        if (count($arrDatos) > 0) {
            return json_encode($arrDatos);
        } else {
            return FALSE;
        }
    }

    # --------------------------------------------------------------------------
    # admin search caracter
    #---------------------------------------------------------------------------

    function caracter($cadena)
    {

        $cadena = str_replace(" ", "", $cadena);
        $cadena = str_replace("-", "", $cadena);
        $cadena = str_replace("_", "", $cadena);
        $cadena = str_replace("/", "", $cadena);
        return $cadena;
    }
}
