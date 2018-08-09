<?php
//die('running upgrade');
$this->startSetup();

$this->run("
    CREATE TABLE {$this->getTable('example/event')} (
      `event_id` int(10),
      `name` varchar(255) NOT NULL,
      `start` datetime NOT NULL,
      `end` datetime NOT NULL,
      `created_at` datetime NOT NULL,
      `modified_at` datetime NOT NULL
    );
");
$this->run("
    ALTER TABLE `example_event`
  ADD PRIMARY KEY (`event_id`);
");
$this->run("
    ALTER TABLE `example_event`
  MODIFY `event_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;
");
$this->endSetup();