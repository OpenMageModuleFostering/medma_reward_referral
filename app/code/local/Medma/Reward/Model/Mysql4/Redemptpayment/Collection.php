<?php

class Medma_Reward_Model_Mysql4_Redemptpayment_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reward/redemptpayment');
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
