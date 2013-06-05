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
class HomeconnectViewHomeconnects extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();

        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');
        HomeconnectHelper::addSubmenu($view);

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/homeconnect.php';

		$state	= $this->get('State');
		$canDo	= HomeconnectHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_HOMECONNECT_TITLE_HOMECONNECTS'), 'homeconnects.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/homeconnect';
		if (file_exists($formPath))
		{
			// Aks : No Need of New
            if ($canDo->get('core.create'))
            {
			    JToolBarHelper::addNew('homeconnect.add','JTOOLBAR_NEW');
		    }
		    

		    if ($canDo->get('core.edit') && isset($this->items[0]))
		    {
			    JToolBarHelper::editList('homeconnect.edit','JTOOLBAR_EDIT');
		    }
        }

		// Aks : No Need Of Following Buttons
        if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('homeconnects.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('homeconnects.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			else if (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'homeconnects.delete','JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('homeconnects.archive','JTOOLBAR_ARCHIVE');
			}
			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('homeconnects.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'homeconnects.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			else if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('homeconnects.trash','JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_homeconnect');
		}
		
	}
}