<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      akshay <akshay1357agarwal@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Homeconnect.
 */
class HomeconnectViewCreatebundle extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $params;

	// Aks : To Display Either of the Divs
	protected $userEmail;
	protected $userAddress;
	protected $recommendDisplay;
	protected $mainCategoryDisplay;
    protected $prevDiv;
    protected $data;
    //SDS to get super category array
    protected $categories;
    protected $cat_descriptionline;
    protected $recommended;
    
    /**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app                = JFactory::getApplication();
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->params       = $app->getParams('com_homeconnect');
  
		// Aks : To Display Either of the Divs
		$this->userEmail = JRequest::getVar('email');
		$this->userAddress = JRequest::getVar('geocomplete');
		$this->recommendDisplay = "display: none;";
		$this->mainCategoryDisplay = "display: none;";

		// edited by SDs for back navigation from details page prevDiv value stored in variable
		if (JRequest::getVar('bundle_type') == 'recommend')
		{
			$model =& $this->getModel();//without calling following get methos we cant get recommeneded packages as recommeneded data is merged to api/v1
			$model->getDataFromAPI($this->userAddress);
			$this->data = $model->getDataFromAPI($this->userAddress);
			$this->recommended = $model->getRecommendedPackages();
			$this->categories= $model->getLocationCategories();
			$this->prevDiv="recommended_div";
			$this->recommendDisplay = "display: block;";
		}
		else
		{
			$model =& $this->getModel();
			$this->data = $model->getDataFromAPI($this->userAddress);
			$this->recommended = $model->getRecommendedPackages();

			if (!is_array($this->data))
			{
				
			 	$app =& JFactory::getApplication();
			 	JError::raiseWarning( 100, 'Warning: Sorry Our services not availble i avialable in your area' );
				$app->redirect('index.php'); 
			}
	      
			$this->categories= $model->getLocationCategories();
			

			$this->prevDiv="confirmation_div";
			$this->mainCategoryDisplay = "display: block;";
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
            throw new Exception(implode("\n", $errors));
		}

        $this->_prepareDocument();
        
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('com_homeconnect_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
