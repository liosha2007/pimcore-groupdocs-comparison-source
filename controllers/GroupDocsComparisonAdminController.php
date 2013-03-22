<?php

class GroupDocsComparison_GroupDocsComparisonAdminController extends Pimcore_Controller_Action_Admin {
	/**
	 * Return current values as json
	 */
	public function loaddataAction(){
		$this->_helper->viewRenderer->setNoRender();
		$conf = new GroupDocsComparison_GroupDocs();
		$this->_helper->json(array('configs' =>
				array(
						'id' => '5',
						'fileid' => $conf->getConfig('fileid'),
						'embedKey' => $conf->getConfig('embedKey'),
						'frameborder' => $conf->getConfig('frameborder'),
						'width' => $conf->getConfig('width'),
						'height' => $conf->getConfig('height')
				)
		));
	}

	/**
	 * Save new values
	 */
	public function savedataAction(){
		$conf = new GroupDocsComparison_GroupDocs();

		$cid = $this->_getParam("cid");
		$pkey = $this->_getParam("pkey");
		$firstfileid = $this->_getParam("firstfileid");
		$secondfileid = $this->_getParam("secondfileid");
		$frameborder = $this->_getParam("frameborder");
		$width = $this->_getParam("width");
		$height = $this->_getParam("height");
		if ($pkey != '' && $cid != '' && $firstfileid != '' && $secondfileid != '' && $frameborder != '' && $width != '' && $height != '') {
			
			// Code here
			
			$embedKey = 'embedKeyValue';
			$fileid = 'fileidValue';
			
			$conf->setConfig(array( 'fileid' => $fileid, 'embedKey' => $embedKey, 'frameborder' => $frameborder, 'width' => $width, 'height' => $height ));
			$this->getResponse()->setHttpResponseCode(200);
		}
		else {
			$this->getResponse()->setHttpResponseCode(500);
			$this->view->message = 'Save error';
		}
		$this->_helper->viewRenderer->setNoRender();
	}
}