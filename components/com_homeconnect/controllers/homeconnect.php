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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Homeconnect controller class.
 */
class HomeconnectControllerHomeconnect extends HomeconnectController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_homeconnect.edit.homeconnect.id');
		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_homeconnect.edit.homeconnect.id', $editId);

		// Get the model.
		$model = $this->getModel('Homeconnect', 'HomeconnectModel');

		// Check out the item
		if ($editId) {
            $model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId) {
            $model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnect&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Homeconnect', 'HomeconnectModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_homeconnect.edit.homeconnect.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_homeconnect.edit.homeconnect.id');
			$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnect&layout=edit&id='.$id, false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_homeconnect.edit.homeconnect.data', $data);

			// Redirect back to the edit screen.
			$id = (int)$app->getUserState('com_homeconnect.edit.homeconnect.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnect&layout=edit&id='.$id, false));
			return false;
		}

        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_homeconnect.edit.homeconnect.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('Item saved successfully'));
        $this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnects', false));

		// Flush the data from the session.
		$app->setUserState('com_homeconnect.edit.homeconnect.data', null);
	}

    function cancel() {
		$app = JFactory::getApplication();

		//Get the edit id (if any)
		$id = (int) $app->getUserState('com_homeconnect.edit.homeconnect.id');
        if ($id) {
            //Redirect back to details
            $app->setUserState('com_homeconnect.edit.homeconnect.id', null);
			$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnect&id='.$id, false));
        } else {
            //Redirect back to list
			$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=homeconnects', false));
        }
    }

	public function thankYou()
	{
		$model = $this->getModel('Createbundle', 'HomeconnectModel');
		$model->createcsv();
		// $model-> setSuppliers();
		$supplers = JRequest::getVar("suppliers");

		$_SESSION['suppliers'] = $supplers;

		$cumh_installer_help = JRequest::getVar("cumh_installer_help");
		$body	= JRequest::getVar("data");
		$selectedproduct = explode("#$#",$body);
		$result= $model->sendEmail();
        if($result == "success")
        {
			// $model->sendConfirmPost($selectedproduct);
			$this->setRedirect(JRoute::_('index.php?option=com_homeconnect&view=createbundle&layout=default_thank_you&Itemid=168&suppliers='.$supplers.'&cumh_installer_help='.$cumh_installer_help, false));
		}
	}
}