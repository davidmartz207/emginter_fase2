<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders_m extends CI_Model{
  public function __construct(){
      parent::__construct();   
  }   
 
  function registrar_pedido($arrDatos){
        //echo "<pre>",print_r($arrDatos),"</pre>";exit;
        $id_usuario = get_id_usuario();
        if(!empty($id_usuario)){
            $this->db->insert('orders',
                array(
                    'id_usuario'        => $id_usuario,
                    'po_co'             => $arrDatos['po_co'],
                    'customer_name'     => $arrDatos['customer_name'],
                    'ship_to'           => $arrDatos['ship_to'],
                    'customer_address'  => $arrDatos['customer_address'],
                    'address'           => $arrDatos['address'],
                    'customer_po_num'   => $arrDatos['customer_po_num'],
                    'sales_rep'         => $arrDatos['sales_rep'],
                    'total_pedido'      => $arrDatos['total_pedido']
            ));
            return $this->db->insert_id();
        }
   }
   
   function registrar_item($arrDatos){
        //echo "<pre>",print_r($arrDatos),"</pre>";exit;
        $id_usuario = get_id_usuario();
        $this->db->insert('items_x_order',
            array(
                'id_usuario'        => $id_usuario,
                'id_order'          => $arrDatos['id_order'],
                'cantidad'          => $arrDatos['cantidad'],
                'sku'               => $arrDatos['sku'],
                'customize'         => $arrDatos['customer'],
                'descripcion'       => $arrDatos['descripcion'],
                'unit_price'        => $arrDatos['unit_price'],
                'total_price_item'  => $arrDatos['total_price']
        ));
   }
   
   function get_orders_by_user($return_array=FALSE){
       $id_usuario  = get_id_usuario();
       if(!empty($id_usuario)){
            $arrResult              = array();
            $arrResult['id_order']  = 0;
            $query = $this->db->query("SELECT id_order,po_co,customer_name,
                                            ship_to,customer_address,address,
                                            customer_po_num,sales_rep,total_pedido
                                    FROM orders 
                                    WHERE id_usuario=? 
                                    ORDER BY fecha_registro DESC",array($id_usuario));
            if($query->num_rows() > 0) {
                $row = $query->row_array();
                $arrResult['id_order']          = $row['id_order'];
                $arrResult['po_co']             = $row['po_co'];
                $arrResult['customer_name']     = $row['customer_name'];
                $arrResult['ship_to']           = $row['ship_to'];
                $arrResult['customer_address']  = $row['customer_address'];
                $arrResult['address']           = $row['address'];
                $arrResult['customer_po_num']   = $row['customer_po_num'];
                $arrResult['sales_rep']         = $row['sales_rep'];
                $arrResult['total_pedido']      = $row['total_pedido'];
            }
            
            if(!empty($arrResult['id_order'])){
            
                # retorna un array solo con los datos d elos items de la orden
                if($return_array == FALSE){
                     return $this->db->query("SELECT cantidad AS Quantity,sku AS SKU,
                                                     customize,descripcion AS Description,
                                                     unit_price,total_price_item
                                             FROM items_x_order 
                                             WHERE id_order=? 
                                             ORDER BY fecha_registro DESC",
                                             array($arrResult['id_order']));
                }elseif($return_array == TRUE){
                    $query = $this->db->select('cantidad,sku,customize,descripcion,
                                                unit_price,total_price_item')
                                     ->from('items_x_order')
                                     ->order_by('fecha_registro','ASC')
                                     ->where('id_usuario',$id_usuario)
                                     ->where('id_order',$arrResult['id_order'])
                                     ->get();
                     if ($query->num_rows()>0){
                         foreach ($query->result() as $row){
                             $arrResult['arrProductos'][] = array(
                                 'cantidad'         => (int)$row->cantidad,
                                 'sku'              => html_escape($row->sku),
                                 'customer'         => html_escape($row->customize),
                                 'descripcion'      => html_escape($row->descripcion),
                                 'unit_price'       => html_escape($row->unit_price),
                                 'total_price'      => html_escape($row->total_price_item)
                             );
                         }
                         return $arrResult;
                     }else{
                         return FALSE;
                     }
                }
            }else{
                return FALSE;
            }
        }
   }
   
   function delete(){
       $id_usuario = get_id_usuario();
       if(!empty($id_usuario)){
            // items por pedidos
            $this->db->where('id_usuario',$id_usuario);
            $this->db->delete('items_x_order');
            
            // pedidos
            $this->db->where('id_usuario',$id_usuario);
            $this->db->delete('orders');
       }
   }
}