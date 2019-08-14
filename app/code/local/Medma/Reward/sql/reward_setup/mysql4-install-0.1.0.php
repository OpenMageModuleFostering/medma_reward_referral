<?php

$installer = $this;

$installer->startSetup();
$setup = Mage::getModel('customer/entity_setup', 'core_setup');

$setup->addAttribute('customer', 'discountcode1', array(
    'type' => 'text',
    'input' => 'text',
    'label' => 'Discount Code',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'default' => '0',
    'visible_on_front' => 1,
        'source'=> 'profile/entity_discountcode1',
));
if (version_compare(Mage::getVersion(), '1.6.0', '<='))
{
      $customer = Mage::getModel('customer/customer');
      $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
      $setup->addAttributeToSet('customer', $attrSetId, 'General', 'discountcode1');
}
if (version_compare(Mage::getVersion(), '1.4.2', '>='))
{
    Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'discountcode1')
    ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
    ->save();
}
$installer->run("



    ");

$installer->endSetup(); 