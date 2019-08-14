<?php

class Medma_Reward_Model_Mysql4_Reward_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reward/reward');
    }
// 	public function addFieldToSort($attribute, $dir='asc')
//     {
// 	if (!is_string($attribute)) {
// 	return $this;
// 	}
// 	$this->setOrder($attribute, $dir);
// 	return $this;
//     }
}
