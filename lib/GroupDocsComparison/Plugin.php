<?php
class GroupDocsComparison_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {

	public static function needsReloadAfterInstall() {
		return true;
	}

	public static function install() {
		Pimcore_API_Plugin_Abstract::getDb()->query("CREATE TABLE IF NOT EXISTS `plugin_groupdocs` (
			`id` INTEGER,
	        `data` varchar(512) DEFAULT '0',
			`frameborder` INTEGER DEFAULT 0,
	        `width` INTEGER DEFAULT 480,
			`height` INTEGER DEFAULT 320,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		Pimcore_API_Plugin_Abstract::getDb()->query("INSERT INTO `plugin_groupdocs` (`id`, `data`, `frameborder`, `width`, `height`) VALUES (5, '{\"cid\":\"\",\"pkey\":\"\",\"baseurl\":\"https://api.groupdocs.com/v2.0\",\"firstfileid\":\"\",\"secondfileid\":\"\",\"resfileid\":\"\",\"embedKey\":\"\"}', 0, 480, 320);");

		if (self::isInstalled()) {
			return "GroupDocs Comparison Plugin successfully installed.";
		} else {
			return "GroupDocs Comparison Plugin could not be installed.";
		}
	}

	public static function uninstall() {
		if (count(Pimcore_API_Plugin_Abstract::getDb()->query("SELECT * FROM `plugin_groupdocs`;")->fetchAll()) == 1) {
			Pimcore_API_Plugin_Abstract::getDb()->query("DROP TABLE `plugin_groupdocs`;");
		}
		else {
			Pimcore_API_Plugin_Abstract::getDb()->query("DELETE FROM `plugin_groupdocs` WHERE `id`=5;");
		}
		if (!self::isInstalled()) {
			return "GroupDocs Comparison Plugin successfully uninstalled.";
		} else {
			return "GroupDocs Comparison Plugin could not be uninstalled.";
		}
	}

	public static function isInstalled() {
		$result = null;
		try {
			$result = Pimcore_API_Plugin_Abstract::getDb()->query("SELECT * FROM `plugin_groupdocs` WHERE `id`=5;") or die ("Table 'plugin_groupdocs' don't exists!");
		} catch (Zend_Db_Statement_Exception $e) {

		}
		return (!empty($result)) && count($result->fetchAll()) == 1;
	}

	public static function getTranslationFile($language) {

	}

	public static function getInstallPath() {
		return PIMCORE_PLUGINS_PATH . "/GroupDocsComparison/install";
	}
}