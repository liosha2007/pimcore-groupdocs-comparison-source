<?php

include_once 'GroupDocsComparison/groupdocs-php/APIClient.php';
include_once 'GroupDocsComparison/groupdocs-php/GroupDocsRequestSigner.php';
include_once 'GroupDocsComparison/groupdocs-php/MgmtApi.php';
include_once 'GroupDocsComparison/groupdocs-php/ComparisonApi.php';
include_once 'GroupDocsComparison/groupdocs-php/AsyncApi.php';

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
						'cid' => json_decode($conf->getConfig('data'), true)['cid'],
						'pkey' => json_decode($conf->getConfig('data'), true)['pkey'],
						'baseurl' => json_decode($conf->getConfig('data'), true)['baseurl'],
						'firstfileid' => json_decode($conf->getConfig('data'), true)['firstfileid'],
						'secondfileid' => json_decode($conf->getConfig('data'), true)['secondfileid'],
						'resfileid' => json_decode($conf->getConfig('data'), true)['resfileid'],
						'embedKey' => json_decode($conf->getConfig('data'), true)['embedKey'],
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
		$baseurl = $this->_getParam("baseurl");
		$firstfileid = $this->_getParam("firstfileid");
		$secondfileid = $this->_getParam("secondfileid");
		$frameborder = $this->_getParam("frameborder");
		$width = $this->_getParam("width");
		$height = $this->_getParam("height");
		if ($frameborder != '' && $width != '' && $height != '') {
			
			$groupDocsRequestSigner = new GroupDocsRequestSigner($pkey);
			$apiClient = new APIClient($groupDocsRequestSigner);
			// Get embed key
			$area = 'comparison';
			$mgmtApi = new MgmtApi($apiClient);
			$oldKey = $mgmtApi->GetUserEmbedKey($cid, $area);
			if ($oldKey->status != 'Ok'){
				echo 'oldKey->error_message: ' . $oldKey->error_message;
				return;
			}
			$embedKey = $oldKey->result->key->guid;
			if ($embedKey == '') {
				$newKey = $mgmtApi->GenerateKeyForUser($cid, $area);
				if ($newKey->status != 'Ok') {
					echo 'newKey->error_message: ' . $newKey->error_message;
					return;
				}
				$embedKey = $newKey->result->key->guid;
			}
				
			$comparisonApi = new ComparisonApi($apiClient);
			$compare = $comparisonApi->Compare($cid, $firstfileid, $secondfileid, '');
			if ($compare->status != 'Ok'){
				echo 'compare->error_message: ' . $compare->error_message;
				return;
			}
			$jobId = $compare->result->job_id;
			
			// Get result document id
			$asyncApi = new AsyncApi($apiClient);
			do {
				sleep(3);
				$jobInfo = $asyncApi->GetJobDocuments($cid, $jobId);
				if ($jobInfo->result->job_status == 'Postponed'){
					echo 'Job is failure!';
					return;
				}
			}				
			while ($jobInfo->result->job_status != 'Completed' && $jobInfo->result->job_status != 'Archived');

			$resfileid = $jobInfo->result->outputs[0]->guid;
			
			$conf->setConfig(array(
					'data' => json_encode(array(
							'cid' => $cid,
							'pkey' => $pkey,
							'baseurl' => $baseurl,
							'firstfileid' => $firstfileid,
							'secondfileid' => $secondfileid,
							'resfileid' => $resfileid,
							'embedKey' => $embedKey
						)
					),
					'frameborder' => $frameborder,
					'width' => $width,
					'height' => $height
				)
			);
			$this->getResponse()->setHttpResponseCode(200);
		}
		else {
			$this->getResponse()->setHttpResponseCode(500);
			$this->view->message = 'Save error';
		}
		$this->_helper->viewRenderer->setNoRender();
	}
}