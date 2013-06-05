<?php

defined('JPATH_BASE') or die;
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );

	jimport('joomla.application.component.helper');

	// Load the base adapter.
	require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

	class plgFinderFSS_KB extends FinderIndexerAdapter
	{
		protected $context = 'FSS_KB';

		protected $extension = 'com_fss';

		protected $layout = 'kb';

		protected $type_title = 'KB Article';

		protected $table = '#__fss_kb_art';

		public function __construct(&$subject, $config)
		{
			parent::__construct($subject, $config);
			$this->loadLanguage();
		}

		protected function index(FinderIndexerResult $item, $format = 'html')
		{
			// Check if the extension is enabled
			if (JComponentHelper::isEnabled($this->extension) == false)
			{
				return;
			}

			$item->body = FinderIndexerHelper::prepareContent($item->getElement('body'));
			$item->summary = FinderIndexerHelper::prepareContent($item->getElement('body'));
			
			$item->addTaxonomy('Type', 'KB Article');
			$item->url = 'index.php?option=com_fss&view=kb&kbartid=' . $item->getElement('id');
			
			$item->route = $item->url;
			$item->state = $item->getElement('pub');
			$item->access = 1;

			// Get content extras.
			FinderIndexerHelper::getContentExtras($item);

			// Index the item.
			FinderIndexer::index($item);
		}

		protected function setup()
		{
			return true;
		}

		protected function getListQuery($sql = null)
		{
			$db = JFactory::getDbo();
			// Check if we can use the supplied SQL query.
			$sql = $sql instanceof JDatabaseQuery ? $sql : $db->getQuery(true);
			$sql->select('a.id, a.title as title, a.title as alias, a.body as body, a.published as pub, a.access, a.language');
			$sql->from('#__fss_kb_art AS a');
			
			return $sql;
		}
	}

}