<!-- load background home -->
<div class="backdefault">
     <div class="container">
          <div class="row">
               <div class="col-lg-4" id="logointer">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>includes/images/img/logo-soulseekrs-inter.png"></a>
               </div>
               <?php $this->load->view('includes/menu'); ?>
          </div>
          <div class="row" id="cuenta-perfiles">
               <div class="col-lg-12 text-center">
                    <h3><strong>Página no Encontrada</strong></h3>                 
               </div>
          </div>
         <div class="row text-center">
             <p><p>
               <div class="col-lg-12">
                   <form class="navbar-form" role="search" method="post" action="<?php echo site_url('buscar_perfiles');?>">
               <div class="form-group">
                    <input type="text" class="form-control" name="txtSearch" placeholder="Nombre de la Persona" size="40">
               </div>
                    <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-search"></span> Buscar</button>
               </form>
               </div>
          </div>
          <div class="row" style="height:100px;">
              
          </div>
     </div>
</div>
<!-- end background home -->