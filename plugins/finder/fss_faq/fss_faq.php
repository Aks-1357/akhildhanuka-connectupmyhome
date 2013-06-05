<?php

defined('JPATH_BASE') or die;
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );

	jimport('joomla.application.component.helper');


	// Load the base adapter.
	require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

	class plgFinderFSS_FAQ extends FinderIndexerAdapter
	{
		protected $context = 'FSS_FAQ';

		protected $extension = 'com_fss';

		protected $layout = 'faq';

		protected $type_title = 'FAQ';

		protected $table = '#__fss_faq_faq';

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
			
			$item->addTaxonomy('Type', 'FAQ');
			$item->url = 'index.php?option=com_fss&view=faq&faqid=' . $item->getElement('id');
			
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
			$sql->select('a.id, a.question as title, a.question as alias, a.answer as body, a.published as pub, a.access, a.language');
			$sql->from('#__fss_faq_faq AS a');
			
			return $sql;
		}
	}

}