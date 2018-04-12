<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class cierre_sesion{

    var $CI;
    function cierre()
    {
        $this->CI =& get_instance(); //got instance of CI

        $sesion = $this->CI->session->userdata('sesion');


        $this->CI->load->database();
        $this->CI->load->helper('url');

        if($sesion){
            $query = $this->CI->db->query("select count(*) as count from count_sessions where session_id = '".$sesion."'");

            if ($query->num_rows()>0){
                $row = $query->row_array();
                $count = $row['count'];
                if($count == 0){
                    redirect('logout');
                }
            }
        }

    }
}
?>