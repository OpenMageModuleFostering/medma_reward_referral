<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class Medma_Reward_AccountController extends Mage_Customer_AccountController
{
    public function redemptionPostAction(){//print_r($_POST);exit;
	if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

	if ($this->getRequest()->isPost()) {
		 /** @var $customer Mage_Customer_Model_Customer */
            	$customer = $this->_getSession()->getCustomer();

		/** @var $customerForm Mage_Customer_Model_Form */
		$customerForm = Mage::getModel('customer/form');
		$customerForm->setFormCode('customer_account_redemption')
			->setEntity($customer);
		
		$model		= Mage::getModel('reward/reward');
		$curDate	= date("Y-m-d");

		$redemptAmt	= $_POST['redemption_amount'];
		$customerName	= $_POST['bank_name'];
		$account_no	= $_POST['account_no'];
		if($redemptAmt!=0 && $customerName!='' && $account_no!='')
		{
		$customerId	= Mage::getSingleton('customer/session')->getId();
		$defaultData['customer_id']	= $customerId;
		$defaultData['redemption_amount']	= $redemptAmt;
		$defaultData['bank_name']		= $bankName;
		$defaultData['claim_request']		= 'claimed';
		$defaultData['redemption_time']		= $curDate;
		$defaultData['customer_account']	= $account_no;
		$model->setData($defaultData)->setId();
		$model->save();

		$storeCurrency	= Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		$customerEmail	= Mage::getSingleton('customer/session')->getCustomer()->getEmail();

		$message = "<table align='center' cellpadding='0' cellspacing='0' border='0' style='border:1px solid #bababa;width:800px;background-color:#eeeeee'>
					<tr>
        					<td align='center'  width='100%'>
							<table align='center' cellpadding='0' cellspacing='0' border='0'  width='100%'>
                						<tr>
                    							<td colspan='3' style='padding-left: 30px;'>
                        							<table align='left' cellpadding='0' cellspacing='0' border='0' width='100%'>
											<tr><td height='15px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'> Hello Admin<b>,</b></td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>$customerName has filled his/her redemption form</td>
                          								</tr>
                          								<tr><td height='10px;'></td></tr>
											<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>Redemption Amount: $redemptAmt</td>
                          								</tr>
											<tr><td height='10px;'></td></tr>
											
											<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>Customer Account no.: $account_no</td>
                          								</tr>
											<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								
                          								<tr><td height='30px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;font-weight:bold'>Best Regards,</td>
                          								</tr>
                          								<tr><td height='5px;'></td></tr>
                          								<tr>
                               									<td align='left' style='font-size:14px;font-family:Arial;'>$customerName</td>
                          								</tr>
                      								</table>
                      							</td>
                						</tr>
                						<tr><td height='50px;'></td></tr>
            						</table>
        					</td>
    					</tr>
				</table>";
			//echo $message;exit;
		
		$subject	= 'Redemption Form Information';
		$to		= Mage::getStoreConfig('trans_email/ident_general/email');
		$customerDetails= Mage::getSingleton('customer/session')->getCustomer()->getData();
		$from_email	= $customerDetails['email'];
		
		$header ="MIME-Version: 1.0"."\r\n";
		$header.="Content-type: text/html; charset=iso-8859-1"."\r\n";
		$header.="From: <".$from_email.">"."\r\n";
		
		mail($to,$subject,$message,$header);
		//print_r($customerForm);
		$this->_getSession()
                    ->addSuccess($this->__('Redemption information has been sent to Admin.'));
		$this->_redirect('*/*/redemption');
		}
		else
		{
			$this->_getSession()
			->addError($this->__('Redemption information cound not be sent to Admin.'));
			$this->_redirect('*/*/redemption');
		}
	}
    }
    public function redemptionAction()
    {
	
	$this->loadLayout();
	$this->_initLayoutMessages('customer/session');
	$this->_initLayoutMessages('catalog/session');
	$data = $this->_getSession()->getCustomerFormData(true);
	$customer = $this->_getSession()->getCustomer();
	if (!empty($data)) {
	    $customer->addData($data);
	}
	$this->renderLayout();
    }

    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
		$referalCode	= $_POST['businesslicensepath'];
		if($referalCode!='')
		{
			$cust		= Mage::getModel('customer/customer')->getCollection()->addFieldToFilter('customeridentifier1', array($referalCode))->getData();//print_r($cust);exit;
			if(count($cust)==0)
			{
				$this->_getSession()->addError($this->__('Invalid referal code'));
				$this->_redirect('*/*/edit');
            			return;
			}
		}
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            $errors = array();
            $customerErrors = $customerForm->validateData($customerData);
            if ($customerErrors !== true) {
                $errors = array_merge($customerErrors, $errors);
            } else {
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                    if (Mage::helper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $customer->setPassword($newPass);
                            $customer->setConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }

                // Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $customer->setConfirmation(null);
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('customer/account');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    }
    public function createPostAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if ($this->getRequest()->isPost()) {
		$referalCode	= $_POST['businesslicensepath'];
		if($referalCode!='')
		{
			$cust		= Mage::getModel('customer/customer')->getCollection()->addFieldToFilter('customeridentifier1', array($referalCode))->getData();//print_r($cust);exit;
			if(count($cust)==0)
			{
				$session->addError($this->__('Invalid referal code'));
				$this->_redirect('*/*/create');
            			return;
			}
		}

            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            /* @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_create')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                /* @var $address Mage_Customer_Model_Address */
                $address = Mage::getModel('customer/address');
                /* @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('customer_register_address')
                    ->setEntity($address);

                $addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
                $addressErrors  = $addressForm->validateData($addressData);
                if ($addressErrors === true) {
                    $address->setId(null)
                        ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                        ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
                    $addressForm->compactData($addressData);
                    $customer->addAddress($address);

                    $addressErrors = $address->validate();
                    if (is_array($addressErrors)) {
                        $errors = array_merge($errors, $addressErrors);
                    }
                } else {
                    $errors = array_merge($errors, $addressErrors);
                }
            }

            try {
                $customerErrors = $customerForm->validateData($customerData);
                if ($customerErrors !== true) {
                    $errors = array_merge($customerErrors, $errors);
                } else {
                    $customerForm->compactData($customerData);
                    $customer->setPassword($this->getRequest()->getPost('password'));
                    $customer->setConfirmation($this->getRequest()->getPost('confirmation'));
                    $customerErrors = $customer->validate();
                    if (is_array($customerErrors)) {
                        $errors = array_merge($customerErrors, $errors);
                    }
                }

                $validationResult = count($errors) == 0;

                if (true === $validationResult) {
                    $customer->save();

                    Mage::dispatchEvent('customer_register_success',
                        array('account_controller' => $this, 'customer' => $customer)
                    );

                    if ($customer->isConfirmationRequired()) {
                        $customer->sendNewAccountEmail(
                            'confirmation',
                            $session->getBeforeAuthUrl(),
                            Mage::app()->getStore()->getId()
                        );
                        $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));
                        $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        return;
                    } else {
                        $session->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        $this->_redirectSuccess($url);
                        return;
                    }
                } else {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $session->addError($errorMessage);
                        }
                    } else {
                        $session->addError($this->__('Invalid customer data'));
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('customer/account/forgotpassword');
                    $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                    $session->setEscapeMessages(false);
                } else {
                    $message = $e->getMessage();
                }
                $session->addError($message);
            } catch (Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
	
        }

        $this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
    }

}
