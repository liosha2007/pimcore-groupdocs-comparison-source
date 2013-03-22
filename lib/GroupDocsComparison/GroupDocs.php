<?php
class GroupDocsComparison_GroupDocs {
	/**
	 * Table object
	 */
	protected $_config = null;

	/**
	 * GroupDocs file ID
	 */
	protected $_fileid = 0;
	
	/**
	 * GroupDocs user embd key
	 */
	protected $_embedKey = '';

	/**
	 * Html frame border
	 */
	protected $_frameborder = 0;

	/**
	 * Html frame width
	 */
	protected $_width = 0;

	/**
	 * Html frame height
	 */
	protected $_height = 0;

	/**
	 * Class constructor
	 * @param Array $config
	 */
	public function __construct($config = array()) {
		$this->_config = new GroupDocsComparison_Config();
		// Set file ID
		$this->_fileid = (empty($config['fileid'])) ? $this->getConfig('fileid') : $config['fileid'];
		// Set embed key
		$this->_embedKey = (empty($config['embedKey'])) ? $this->getConfig('embedKey') : $config['embedKey'];
		// Set frameborder
		$this->_frameborder = (empty($config['frameborder'])) ? $this->getConfig('frameborder') : $config['frameborder'];
		// Set width
		$this->_width = (empty($config['width'])) ? $this->getConfig('width') : $config['width'];
		// Set height
		$this->_height = (empty($config['height'])) ? $this->getConfig('height') : $config['height'];
	}

	public function getConfig($key = null) {
		try {
			$rows = $this->_config->fetchAll();
		} catch (Zend_Db_Exception $e) {
			Logger::error("Failed to get configuration; ".$e->getMessage());
			return null;
		}
		for ($n = 0; $n < count($rows); $n += 1) {
			if ($rows[$n]['id'] == 5) {
				return $rows[$n][$key];
			}
		}
		return null;
	}

	public function setConfig($values = array()) {
		$this->_config->update($values, 'id = 5');
	}

	/**
	 * Render html frame
	 */
	public function renderFrame() {
		return '<iframe src="http://stage-apps.groupdocs.com/document-comparison/embed/' 
				. $this->_embedKey 
				. '/' 
				. $this->_fileid 
				. '&referer=PimCore/1.0" frameborder="'
				. $this->_frameborder
				. '" width="'
				. $this->_width
				. '" height="'
				. $this->_height
				. '"></iframe>';
	}
}