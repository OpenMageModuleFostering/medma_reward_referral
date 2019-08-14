<?php 
 
/**
 * Adminhtml customer action tab
 *
 */
class Medma_Reward_Block_Adminhtml_Customer_Edit_Tab_Action extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
 
    public function __construct()
    {
        $this->setTemplate('reward/action.phtml');
 
    }
 
    public function getCustomtabInfo(){
 
        $customer = Mage::registry('current_customer');
                    return $customer->getId();
    }
 
    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Total Redemption');
    }
 
    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Total Redemption');
    }
 
    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool)$customer->getId();
    }
 
    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
 
     /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'tags';
    }
 
}
?>
