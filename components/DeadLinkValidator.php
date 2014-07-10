<?php

class DeadLinkValidator extends CValidator {
	
	public $type;
	public $timeout;
	
	public function validateAttribute($object, $attribute) {
		if(!$this->isEnabled()) {
			return;
		}
		try {
			$client = new Zend_Http_Client($object->$attribute, array(
				'timeout' => $this->timeout,
			));
			$response = $client->request($this->type);
		} catch(Zend_Uri_Exception $e) {
			$this->addError($object, $attribute, $e->getMessage());
			return;
		} catch( Zend_Http_Client_Exception $e) {
			$this->addError($object, $attribute, $e->getMessage());
			return;
		}
		if($response->isError()) {
			$this->addError($object, $attribute, 'Error: '.$response->getStatus().' '.$response->getMessage());
		}
	}
	
	private function isEnabled() {
		$validateDeadLinks = Yii::app()->getController()->getSpace()->getSetting('enableDeadLinkValidation', 'linklist');
		// check global settings if space setting empty
		if($validateDeadLinks == '' || $validateDeadLinks == null) {
			$validateDeadLinks = HSetting::Get('enableDeadLinkValidation', 'linklist');
		}
		// set default if global setting empty
		if($validateDeadLinks == '' || $validateDeadLinks == null) {
			$validateDeadLinks = 0;
		}
		return $validateDeadLinks > 0;
	}
}

?>