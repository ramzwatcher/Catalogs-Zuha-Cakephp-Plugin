<?php
/**
 * Products Admin Add View
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2012, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2012, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha� Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.products.views
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
 ?>
<div class="productAdd form">
	<?php
	echo $this->Form->create('Product', array('type' => 'file'));
	?>
    <h2><?php echo __('Add a Product'); ?></h2>
    <fieldset>
    	<?php
		echo $this->Form->input('Product.is_public', array('default' => 1, 'type' => 'hidden'));
		echo $this->Form->input('Product.name', array('label' => 'Item display name'));
		echo $this->Form->input('Product.sku');
		echo $this->Form->input('Product.store_id', array('label' => 'Which store should hold this item? ('.$this->Html->link('add', array('controller' => 'product_stores', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_stores', 'action' => 'index')).' stores)'));

		echo $this->Form->input('Product.product_brand_id', array('empty' => '-- Select --', 'label' => 'What is this item\'s brand name? ('.$this->Html->link('add', array('controller' => 'product_brands', 'action' => 'add')).' / '.$this->Html->link('edit', array('controller' => 'product_brands', 'action' => 'index')).' brands)'));
		echo $this->Form->input('Product.price', array('label' => 'What is the retail price?'));
		echo $this->Form->input('Product.stock', array('label' => 'Would you like to track inventory?', 'after' => '<p>Enter your current item count or leave blank for unlimited</p>'));
		echo $this->Form->input('Product.cart_min', array('label' => 'Minimun Cart Quantity?', 'after' => '<p>Enter the minimum cart quantity or leave blank for 1</p>'));
		echo $this->Form->input('Product.cart_max', array('label' => 'Maximum Cart Quantity?', 'after' => '<p>Enter the max cart quantity or leave blank for unlimited</p>'));
        echo CakePlugin::loaded('Media') ? $this->Element('Media.selector', array('multiple' => true)) : null;
		echo $this->Form->input('Product.summary', array('type' => 'text', 'label' => 'Promo or Summary Text', 'after' => '<p>Used to entice people to view more about this item.</p>'));
		echo $this->Form->input('Product.description', array('type' => 'richtext', 'label' => 'What is the sales copy for this item?'));
		echo $this->Form->input('Product.video_url', array('type' => 'text', 'label' => 'Is there a video for this product?', 'after' => '(may not be used in all themes)', 'placeholder'=>'http://www.youtube.com/watch?v=aM94aorYVS4'));
		?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you offer shipping for this item?');?></legend>
    	<?php
		$fedexSettings = defined('__ORDERS_FEDEX') ? unserialize(__ORDERS_FEDEX) : null;
		$radioOptions = array();
		if (!empty($fedexSettings)) : foreach($fedexSettings as $k => $val) :
			$radioOptions[$k] = $val ;
			echo $this->Form->input('Product.weight', array('label' => 'Weight (lbs)'));
			echo $this->Form->input('Product.height', array('label' => 'Height (8-70 inches)'));
			echo $this->Form->input('Product.width', array('label' => 'Width (50-119 inches)'));
			echo $this->Form->input('Product.length', array('label' => 'Length (50-119 inches)'));
		endforeach; endif;
		$radioOptions += array('FIXEDSHIPPING' => 'FIX SHIPPING', 'FREESHIPPING' => 'FREE SHIPPING') ;
		echo $this->Form->radio('Product.shipping_type', $radioOptions, array('class' => 'shipping_type' , 'default' => ''));
	 	?>
	 	<div id='ShippingPrice'>
	 		<?php echo $this->Form->input('Product.shipping_charge');?>
		</div>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you want to limit geographic availability of this item?');?></legend>
    	<?php
		echo $this->Form->input('Location.available', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Available (comma separated)'));
		echo $this->Form->input('Location.restricted', array('rows'=>1, 'cols' => 30,'label' => 'Zip Codes Restricted (comma separated)'));
		echo $this->Form->hidden('Location.model', array('value' => Inflector::camelize(Inflector::singularize($this->name))));
		?>
    </fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Does this item have a schedule?');?></legend>
    	<?php
		echo $this->Form->input('Product.started', array('empty' => true));
		echo $this->Form->input('Product.ended', array('empty' => true));

		if (isset($this->request->data['ProductPrice'])) {
			foreach($this->request->data['ProductPrice'] as $index => $val) {
				echo $this->Form->hidden("ProductPrice.{$index}.id", array('value'=>$val['id']));
				echo $this->Form->hidden("ProductPrice.{$index}.price", array('value'=>$val['price']));
				echo $this->Form->hidden("ProductPrice.{$index}.product_id", array('value'=>$val['product_id']));
				echo $this->Form->hidden("ProductPrice.{$index}.user_role_id", array('value'=>$val['user_role_id']));
			}
		}
		$i = 0;
		if (!empty($this->request->data['Category'])) { foreach($this->request->data['Category'] as $value) {
			++$i;
			echo '<div id="divCategory'.$i.'">';
			echo $i . ' '. $categories[$value];
			echo $this->Html->link('Remove' , "javascript:rem('Category{$i}')", array(''));
			echo $this->Form->hidden('Category.'.$i, array('value' => $value));
			echo '</div>';
		}?>
		<h3>Options</h3>
		<?php
		if(isset($options)) {
			foreach($options as $key => $opt) {
				echo '<div style ="float:left; width: 200px; clear:none;">';
				echo '<fieldset>';
				echo '<legend>' . $opt['CategoryOption']['name'] . '</legend>';
				$sel = array();
				foreach($opt['children'] as $child) {
					$sel[$child['CategoryOption']['id']] = $child['CategoryOption']['name'];
				}
				if (!empty($sel))
					echo $this->Form->input('CategoryOption.'.$opt['CategoryOption']['id'],
						array('options'=>$sel, 'multiple'=>'checkbox', 'label'=> false, 'div'=>false,
								'type'=> $opt['CategoryOption']['type'] == 'Attribute Group' ? 'radio' : 'select'));
				echo '</fieldset>';
				echo '</div>';
			}
		} }
	?>
	</fieldset>

	<fieldset>
 		<legend class="toggleClick"><?php echo __('Does this item need to be categorized?');?></legend>
			<?php echo $this->Form->input('Category', array('multiple' => 'checkbox', 'label' => 'Which categories? ('.$this->Html->link('add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' / '.$this->Html->link('edit', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'tree')).' categoies)')); ?>
	</fieldset>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Is this a recurring billing item?');?></legend>
			<?php
				echo $this->Form->input('Product.arb_settings', array(
					'rows' => 1,
					'cols' => 30,
					'label' => 'Arb Settings (
									trialOccurrences (No Of Billing Cycles For Trial),
									totalOccurrences (Total Billing Cycles),
									interval_length (How Many Months Do You Want In A Billing Cycle),
									trialAmount (Amount If Any For Trial Period) )'
					)); ?>
	</fieldset>
	<?php
		if(!empty($paymentOptions)) { ?>
		<fieldset>
			<legend class="toggleClick"><?php echo __('Select Payment Types For The Item.');?></legend>
			<?php
				echo $this->Form->input('Product.payment_type', array('options' => $paymentOptions, 'multiple' => 'checkbox'));
			?>
		</fieldset>
	<?php
		} ?>
	<fieldset>
 		<legend class="toggleClick"><?php echo __('Do you want to create this item as virtual?');?></legend>
    	<?php
			echo $this->Form->input('Product.model', array('options' => array('Webpage' => 'Webpage'), 'empty' => true, 'value' => 'Webpage'));
			#echo $this->Form->input('Product.foreign_key', array('empty' => true));
			echo $this->Form->input('Product.is_virtual', array('checked' => true));
			echo $this->Form->input('Product.hours_expire', array('after' => 'Number of hours that this item will be available to the customer after purchase'));
		?>
    </fieldset>

    <?php
    /**
     * Begin Webpages:add()
     */
    ?>
 <div class="webpages form">
	<?php echo $this->Form->create('Webpage');?>
	<h2><?php echo __('Webpage Builder');?></h2>
	<fieldset>
	<legend class="toggleClick"><?php echo __('Search Engine Optimization');?></legend>
    <?php
		echo $this->Form->input('Alias.name', array('label' => 'SEO Url (unique)'));
		echo $this->Form->input('Alias.plugin', array('type' => 'hidden', 'value' => 'webpages'));
		echo $this->Form->input('Alias.controller', array('type' => 'hidden', 'value' => 'webpages'));
		echo $this->Form->input('Alias.action', array('type' => 'hidden', 'value' => 'view'));
		echo $this->Form->input('Webpage.title', array('label' => 'SEO Title'));
		echo $this->Form->input('Webpage.keywords', array('label' => 'SEO Keywords'));
		echo $this->Form->input('Webpage.description', array('label' => 'SEO Description'));
	?>
    </fieldset>


	<fieldset>
	<legend class="toggleClick"><?php echo __('Access Rights');?></legend>
    <?php
		echo $this->Form->input('RecordLevelAccess.UserRole', array('label' => 'User Roles', 'type' => 'select', 'multiple' => true, 'options' => $userRoles, 'between' => '<br>Site settings used by default, if you use this, only these groups will have access.'));
	?>
    </fieldset>

	<fieldset>
	<legend><?php echo __('Add Webpage');?></legend>
	<?php
		echo $this->Form->input('type', array('default' => 'page_content'));
	?>
    	<fieldset>
        <legend><?php echo __('Template Settings'); ?></legend>
    <?php
		echo $this->Form->input('is_default', array('type' => 'checkbox'));
		echo $this->Form->input('template_urls', array('after' => ' <br>One url per line. (ex. /tickets/tickets/view/*)'));
		echo $this->Form->input('user_roles', array('type' => 'select', 'options' => $userRoles, 'multiple' => 'checkbox'));
	?>
    	</fieldset>
    <?php
		echo $this->Form->input('name');
		echo $this->Form->input('Webpage.content', array('type' => 'richtext'));
	?>
	</fieldset>
