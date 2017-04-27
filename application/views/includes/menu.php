<div id="menu-top" class="menu-superior">
	<ul>
		<li>
			<a <?php echo (get_controller()=='home') ? 'class="active reset_result"' : 'class=" reset_result"';?> href="<?php echo site_url('home'); ?>"><?php echo lang('home'); ?>
			</a>
		</li>
		<li>
			<a <?php echo (get_controller()=='about_us') ? 'class="active reset_result"' : 'class=" reset_result"';?> href="<?php echo site_url('about-us'); ?>"><?php echo lang('about_us'); ?>
			</a>
		</li>
		<li>
			<a <?php echo (get_controller()=='products') ? 'class="active reset_result"' : 'class=" reset_result"';?> href="<?php echo site_url('products'); ?>"><?php echo lang('products'); ?>
			</a>
		</li>
		<li>
			<a <?php echo (get_controller()=='catalog_products') ? 'class="active reset_result"' : 'class=" reset_result"';?> href="<?php echo site_url('catalog_products'); ?>"><?php echo lang('catalog'); ?>
			</a>
		</li>
		<li>
			<a <?php echo (get_controller()=='orders') ? 'class="active reset_result"' : 'class=" reset_result"';?> href="<?php echo site_url('orders'); ?>"><?php echo lang('orders'); ?>
			</a>
		</li>
		<li>
			<a <?php echo (get_controller()=='contact') ? 'class="active reset_result"' : 'class=" reset_result"'; ?> href="<?php echo site_url('contact'); ?>"><?php echo lang('contact'); ?>
			</a>
		</li>
	</ul>
</div>