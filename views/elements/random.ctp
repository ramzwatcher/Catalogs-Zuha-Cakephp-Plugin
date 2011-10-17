<?php
/**
 * Random Catalog Item Element
 *
 * Displays a random catalog item image and information.
 *
 * PHP versions 5
 *
 * Zuha(tm) : Business Management Applications (http://zuha.com)
 * Copyright 2009-2010, Zuha Foundation Inc. (http://zuhafoundation.org)
 *
 * Licensed under GPL v3 License
 * Must retain the above copyright notice and release modifications publicly.
 *
 * @copyright     Copyright 2009-2010, Zuha Foundation Inc. (http://zuha.com)
 * @link          http://zuha.com Zuha™ Project
 * @package       zuha
 * @subpackage    zuha.app.plugins.catalogs.views.elements
 * @since         Zuha(tm) v 0.0.1
 * @license       GPL v3 License (http://www.gnu.org/licenses/gpl.html) and Future Versions
 */
?>
<?php 
// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
	if(!empty($instance) && defined('__ELEMENT_CATALOGS_RANDOM_'.$instance)) {
		extract(unserialize(constant('__ELEMENT_CATALOGS_RANDOM_'.$instance)));
	}
	
// set up the default config vars
	$itemCount = (!empty($itemCount) ? $itemCount : 3);
	$showGalleryThumb = (!empty($showGalleryThumb) ? $showGalleryThumb : false);
	$galleryThumbSize = ($showGalleryThumb === 1 ? 'small' : $showGalleryThumb);
	$showItemName = (!empty($showItemName) ? $showItemName : true);
	$showItemDescription = (!empty($showItemDescription) ? $showItemDescription : true); 
	$showViewLink = (!empty($showViewLink) ? $showViewLink : true); 
	$viewLinkText = (!empty($viewLinkText) ? $viewLinkText : 'View'); 
	$before = (!empty($before) ? '<p>'.$before.'</p>' : false); 
	$inbetween = (!empty($inbetween) ? '<p>'.$inbetween.'</p>' : false); 
	$after = (!empty($after) ? '<p>'.$after.'</p>' : false); 
	
?>

<?php 
	$catalogItems = $this->requestAction(array('plugin' => 'catalogs', 'controller' => 'catalog_items', 'action' => 'random_product'), array('pass' => array($itemCount)));
	if (!empty($catalogItems)) : 									   
?>
<ul class="elementCatalogRandom" id="elementCatalogRandom<?php echo $instance; ?>">
<?php foreach ($catalogItems as $catalogItem) : ?>
  <li>
    <div class="img"><?php echo $this->element('thumb', array('plugin' => 'galleries', 'model' => 'CatalogItem', 'foreignKey' => $catalogItem['CatalogItem']['id'], 'thumbSize' => $galleryThumbSize, 'thumbLink' => '/catalogs/catalog_items/view/'.$catalogItem['CatalogItem']['id']));  ?></div>
    <div class="txt">
	  <?php echo $before; ?>
      <h3><?php echo $this->Html->link($catalogItem['CatalogItem']['name'] , array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action'=>'view' , $catalogItem["CatalogItem"]["id"])); ?></h3>
	  <?php echo $inbetween; ?>
      <div class="more-holder"><?php echo $this->Html->link($viewLinkText , array('plugin' => 'catalogs', 'controller' => 'catalog_items' , 'action'=>'view' , $catalogItem["CatalogItem"]["id"])); ?></div>
	  <?php echo $after; ?>
    </div>
    <!-- /txt end -->
  <?php endforeach; ?>
</ul>
<?php endif; ?>