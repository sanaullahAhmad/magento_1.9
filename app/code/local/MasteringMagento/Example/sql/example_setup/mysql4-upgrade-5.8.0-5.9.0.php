<?php
//die('running upgrade');
$this->startSetup();
$catalogInstaller = new Mage_Catalog_Model_Resource_Setup($this->_resourceName);
$additionalTable = $catalogInstaller->getEntityType('catalog_product', 'additional_attribute_table');

$this->run("
    UPDATE `{$this->getTable($additionalTable)}` 
    SET apply_to = CONCAT_WS(',', 'apply_to', 'event')
    WHERE apply_to LIKE '%simple%' AND apply_to NOT LIKE '%event%';
");

 $catalogInstaller->addAttribute('catalog_product', 'event_title', array(
 	'label'		=> 'Event Title',
 	'required' 	=> TRUE,
 	'group' 	=> 'Event Settings',
 	'apply_to' 	=> 'event'
 ));

 $catalogInstaller->addAttribute('catalog_product', 'event_id', array(
 	'label'		=> 'Event ID',
 	'required' 	=> TRUE,
 	'group' 	=> 'Event Settings',
 	'input' 	=> 'select',
 	'source' 	=> 'example/product_attribute_source_event',
 	'apply_to' 	=> 'event',
 	'note' 		=> 'Select your event from the list'
 ));

$this->endSetup();