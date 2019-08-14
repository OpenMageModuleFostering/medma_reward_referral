<?php

class Medma_Reward_Model_Mysql4_Redemptpayment extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the registration_id refers to the key field in your database table.
        $this->_init('reward/redemptpayment', 'id');
    }
}
