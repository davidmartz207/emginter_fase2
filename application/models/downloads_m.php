<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads_m extends CI_Model{

	protected $db_search;

	public function __construct(){
		parent::__construct();   
		$this->db_search = $this->load->database('emg_search', TRUE);
	}

	public function get_data_pdf($arrDatos){
		$lang = get_lang();
		$this->db->select('p.id_producto,p.oem,p.url_post,p.path_img,
			p.nombre_en AS producto_en,p.nombre_es AS producto_es,
			p.sku,p.oem,p.smp,p.wells,p.new_release,p.descripcion_en,
			p.descripcion_es,p.precio,
			tp.nombre_en AS tipo_producto_en,tp.nombre_es AS tipo_producto_es')
		->from('productos AS p')
		->join('tipos_productos AS tp','tp.id_tipo_producto=p.id_tipo_producto')
		->where('p.estatus','1');
		#filtros prodcuts
		if(isset($arrDatos['selTiposProductos']) and !empty($arrDatos['selTiposProductos'])){
			$this->db->where('p.id_tipo_producto',$arrDatos['selTiposProductos']);
		}

		#filtros app products
		$arrParam_app = array();
		if(isset($arrDatos['selTiposMotores']) and !empty($arrDatos['selTiposMotores'])){
			$arrParam_app['tipo_motor'] = $arrDatos['selTiposMotores'];
		}
		if(isset($arrDatos['selMarcas']) and !empty($arrDatos['selMarcas'])){
			$arrParam_app['marca'] = $arrDatos['selMarcas'];
		}
		if(isset($arrDatos['selModelos']) and !empty($arrDatos['selModelos'])){
			$arrParam_app['modelo'] = $arrDatos['selModelos'];
		}

		//$this->db->limit(300);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows()>0){
			$arrResult = array();
			foreach ($query->result() as $row){
				$id_producto  = (int)$row->id_producto;
				# gestion del lenguaje
				if($lang=='es'){
					$tipo_producto = html_escape($row->tipo_producto_es);
					$datos_linea   = 'Tipo: '.$row->tipo_producto_es;
					$descripcion   = $row->descripcion_es;
				}elseif($lang=='en'){
					$tipo_producto = html_escape($row->tipo_producto_en);
					$datos_linea   = 'Type: '.$row->tipo_producto_en;
					$descripcion   = $row->descripcion_en;
				}
				$arrResult[] = array(
					'id'               => $id_producto,
					'url_post'         => html_escape($row->url_post),
					'tipo_producto'    => $tipo_producto,
					'titulo'           => html_escape($row->sku),
					'path_img'         => (!empty($row->path_img) ? html_escape($id_producto.'/'.$row->path_img) : 'default.png'),
					'datos_linea'      => html_escape($datos_linea),
					'descripcion'      => html_escape($descripcion),
					'arrApplicaciones' => get_applications_by_product($arrParam_app,$id_producto)
					);
			}
			return $arrResult;
		}else{
			return FALSE;
		}
	}

	#---------------------------------------------------------------------------
	# busqueda por vehiculo
	#---------------------------------------------------------------------------
	public function get_years_by_applications(){
		$year_now = date("Y");
		$arrDatos = array('0' => lang('selected_year'));
		for($i=1927;$i<=$year_now;$i++){
			$this->db_search->select('id_aplicaciones_producto');
			$this->db_search->distinct();
			$this->db_search->from('aplicaciones_productos');
			$this->db_search->where('estatus',1);
			$this->db_search->like('years',$i);
			$this->db_search->limit(1);
			$query = $this->db_search->get();
			//echo $this->db_search->last_query();
			if($query->num_rows()>0){$arrDatos[(int)$i] = (int)$i;}
		}
		if(count($arrDatos)>0){
			return $arrDatos;
		}else{
			return FALSE;
		}
	}

	public function get_field($campo){
        $this->db_search->select('valor')->from('search')
                ->where('campo',$campo)
                ->where('hash',$this->get_hash());
        $query = $this->db_search->get();
        //exit($this->db_search->last_query());
        if ($query->num_rows()>0){
             $row = $query->row_array();
             return $row['valor'];
         }else{
             return FALSE;
         }
    }

    function get_hash(){
        return md5($this->input->ip_address());
    }
}