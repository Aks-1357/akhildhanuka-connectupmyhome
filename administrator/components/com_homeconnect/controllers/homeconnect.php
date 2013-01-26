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

jimport('joomla.application.component.controllerform');

/**
 * Homeconnect controller class.
 */
class HomeconnectControllerHomeconnect extends JControllerForm
{

    function __construct() {
        $this->view_list = 'homeconnects';
        parent::__construct();
    }

}