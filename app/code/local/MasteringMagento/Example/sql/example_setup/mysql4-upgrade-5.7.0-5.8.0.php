<?php
//die('running ticket page upgrade');
$this->startSetup();
$catalogInstaller = new Mage_Catalog_Model_Resource_Setup($this->_resourceName);

$this->run("
    CREATE TABLE `{$this->getTable('example/event_ticket')}` (
      `ticket_id` int(10),
      `event_id` int(10),
      `product_id` int(10),
      `title` varchar(255),
      `price` float(12,4),
      `sort_order` int(10) DEFAULT 0,
      `created_at` datetime NOT NULL,
      `modified_at` datetime NOT NULL
    )
");
$this->run("
    ALTER TABLE `example_event_ticket`
  ADD PRIMARY KEY (`ticket_id`);
");
$this->run("
    ALTER TABLE `example_event_ticket`
  MODIFY `ticket_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;
");

$this->endSetup();