<?php

class Medma_Reward_Model_Redemptpayment extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('reward/redemptpayment');
    }
}