</div>




	<?php
    echo $this->Form->end('Submit');
	?>

<?php
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Products',
		'items' => array(
			$this->Html->link(__('List'), array('controller' => 'products', 'action' => 'index')),
			$this->Html->link(__('Add'), array('controller' => 'products', 'action' => 'add')),
			)
		),
 	array(
		'heading' => 'Webpages',
		'items' => array(
			 $this->Html->link(__('List'), array('action' => 'index')),
			 )
		)
	)));
?>

<script type="text/javascript">

$('#addCat').click(function(e){
	e.preventDefault();
	$('#anotherCategory').show();
});

$('#priceID').click(function(e){
	e.preventDefault();
	action = '<?php echo $this->Html->url(array('plugin' => 'products',
					'controller'=>'product_prices', 'action'=>'add', 'admin'=>true))?>';
	$("#ProductAddForm").attr("action" , action);
	$("#ProductAddForm").submit();
});
function rem($id) {
	$('#div'+$id).remove();
}

$(document).ready( function(){
	if($('input.shipping_type:checked').val() == 'FIXEDSHIPPING') {
		$('#ShippingPrice').show();
	} else {
		$('#ShippingPrice').hide();
	}
});

var shipTypeValue = null;
$('input.shipping_type').click(function(e){
	shipTypeValue = ($('input.shipping_type:checked').val());
	if(shipTypeValue == 'FIXEDSHIPPING') {
		$('#ShippingPrice').show();
	} else {
		$('#ShippingPrice').hide();
	}
});

