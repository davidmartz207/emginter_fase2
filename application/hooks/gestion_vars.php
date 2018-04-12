<?php
class Gestion_vars{
    function keep_vars ($vars = array()){
        if (empty($vars) or !is_array($vars)) return;

        global $pre_filter;
        $pre_filter = array();

        foreach ($vars as $var) {
            $pre_filter = array_merge($pre_filter, $var);
        }
    }
}
?>