<?php
class Gestion_sesion{
   function index(){
      $CI          = & get_instance(); 
      $controlador = get_controller();
      
      if(user_is_logged()){
        switch($controlador){
            case 'login':
            case 'pass_recovery':{
                redirect('home');
                break;
            }
            case 'my_account':
            case 'logout':{
                break;
            }
        }#end switch
        
        if(!user_is_admin() and !user_is_operador()){
            switch($controlador){
                case 'panel_inicio':#inicio
                case 'panel_config':
                case 'panel_articulos':
                case 'panel_catalogs':
                case 'panel_products':
                case 'panel_applications':
                case 'panel_galery':
                case 'panel_product_type':
                case 'panel_engine_type':
                case 'panel_manufacturer':
                case 'panel_model':
                case 'panel_usuarios':{
                    redirect('home');
                    break;
                }
            }#end switch
        }
      }# en loggged
      else{# NO logueado
        $controladores_guest = array(
            'home',
            'about_us',
            'products',
            'downloads',
            'catalog_products',
            'product',
            'contact',
            'news',
            'media',#controlador de imagenes
            'content',
            'login',
            'logout',
            'register',
            'pass_recovery'
         );
         if(!in_array($controlador,$controladores_guest)){
            redirect('login');
         }#end usuario en controlador permitido
         
      }# end if - !logged
      unset($CI);
   }#end method index
}
?>