//
// begin Webpages::add()
//
$(function() {

	var webpageType = $("#WebpageType").val();
	$("#WebpageIsDefault").parent().parent().hide();
	if (webpageType == 'template' || webpageType == 'element') {
		$("#RecordLevelAccessUserRole").parent().parent().hide();
 		$("#AliasName").parent().parent().hide();
	}
	if (webpageType == 'template') {
		$("#WebpageIsDefault").parent().parent().show();
	}
	$("#WebpageType").change(function() {
		var webpageType = $("#WebpageType").val();
		if (webpageType == 'template' || webpageType == 'element') {
			  $("#RecordLevelAccessUserRole").parent().parent().hide();
			  $("#AliasName").parent().parent().hide();
		} else {
			  $("#WebpageIsDefault").parent().parent().hide();
			  $("#RecordLevelAccessUserRole").parent().parent().show();
			  $("#AliasName").parent().parent().show();
		}
		if (webpageType == 'template') {
			$("#WebpageIsDefault").parent().parent().show();
		}
		if (webpageType == 'element') {
			$("#WebpageIsDefault").parent().parent().hide();
		}
	});


	if ($("#WebpageIsDefault").is(":checked")) {
		$("#WebpageTemplateUrls").parent().hide();
	}

	$("#WebpageIsDefault").change(function() {
		if ($(this).is(":checked")) {
			$("#WebpageTemplateUrls").parent().hide();
		} else {
			$("#WebpageTemplateUrls").parent().show();
		}
	});
});
</script>


</div>