<?php

$installer = $this;

$installer->startSetup();

$installer->run("

 DROP TABLE IF EXISTS {$this->getTable('reward/reward')};
CREATE TABLE {$this->getTable('reward/reward')}  (
  `redemptionpayment_id` int(11) unsigned NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `redemption_amount` decimal(6,3),
  `bank_name` varchar(255) NOT NULL default '',
  `bank_address` varchar(255) NOT NULL default '',
  `claim_request` varchar(50) NOT NULL default '',
  `customer_account` varchar(150) NOT NULL default '',
  `redemption_time` datetime NULL,
  PRIMARY KEY (`redemptionpayment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('reward/redemptpayment')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `redemption_amount` decimal(9,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 
