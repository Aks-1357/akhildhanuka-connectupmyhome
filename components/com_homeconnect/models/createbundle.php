<?php
/**
 * @version     1.0.0
 * @package     com_homeconnect
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      akshay <akshay1357agarwal@gmail.com> - http://
 */

// No direct access.

//values defined for Product details i.e Results body seperated by comma and stored in array and details at following position

defined('_JEXEC') or die;
define("ID",0);
define("PTYPE",1);
define("SUPPLIER",2);
define("CLASS",3);
define("TAXONOMY",4);
define("PDETAIL",5);
define("PNAME",6);
define("RRP",7);
define("PRICE",8);
define("CONTRACT",9);
define("MINSPEND",10);
define("CONDITIONS",11);
define("EQUIPMENT",12);
define("EQUIPNAME",13);
define("EQUIPPRICE",14);

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Homeconnect model.
 */
class HomeconnectModelCreatebundle extends JModelForm
{
	var $_item = null;
    var $categories;
    var $recommendation;
    protected $supplier = array();
    protected $selectedDataArray =array();
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
    
	protected function populateState()
	{
		try
		{
			$app = JFactory::getApplication('com_homeconnect');
	
			// Load state from the request userState on edit or from the passed variable on default
			if (JFactory::getApplication()->input->get('layout') == 'edit')
			{
				$id = JFactory::getApplication()->getUserState('com_homeconnect.edit.homeconnect.id');
			}
			else
			{
				$id = JFactory::getApplication()->input->get('id');
				JFactory::getApplication()->setUserState('com_homeconnect.edit.homeconnect.id', $id);
			}
			$this->setState('homeconnect.id', $id);
	
			// Load the parameters.
			$params = $app->getParams();
			$this->setState('params', $params);
		}
		catch(Exception $e)
		{
			echo $e;
		}
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		return $this->_item;
	}

	public function getTable($type = 'Homeconnect', $prefix = 'HomeconnectTable', $config = array())
	{   
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
		try
		{
			// Get the id.
			$id = (!empty($id)) ? $id : (int)$this->getState('homeconnect.id');
	
			if ($id)
			{
				// Initialise the table
				$table = $this->getTable();
	
				// Attempt to check the row in.
				if (method_exists($table, 'checkin'))
				{
					if (!$table->checkin($id))
					{
						$this->setError($table->getError());
						return false;
					}
				}
			}
			return true;
		}
		catch(Exception $e)
		{
			echo $e;
		}
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		try
		{
			$id = (!empty($id)) ? $id : (int)$this->getState('homeconnect.id');
	
			if ($id)
			{
				// Initialise the table
				$table = $this->getTable();
	
				// Get the current user object.
				$user = JFactory::getUser();
	
				// Attempt to check the row out.
				if (method_exists($table, 'checkout'))
				{
					if (!$table->checkout($user->get('id'), $id))
					{
						$this->setError($table->getError());
						return false;
					}
				}
			}
			return true;
		}
		catch(Exception $e)
		{
			echo $e;
		}
	
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_homeconnect.homeconnect', 'homeconnect', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = $this->getData();
		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
		try
		{
			$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('homeconnect.id');
			$user = JFactory::getUser();
	
			if($id)
			{
				// Check the user can edit this item
				$authorised = $user->authorise('core.edit', 'homeconnect.'.$id);
			}
			else
			{
				// Check the user can create new items in this section
				$authorised = $user->authorise('core.create', 'com_homeconnect');
			}
	
			if ($authorised !== true)
			{
				JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
				return false;
			}
	
			$table = $this->getTable();
			if ($table->save($data) === true)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e)
		{
			echo $e;
		}
	}

	public function getDataFromAPI($location)
	{
		return $this->getDataFromMainAPI($location);
		//return $this->getDataFromCategoryAPI($location);
	}

	private function getDataFromMainAPI($location)
	{
			try
			{
				$data = Array();
				$Categories = Array();
				$config_object = new JConfig();
				$url = $config_object->api_url."/v1";// "http://54.241.45.56/api/v1";
				$result = json_decode($this->curlRequest($url));
	//			$d= '{"nav":[{"name":"Taxonomy","count":"138","values":[{"name":"Broadband","count":"16","values":[{"name":"Best Telecom","count":"16","values":[]},{"name":"Dodo","count":"45","values":[]},{"name":"TPG","count":"3","values":[]},{"name":"Telstra","count":"16","values":[]},{"name":"Westnet","count":"20","values":[]},{"name":"iiNet","count":"20","values":[]}]},{"name":"Tv","count":"16","values":[{"name":"Best Telecom","count":"16","values":[]},{"name":"Dodo","count":"45","values":[]},{"name":"TPG","count":"3","values":[]},{"name":"Telstra","count":"16","values":[]},{"name":"Westnet","count":"20","values":[]},{"name":"iiNet","count":"20","values":[]}]},{"name":"Gaming","count":"16","values":[{"name":"Best Telecom","count":"16","values":[]},{"name":"Dodo","count":"45","values":[]},{"name":"TPG","count":"3","values":[]},{"name":"Telstra","count":"16","values":[]},{"name":"Westnet","count":"20","values":[]},{"name":"iiNet","count":"20","values":[]}]}]},{"name":"Provider","count":"138","values":[{"name":"Dodo","count":"45","values":[]},{"name":"Best Telecom","count":"34","values":[]},{"name":"Westnet","count":"20","values":[]},{"name":"iiNet","count":"20","values":[]},{"name":"Telstra","count":"16","values":[]},{"name":"TPG","count":"3","values":[]}]}],"results":[{"Id":"74","Url":null,"Title":"ADSL Off-Net 512K 100GB","Heading":"","Advertiser":"","body":"[74, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 512K 100GB, , $39.99, 6, $299.89, Shaped to 128/64kbps once allowance is exceeded, , , , 100, 0.512, 0.512, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[74, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 512K 100GB, , $39.99, 6, $299.89, Shaped to 128/64kbps once allowance is exceeded, , , , 100, 0.512, 0.512, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"75","Url":null,"Title":"ADSL Off-Net 8M 50GB","Heading":"","Advertiser":"","body":"[75, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 50GB, , $49.99, 6, $359.89, Shaped to 128/128kbps once allowance is exceeded, , , , 50, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[75, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 50GB, , $49.99, 6, $359.89, Shaped to 128/128kbps once allowance is exceeded, , , , 50, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"76","Url":null,"Title":"ADSL Off-Net 8M 200GB","Heading":"","Advertiser":"","body":"[76, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 200GB, , $59.99, 6, $419.89, Shaped to 256/128kbps once allowance is exceeded, , , , 200, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[76, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 200GB, , $59.99, 6, $419.89, Shaped to 256/128kbps once allowance is exceeded, , , , 200, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"94","Url":null,"Title":"Home-1","Heading":"","Advertiser":"","body":"[94, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1, , $49.95, 24, $1,278.75, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 10, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[94, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1, , $49.95, 24, $1,278.75, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 10, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"95","Url":null,"Title":"Home-2","Heading":"","Advertiser":"","body":"[95, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-2, , $59.95, 24, $1,518.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[95, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-2, , $59.95, 24, $1,518.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"96","Url":null,"Title":"Home-3","Heading":"","Advertiser":"","body":"[96, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-3, , $79.95, 24, $1,998.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 100, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[96, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-3, , $79.95, 24, $1,998.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 100, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"97","Url":null,"Title":"Home-4","Heading":"","Advertiser":"","body":"[97, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-4, , $89.95, 24, $2,238.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 200, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[97, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-4, , $89.95, 24, $2,238.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 200, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"98","Url":null,"Title":"Home-5","Heading":"","Advertiser":"","body":"[98, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-5, , $119.95, 24, $2,958.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 500, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[98, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-5, , $119.95, 24, $2,958.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 500, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"166","Url":null,"Title":"Home-1 with Phone 1","Heading":"","Advertiser":"","body":"[166, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 1, , $59.90, 24, $1,477.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 17c per minute plus 39c flagfall, $2.","Teaser":"[166, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 1, , $59.90, 24, $1,477.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 17c per minute plus 39c flagfall, $2.","Classifiers":null},{"Id":"167","Url":null,"Title":"Home-1 with Phone 2","Heading":"","Advertiser":"","body":"[167, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 2, , $61.90, 24, $1,525.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 15c per minute plus 37c flagfall, $1.","Teaser":"[167, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 2, , $61.90, 24, $1,525.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 15c per minute plus 37c flagfall, $1.","Classifiers":null}],"Totalresults":138}';
	//			$d= '{"nav":[{"name":"Taxonomy","count":"1107","tips":null,"values":[{"name":"Broadband","count":"16","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTVs","values":[{"name":"BestTelecom","count":"16","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Dodo","count":"45","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"MyNetFone","count":"9","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"Optus","count":"10","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Spintel","count":"16","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"TPG","count":"17","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Telstra","count":"16","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"Vodafone","count":"37","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Westnet","count":"16","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"iPrimus","count":"75","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"iiNet","count":"16","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]}]},{"name":"Games","count":"4","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[{"name":"BigFish","count":"4","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Dell","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"DickSmith","count":"21","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"GOG","count":"9","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"GameHouse","count":"3","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"GamersGate","count":"3","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"HarveyNorman","count":"19","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"JBHi-Fi","count":"25","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Microsoft","count":"4","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"NintendoWii","count":"1","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Nintendo","count":"12","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"PlayStationGames","count":"5","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Shockwave","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Sony","count":"7","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"TelstraGameArena","count":"3","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"XboxGames","count":"4","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]}]},{"name":"MusicContent","count":"8","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[{"name":"AppleiTunes","count":"8","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"BBMMusic","count":"2","tips":"YoucanstreamyourmusicthroughyourInternetTV","values":[]},{"name":"Deezer","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"JBHiFiNow","count":"6","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"MOG","count":"2","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Rara","count":"2","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Rdio","count":"2","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"SamsungMusicHub","count":"6","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Songl","count":"2","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"SonyMusicUnlimited","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Spotify","count":"3","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"XboxMusic","count":"3","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]}]},{"name":"TVContent","count":"1","tips":"Youcanstreamquickflixthroughyourplaystation","values":[{"name":"AppleTV","count":"1","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Fetchtv-AdamInternet","count":"12","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Fetchtv-Internode","count":"14","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Fetchtv-Netspace","count":"12","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"Fetchtv-Optus","count":"7","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Fetchtv-Westnet","count":"12","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Fetchtv-iiNet","count":"12","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Foxtel-Optus","count":"20","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Foxtel-Telstra","count":"31","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Foxtel","count":"23","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Quickflix","count":"4","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"Setanta","count":"2","tips":"YoucanstreamyourmusicthroughyourInternetTV","values":[]}]}]},{"name":"Provider","count":"1107","tips":null,"values":[{"name":"iPrimus","count":"141","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Dodo","count":"117","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"iiNet","count":"114","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Westnet","count":"104","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Optus","count":"57","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Telstra","count":"55","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"BestTelecom","count":"54","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Spintel","count":"40","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Vodafone","count":"37","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"TPG","count":"32","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Foxtel-Telstra","count":"31","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"HarveyNorman","count":"29","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"MyNetFone","count":"29","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"JBHi-Fi","count":"25","tips":"YoucanstreamyourmusicthroughyourInternetTV","values":[]},{"name":"Foxtel","count":"23","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"DickSmith","count":"21","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Foxtel-Optus","count":"20","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"Fetchtv-Internode","count":"14","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Fetchtv-AdamInternet","count":"12","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Fetchtv-Netspace","count":"12","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Fetchtv-Westnet","count":"12","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Fetchtv-iiNet","count":"12","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"Nintendo","count":"12","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"GOG","count":"9","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"AppleiTunes","count":"8","tips":"YoucanstreamyourmusicthroughyourInternetTV","values":[]},{"name":"Fetchtv-Optus","count":"7","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Sony","count":"7","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"SamsungMusicHub","count":"6","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"JBHiFiNow","count":"6","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Dell","count":"5","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"PlayStationGames","count":"5","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"BigFish","count":"4","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Quickflix","count":"4","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Microsoft","count":"4","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"XboxGames","count":"4","tips":"YoucanstreamyourmusicthroughyourInternetTV","values":[]},{"name":"GameHouse","count":"3","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Spotify","count":"3","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"GamersGate","count":"3","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"XboxMusic","count":"3","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"TelstraGameArena","count":"3","tips":"Youcanconnectallyourmusictoplaythroughoutyourhouse","values":[]},{"name":"Rara","count":"2","tips":"YoucanuseyouriPadiPhoneandandroidasyourremoteforTVandmusic","values":[]},{"name":"Rdio","count":"2","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"Setanta","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Shockwave","count":"2","tips":"FoxtelontheinternetisavailablethroughsamsunginternetTV","values":[]},{"name":"Songl","count":"2","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]},{"name":"Deezer","count":"2","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"SonyMusicUnlimited","count":"2","tips":"YoucanpackageyourMogstreamingmusicservicewithTelstra","values":[]},{"name":"BBMMusic","count":"2","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"MOG","count":"2","tips":"Youcanstreamquickflixthroughyourplaystation","values":[]},{"name":"AppleTV","count":"1","tips":"IfyoubuyFoxtelyougetFoxtelonthegoonyouriPadandiPhone","values":[]},{"name":"NintendoWii","count":"1","tips":"YoucangetQuickflixthroughyourinternetTV","values":[]}]}],"results":[{"id":"1","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"Play","teaser":"[1,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,Play,StreamhundredsofblockbustermoviesandTVshowsinstantly,includingthebestfromHBO&theBBC.Directtoyourcomputer,PS3Â®,InternetconnectedTV,tabletandsmartphone.","description":"StreamhundredsofblockbustermoviesandTVshowsinstantly,includingthebestfromHBO&theBBC.Directtoyourcomputer,PS3®,InternetconnectedTV,tabletandsmartphone.Unlimitedviewing:watchwhatyoulike,wheneveryoulike.Nolock-incontracts.Nosettopbox.","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"Unlimitedvideostreaming,selectfromover55,000titlesformoviesandTVshows","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"2","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"PostUnlimited","teaser":"[2,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,PostUnlimited,DVDs&Blu-raysdeliveredstraighttoyourletterbox,twodiscsatatime.Selectfromover55,000titles.Allthelatestreleasemovies&TVshows.","description":"DVDs&Blu-raysdeliveredstraighttoyourletterbox,twodiscsatatime.Selectfromover55,000titles.Allthelatestreleasemovies&TVshows.Watchandreturninthepre-paidenvelopeandthenextsetwillbesenttoyou.Nolock-incontracts.Nolatefees.Nopostagecosts.","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"UnlimitedDVDs/Blu-rayssenttoyouforplaying","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"3","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"PostandPlay","teaser":"[3,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,PostandPlay,CombinedPlayandPostprivileges.Unlimitedstreaming,4DVDspermonth,24.99,12.99,0,,,,,,,UnlimitedStreamingand4DVDs/Blu-raystobesentforviewingpermonth,http://t0.gstatic.","description":"CombinedPlayandPostprivileges.Unlimitedstreaming,4DVDspermonth","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"UnlimitedStreamingand4DVDs/Blu-raystobesentforviewingpermonth","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"4","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"PostandPlayUnlimited","teaser":"[4,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,PostandPlayUnlimited,CombinedPlayandPostprivileges.Unlimitedstreaming,unlimitedDVDspermonth,29.99,12.99,0,,,,,,,UnlimitedStreamingandDVDs/Blu-rays,http://t0.gstatic.com/images?","description":"CombinedPlayandPostprivileges.Unlimitedstreaming,unlimitedDVDspermonth","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"UnlimitedStreamingandDVDs/Blu-rays","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"5","url":"http://www.setanta.com/au/HTS/SUBSCRIBE-ON-SETANTA-I/","thumbnail":"","category":"","classifiers":"SportsChannel","supplier":"Setanta","title":"Setanta-I","teaser":"[5,3,TVContent,Setanta,SportsChannel,TVContent/Setanta/SportsChannel,Setanta-I,WatchonSetantaSports24/7usinganyPCorMACviainternet.,16.99,0,0,,,,,,,,http://t2.gstatic.com/images?q=tbn:ANd9GcQDyoJho8LdgQePSy6ahV0VSJaFaVc-Yrw1sVO0IZDQFUaKCBR1,,http://www.setanta.","description":"WatchonSetantaSports24/7usinganyPCorMACviainternet.","price":"","monthlyPrice":"$0.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"6","url":"http://www.setanta.com/au/HTS/SUBSCRIBE-ON-SETANTA-I/","thumbnail":"","category":"","classifiers":"SportsChannel","supplier":"Setanta","title":"Setanta-I","teaser":"[6,3,TVContent,Setanta,SportsChannel,TVContent/Setanta/SportsChannel,Setanta-I,WatchonSetantaSports24/7usinganyPCorMACviainternet.Saveover$100forthisannualsubscriptionoption.,8.33,0,12,,,,,,,Annualsubscriptionoptiontosaveover$100,http://t2.gstatic.","description":"WatchonSetantaSports24/7usinganyPCorMACviainternet.Saveover$100forthisannualsubscriptionoption.","price":"","monthlyPrice":"$0.0","contractLength":"12months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"Annualsubscriptionoptiontosaveover$100","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"7","url":"https://www.foxtel.com.au/shop/packages-and-deals/?execution=e1s1","thumbnail":"","category":"","classifiers":"TVChannels","supplier":"Foxtel","title":"Platinum","teaser":"[7,3,TVContent,Foxtel,TVChannels,TVContent/Foxtel/TVChannels,Platinum,WatchTVchannelsviayourinternetconnection.Allchannelpackagesavailable,110,75,12,,FoxteliQ/MyStar,,,,,OngoingpromotoendonApril292013,http://t2.gstatic.com/images?","description":"WatchTVchannelsviayourinternetconnection.Allchannelpackagesavailable","price":"","monthlyPrice":"$75.0","contractLength":"12months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"FoxteliQ/MyStar","equipmentCost":"","conditions":"OngoingpromotoendonApril292013","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"8","url":"https://www.foxtel.com.au/shop/packages-and-deals/?execution=e1s1","thumbnail":"","category":"","classifiers":"TVChannels","supplier":"Foxtel","title":"Sports&Entertainment","teaser":"[8,3,TVContent,Foxtel,TVChannels,TVContent/Foxtel/TVChannels,Sports&Entertainment,WatchTVchannelsviayourinternetconnection.","description":"WatchTVchannelsviayourinternetconnection.IncludestheEssentialsPackage,SportsPackage,andthe3packageoptionsfortheEntertainmentPackage","price":"","monthlyPrice":"$75.0","contractLength":"12months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"FoxteliQ/MyStar","equipmentCost":"","conditions":"OngoingpromotoendonApril292013","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"9","url":"https://www.foxtel.com.au/shop/packages-and-deals/?execution=e1s1","thumbnail":"","category":"","classifiers":"TVChannels","supplier":"Foxtel","title":"Sport","teaser":"[9,3,TVContent,Foxtel,TVChannels,TVContent/Foxtel/TVChannels,Sport,WatchTVchannelsviayourinternetconnection.IncludesstandardEssentialsandSportsPacks,72,75,12,,FoxteliQ/MyStar,,,,,,http://t2.gstatic.com/images?","description":"WatchTVchannelsviayourinternetconnection.IncludesstandardEssentialsandSportsPacks","price":"","monthlyPrice":"$75.0","contractLength":"12months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"FoxteliQ/MyStar","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},{"id":"10","url":"https://www.foxtel.com.au/shop/packages-and-deals/?execution=e1s1","thumbnail":"","category":"","classifiers":"TVChannels","supplier":"Foxtel","title":"Essentials","teaser":"gstatic.com/images?q=tbn:ANd9GcQjsX-SyVzzbBBNCHmWocMO7hUNFd6qdFDbG8MpTMXBEySay8aj,,https://www.foxtel.com.au/shop/packages-and-deals/?","description":"WatchTVchannelsviayourinternetconnection.IncludesanumberofchannelsforEntertainment,Documentary,Lifestyle,News&Weather,Sports,NewsandRacing,Drama&ClassicMovies,Music,Kids,andlotsmore","price":"","monthlyPrice":"$75.0","contractLength":"12months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"FoxteliQ/MyStar","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""}],"totalResults":1107,"recommendations":[{"description":"Lowestpricebundle","broadband":{"id":"1","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"Play","teaser":"[1,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,Play,StreamhundredsofblockbustermoviesandTVshowsinstantly,includingthebestfromHBO&theBBC.Directtoyourcomputer,PS3Â®,InternetconnectedTV,tabletandsmartphone.","description":"StreamhundredsofblockbustermoviesandTVshowsinstantly,includingthebestfromHBO&theBBC.Directtoyourcomputer,PS3®,InternetconnectedTV,tabletandsmartphone.Unlimitedviewing:watchwhatyoulike,wheneveryoulike.Nolock-incontracts.Nosettopbox.","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"Unlimitedvideostreaming,selectfromover55,000titlesformoviesandTVshows","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"music":{"id":"191","url":"http://www.xbox.com/en-AU/Xbox360/Consoles?xr=shellnav","thumbnail":"","category":"","classifiers":"Consoles","supplier":"Microsoft","title":"Xbox3604GBConsolewithKinect","teaser":",,299,,,,,,,,,http://www.xbox.com/en-AU/Xbox360/Consoles?xr=shellnav,http://t2.gstatic.com/images?q=tbn:ANd9GcSMyyQTqRcQdzquGKYjlcC0YkFGPKzz6MRaG6-K_3docM2pNvVe,http://www.xbox.com/en-AU/Xbox360/Consoles?","description":"ThenewXbox3604GBconsolewithKinecthasbuilt-inwi-fi,ablackwirelesscontroller,astandarddefinitioncompositeA/Vcable,aKinectSensor,theKinectAdventuresgame,andevencomeswithafreeone-monthXboxLIVEGoldMembership.","price":"","monthlyPrice":"$299.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"tV":{"id":"328","url":"http://personal.optus.com.au/web/ocaportal.portal?_nfpb=true&_pageLabel=Template_woRHS&FP=/personal/internet/NBN&site=personal","thumbnail":"","category":"","classifiers":"NBNFibre","supplier":"Optus","title":"40GBBroadbandwithMobileplan","teaser":"[328,1,Broadband,Optus,NBNFibre,Broadband/Optus/NBNFibre,40GBBroadbandwithMobileplan,OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.","description":"OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.Plus,yougetunlimitedstandardlocalandnationalcallstofixedlines,andOptusSecuritytoprotectyourcomputerfromvirusesonallourplans.","price":"","monthlyPrice":"$79.0","contractLength":"24months","minspend":"","quota":"40GB","speed":"20mbps","equipment":"","equipmentCost":"","conditions":"Shapedto256/256kbpsonceallowanceisexceeded","support":"Mobile-Includes$180ofstandardnationalvoicecalls,SMSandMMS,standardinternationalSMS,nationalvideocalls,voicemail,nationaldiversionsandcallsto13/1300and1800numbers","homephone":null,"nationalCallcost":"","mobileCallcost":""},"gaming":{"id":"151","url":"http://bigpondmusic.com/mog","thumbnail":"","category":"","classifiers":"MusicStreaming","supplier":"MOG","title":"Basic","teaser":"[151,2,MusicContent,MOG,MusicStreaming,MusicContent/MOG/MusicStreaming,Basic,UnlimitedaccesstomusicstreamingtoyourPCorMaccomputer(viawebplayerordesktopplayer).,6.99,0,0,,,,,,,14-Dayfreetrialavailable,http://t0.gstatic.com/images?","description":"UnlimitedaccesstomusicstreamingtoyourPCorMaccomputer(viawebplayerordesktopplayer).","price":"","monthlyPrice":"$0.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"14-Dayfreetrialavailable","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""}},{"description":"Bestvaluebundle","broadband":{"id":"2","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"PostUnlimited","teaser":"[2,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,PostUnlimited,DVDs&Blu-raysdeliveredstraighttoyourletterbox,twodiscsatatime.Selectfromover55,000titles.Allthelatestreleasemovies&TVshows.","description":"DVDs&Blu-raysdeliveredstraighttoyourletterbox,twodiscsatatime.Selectfromover55,000titles.Allthelatestreleasemovies&TVshows.Watchandreturninthepre-paidenvelopeandthenextsetwillbesenttoyou.Nolock-incontracts.Nolatefees.Nopostagecosts.","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"UnlimitedDVDs/Blu-rayssenttoyouforplaying","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"music":{"id":"192","url":"http://www.xbox.com/en-AU/Xbox360/Consoles?xr=shellnav","thumbnail":"","category":"","classifiers":"Consoles","supplier":"Microsoft","title":"Xbox360250GBConsolewithKinect","teaser":"[192,4,Games,Microsoft,Consoles,Games/Microsoft/Consoles,Xbox360250GBConsolewithKinect,ThenewSpecialEditionXbox360250GBconsolewithKinectbringsgamesandentertainmenttolifeinextraordinarynewwaysâ€”nocontrollerrequired.,,399,,,,,,,,,http://www.xbox.","description":"ThenewSpecialEditionXbox360250GBconsolewithKinectbringsgamesandentertainmenttolifeinextraordinarynewways—nocontrollerrequired.","price":"","monthlyPrice":"$399.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"tV":{"id":"329","url":"http://personal.optus.com.au/web/ocaportal.portal?_nfpb=true&_pageLabel=Template_woRHS&FP=/personal/internet/NBN&site=personal","thumbnail":"","category":"","classifiers":"NBNFibre","supplier":"Optus","title":"BroadbandwithHomePhoneonNBN","teaser":"[329,1,Broadband,Optus,NBNFibre,Broadband/Optus/NBNFibre,BroadbandwithHomePhoneonNBN,OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.","description":"OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.Plus,yougetunlimitedstandardlocalandnationalcallstofixedlines,andOptusSecuritytoprotectyourcomputerfromvirusesonallourplans.","price":"","monthlyPrice":"$79.0","contractLength":"24months","minspend":"","quota":"120GB","speed":"20mbps","equipment":"","equipmentCost":"","conditions":"Shapedto256/256kbpsonceallowanceisexceeded","support":"Yes,$30monthlyworthofcreditforcallstostandardlocalandNationalcallstofixedlines,13/1300numbersandAustralianmobiles","homephone":null,"nationalCallcost":"","mobileCallcost":""},"gaming":{"id":"152","url":"http://bigpondmusic.com/mog","thumbnail":"","category":"","classifiers":"MusicStreaming","supplier":"MOG","title":"Premium","teaser":"[152,2,MusicContent,MOG,MusicStreaming,MusicContent/MOG/MusicStreaming,Premium,Downloadunlimitedmusictoyourcomputer,mobileandothermusicsystemssuchasSonosandaccessitevenwhenoffline.,11.99,0,0,,,,,,,14-Dayfreetrialavailable,http://t0.gstatic.com/images?","description":"Downloadunlimitedmusictoyourcomputer,mobileandothermusicsystemssuchasSonosandaccessitevenwhenoffline.","price":"","monthlyPrice":"$0.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"14-Dayfreetrialavailable","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""}},{"description":"Mostpopularbundle","broadband":{"id":"3","url":"http://www.quickflix.com.au/signup/stepone","thumbnail":"","category":"","classifiers":"MovieStreaming","supplier":"Quickflix","title":"PostandPlay","teaser":"[3,3,TVContent,Quickflix,MovieStreaming,TVContent/Quickflix/MovieStreaming,PostandPlay,CombinedPlayandPostprivileges.Unlimitedstreaming,4DVDspermonth,24.99,12.99,0,,,,,,,UnlimitedStreamingand4DVDs/Blu-raystobesentforviewingpermonth,http://t0.gstatic.","description":"CombinedPlayandPostprivileges.Unlimitedstreaming,4DVDspermonth","price":"","monthlyPrice":"$12.99","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"UnlimitedStreamingand4DVDs/Blu-raystobesentforviewingpermonth","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"music":{"id":"193","url":"http://www.xbox.com/en-AU/Xbox360/Consoles?xr=shellnav","thumbnail":"","category":"","classifiers":"Consoles","supplier":"Microsoft","title":"Xbox360250GBConsole","teaser":"[193,4,Games,Microsoft,Consoles,Games/Microsoft/Consoles,Xbox360250GBConsole,ThenewXbox360ishere,readyfortomorrowwithabrandnew,leanermachineinanallnewblackglossfinish.","description":"ThenewXbox360ishere,readyfortomorrowwithabrandnew,leanermachineinanallnewblackglossfinish.Wi-Fiisbuilt-inforeasierconnectiontotheworldofentertainmentonXboxLIVE,andwiththehugeharddriveyouhaveplentyofspacetostoreyourfavoritegamesandmovies.","price":"","monthlyPrice":"$299.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""},"tV":{"id":"330","url":"http://personal.optus.com.au/web/ocaportal.portal?_nfpb=true&_pageLabel=Template_woRHS&FP=/personal/internet/NBN&site=personal","thumbnail":"","category":"","classifiers":"NBNFibre","supplier":"Optus","title":"BroadbandwithHomePhoneonNBN","teaser":"[330,1,Broadband,Optus,NBNFibre,Broadband/Optus/NBNFibre,BroadbandwithHomePhoneonNBN,OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.","description":"OptusHomeBroadbandandPhonebundleshavesomegreatfeatures:freeconnection,freedeliverywhenyousignuponlineandfreeWi-Fimodemfornewcustomers.Plus,yougetunlimitedstandardlocalandnationalcallstofixedlines,andOptusSecuritytoprotectyourcomputerfromvirusesonallourplans.","price":"","monthlyPrice":"$79.0","contractLength":"24months","minspend":"","quota":"120GB","speed":"25mbps","equipment":"","equipmentCost":"","conditions":"Shapedto256/256kbpsonceallowanceisexceeded","support":"Yes,$30monthlyworthofcreditforcallstostandardlocalandNationalcallstofixedlines,13/1300numbersandAustralianmobiles","homephone":null,"nationalCallcost":"","mobileCallcost":""},"gaming":{"id":"153","url":"http://www.spotify.com/au/#premium","thumbnail":"","category":"","classifiers":"MusicStreaming","supplier":"Spotify","title":"Free","teaser":"[153,2,MusicContent,Spotify,MusicStreaming,MusicContent/Spotify/MusicStreaming,Free,Musiclibraryyoucanaccessthataread-based.,0.00,0,0,,,,,,,30-DayfreetrialwithPremiumaccess,http://t1.gstatic.com/images?","description":"Musiclibraryyoucanaccessthataread-based.","price":"","monthlyPrice":"$0.0","contractLength":"0months","minspend":"","quota":"0GB","speed":"0mbps","equipment":"","equipmentCost":"","conditions":"30-DayfreetrialwithPremiumaccess","support":"","homephone":null,"nationalCallcost":"","mobileCallcost":""}}]}';
				//$result = json_decode($d);	
						
			  if(!empty($result->nav[0]->values))
			  {
				foreach($result->nav[0]->values as $r)
				{
					$r->description = $this->getDescriptionLine($r->name);
					$data[] = $r;
					//var_dump($r);
					//echo '<br/>';
					//echo '<br/>';
				}
				
				$data = $this->reOrderAccordianData($data);
		
				foreach($data as $d)
				{
					array_push($Categories, JFilterOutput::stringURLSafe($d->name));
				}
				
				$this->setLocationCategories($Categories);
				
				$recommended =	Array();
				$lowprice = Array();
				$bestvalues = Array();
				$mostpopular = Array();
				foreach($result->recommendations as $package)
				{
					if((strcasecmp($package->description, "Lowest price bundle") == 0))
					   {
					   	array_push($lowprice,$package);
					   }
				 	if((strcasecmp($package->description, "Best value bundle") == 0))
					   {
					   	array_push($bestvalues,$package);
					   }
					if((strcasecmp($package->description, "Most popular bundle") == 0))
					   {
					   	  array_push($mostpopular,$package);
					   }
				}
				array_push($recommended,$lowprice);
				array_push($recommended,$bestvalues);
				array_push($recommended,$mostpopular);
				$this->setRecommendedPackages($recommended);
			  }
			  else 
			  {
			  	    $app =& JFactory::getApplication();
				 	JError::raiseWarning( 100, 'Warning: Sorry Our services not availble in your area' );
					$app->redirect('index.php'); 
			  }
				//var_dump($result->recommendations);exit;
				
			 
			  
			}
			catch(Exception $e)
			{
				echo $e;
			}
		
		return $data;
	}

	private function reOrderAccordianData($inputData)
	{
		$tvArray = array();
		$gamingArray = array();
		$musicArray = array();
		$broadbandArray = array();
		foreach($inputData as $d)
		{
			switch(strtolower($d->name))
			{
				case 'tv content' :
				$tvArray=$d;
				break;
				case 'games' :
				$gamingArray=$d;
				break;
				case 'music content' :
				$musicArray=$d;
				break;
				case 'broadband' :
				$broadbandArray=$d;
				break;
				default:
				break;
			}
		}
		
		return array($tvArray,$musicArray,$gamingArray,$broadbandArray);
	}
	
	private function getDataFromCategoryAPI($location)
	{
		try 
		{
			$data = array();
			$locationCategories = array();
	
	//		$url = "http://50.18.20.35/api/v1/?location=".$location;
	//		$locationCategories = json_decode($this->curlRequest($url));
	
			array_push($locationCategories, "nbn");
			array_push($locationCategories, "adsl");
			array_push($locationCategories, "broadband");
	//		array_push($locationCategories, "fixedline");
			$this->setLocationCategories($locationCategories);
			if(!empty($locationCategories))
			{
				foreach ($locationCategories as $c)
				{
					$data[$c] = array();
					$config_object = new JConfig();
					$url = $config_object->api_url."/v1/?category=".$c;
		
					$result = json_decode($this->curlRequest($url));
		/*			if($c=="nbn")
					{
		             $d= '{"Nav":[{"Name":"Taxonomy","count":"138","Values":[{"Name":"Broadband","count":"16","Values":[{"Name":"Best Telecom","count":"16","Values":[]},{"Name":"Dodo","Count":"45","Values":[]},{"Name":"TPG","Count":"3","Values":[]},{"Name":"Telstra","Count":"16","Values":[]},{"Name":"Westnet","Count":"20","Values":[]},{"Name":"iiNet","Count":"20","Values":[]}]},{"Name":"Tv","Count":"16","Values":[{"Name":"Best Telecom","Count":"16","Values":[]},{"Name":"Dodo","Count":"45","Values":[]},{"Name":"TPG","Count":"3","Values":[]},{"Name":"Telstra","Count":"16","Values":[]},{"Name":"Westnet","Count":"20","Values":[]},{"Name":"iiNet","Count":"20","Values":[]}]},{"Name":"Gaming","Count":"16","Values":[{"Name":"Best Telecom","Count":"16","Values":[]},{"Name":"Dodo","Count":"45","Values":[]},{"Name":"TPG","Count":"3","Values":[]},{"Name":"Telstra","Count":"16","Values":[]},{"Name":"Westnet","Count":"20","Values":[]},{"Name":"iiNet","Count":"20","Values":[]}]}]},{"Name":"Provider","Count":"138","Values":[{"Name":"Dodo","Count":"45","Values":[]},{"Name":"Best Telecom","Count":"34","Values":[]},{"Name":"Westnet","Count":"20","Values":[]},{"Name":"iiNet","Count":"20","Values":[]},{"Name":"Telstra","Count":"16","Values":[]},{"Name":"TPG","Count":"3","Values":[]}]}],"results":[{"Id":"74","Url":null,"Title":"ADSL Off-Net 512K 100GB","Heading":"","Advertiser":"","Body":"[74, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 512K 100GB, , $39.99, 6, $299.89, Shaped to 128/64kbps once allowance is exceeded, , , , 100, 0.512, 0.512, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[74, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 512K 100GB, , $39.99, 6, $299.89, Shaped to 128/64kbps once allowance is exceeded, , , , 100, 0.512, 0.512, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"75","Url":null,"Title":"ADSL Off-Net 8M 50GB","Heading":"","Advertiser":"","Body":"[75, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 50GB, , $49.99, 6, $359.89, Shaped to 128/128kbps once allowance is exceeded, , , , 50, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[75, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 50GB, , $49.99, 6, $359.89, Shaped to 128/128kbps once allowance is exceeded, , , , 50, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"76","Url":null,"Title":"ADSL Off-Net 8M 200GB","Heading":"","Advertiser":"","Body":"[76, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 200GB, , $59.99, 6, $419.89, Shaped to 256/128kbps once allowance is exceeded, , , , 200, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Teaser":"[76, Broadband, TPG, Offnet ADSL, Broadband/TPG/Offnet ADSL, , ADSL Off-Net 8M 200GB, , $59.99, 6, $419.89, Shaped to 256/128kbps once allowance is exceeded, , , , 200, 8, 0.384, $59.95, , No, NA, NA, , http://www.tpg.com.","Classifiers":null},{"Id":"94","Url":null,"Title":"Home-1","Heading":"","Advertiser":"","Body":"[94, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1, , $49.95, 24, $1,278.75, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 10, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[94, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1, , $49.95, 24, $1,278.75, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 10, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"95","Url":null,"Title":"Home-2","Heading":"","Advertiser":"","Body":"[95, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-2, , $59.95, 24, $1,518.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[95, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-2, , $59.95, 24, $1,518.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"96","Url":null,"Title":"Home-3","Heading":"","Advertiser":"","Body":"[96, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-3, , $79.95, 24, $1,998.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 100, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[96, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-3, , $79.95, 24, $1,998.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 100, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"97","Url":null,"Title":"Home-4","Heading":"","Advertiser":"","Body":"[97, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-4, , $89.95, 24, $2,238.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 200, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[97, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-4, , $89.95, 24, $2,238.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 200, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"98","Url":null,"Title":"Home-5","Heading":"","Advertiser":"","Body":"[98, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-5, , $119.95, 24, $2,958.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 500, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Teaser":"[98, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-5, , $119.95, 24, $2,958.75, Shaped to 256/256kbps once allowance is exceeded, Not Included, , , 500, 1.5, 0.256, $79.95, , Convert to bundle plans, See bundles plans, See bundles plans, Included, 24*7, http://www.iinet.net.","Classifiers":null},{"Id":"166","Url":null,"Title":"Home-1 with Phone 1","Heading":"","Advertiser":"","Body":"[166, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 1, , $59.90, 24, $1,477.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 17c per minute plus 39c flagfall, $2.","Teaser":"[166, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 1, , $59.90, 24, $1,477.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 17c per minute plus 39c flagfall, $2.","Classifiers":null},{"Id":"167","Url":null,"Title":"Home-1 with Phone 2","Heading":"","Advertiser":"","Body":"[167, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 2, , $61.90, 24, $1,525.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 15c per minute plus 37c flagfall, $1.","Teaser":"[167, Broadband, iiNet, Offnet ADSL, Broadband/iiNet/Offnet ADSL, , Home-1 with Phone 2, , $61.90, 24, $1,525.55, Shaped to 256/128kbps once allowance is exceeded, Not Included, , , 20, 1.5, 0.256, $39.95, , Yes, 15c per minute plus 37c flagfall, $1.","Classifiers":null}],"Totalresults":138}';
					 $result = json_decode($d);
					}
		*/
						foreach($result->nav[0]->values as $r)
						{
							$data[$c][0] = $c;
							$data[$c][] = $r;
						}
					}
				}
			}
		catch(Exception $e)
		{
			echo $e;
		}
		return $data;
	}

	public function getBrandsDataFromAPI($category)
	{
	  try 
	  {
	  	$app = JFactory::getApplication();
	
		$html = "";
	    $config_object = new JConfig();
	 	$url = $config_object->api_url."/v1/?category=".JFilterOutput::stringURLSafe($category);
		$result = json_decode($this->curlRequest($url));
	    $data ="";
		 if(!empty($result->nav[0]->values))
			  {	
			  	
				foreach($result->nav[0]->values as $r)
				{
					$r->description = $this->getDescriptionLine($r->name);
					$data[] = $r;
					//var_dump($r);
					//echo '<br/>';
					//echo '<br/>';
				}
				
				$data = $this->reOrderAccordianData($data);
			  }
		$i=0;
		
		$brandhtml ="";
		
		foreach ($data as $d)
		{
			$i++;
			$j=0;
				
	            if(strcmp($category, JFilterOutput::stringURLSafe($d->name) ) == 0 )
	            {	
	            		            		
						$class = "BrandDisplayRightBorder";
					    foreach($d->values as $v)
						{
							
							$tip = $v->tips;
							//echo $tip;
							//var_dump($d->values);
							//exit;
							$j++;
							if( $j % 4 == 0)
							{
								$class = "BrandDisplayRightBorder BorderNone";
							}
							if($j > 4)
							{
								$class = "BrandDisplayTopBorder";
								if( $j % 4 == 0)
								{
									$class = "BrandDisplayTopBorder BorderNone";
								}
							}

						$brandhtml .='<a href="javascript:void(0);" class="brandtip"><div onClick="javascript:controllerObj.initSwitchInnerDivs(\''.JFilterOutput::stringURLSafe('main_category_inner_div_'.$d->name).'\', \''.JFilterOutput::stringURLSafe('main_category_inner_product_div_'.$d->name.'-'.$v->name).'\','. $i.', '.$j.',\''.$d->name.'\',\''.$v->name.'\')" class=\''.$class.'\'>';
						$brandhtml .="<div class='tile'><div class='tile_inner'>";
						$brandhtml .="";
						 if (strrchr($v->name, '+'))
						       {    
						         $imag = explode("+", $v->name);
								 $img1=$imag[0];
								 $img2=$imag[1];
								 
								 $imagefile1="";
								 $imagefile2="";
								$imagefile1= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img1).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img1).'.jpg'))
							    {
									$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    if($img2)
							    {
								    $imagefile2= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img2).'.jpg';
	
									if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img2).'.jpg'))
								    {
										$imagefile2= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
								    }
								$brandhtml .= "<img src='".$imagefile1."' alt='".$img1."' class='two_imageSize'/>";
								$brandhtml .= "<img src='".$imagefile2."' alt='".$img2."' class='two_imageSize'/>"; 
							    }
						       }
						       else 
							    {
							    $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($v->name).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($v->name).'.jpg'))
							    {
									$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    	
							    $brandhtml .= "<img src='".$imagefile."' alt='". $imagefile."' class='imageSize'/>";	
							    }
							    $brandhtml .="</div></div></div>";
							    if(!empty($tip)&&isset($tip))
								{		
							      $brandhtml.="<span class='hovertitle'>".$tip."</span>";
								}
								$brandhtml.="</a>";
						}	    
							   
	            }
	           
			
		}
		
	      echo $brandhtml;
		
			
	  }
	  catch(Exception $e)
	  {
	  	echo "Error:".$e;
	  }
	
	}

	public function getProductsDataFromAPI($superCategoryName, $subCategoryName, $brandName, $next_id, $prev_id,$accordionIndex,$no_of_categories)
	{
		try
		{
			$app = JFactory::getApplication();
	
			$html = "";
	        $config_object = new JConfig();
			$url = $config_object->api_url."/v1/?category=".JFilterOutput::stringURLSafe($subCategoryName)."&supplier=".JFilterOutput::stringURLSafe($brandName);
			$result = json_decode($this->curlRequest($url));
			$last_accordion_next_button = '<div onClick="javascript:controllerObj.nextAccordionFromProductScreen(\''.$prev_id.'\', \''.$next_id.'\','."'".JFilterOutput::stringURLSafe('category_accordion_div')."'".','.$accordionIndex.','.$no_of_categories.',\''.JFilterOutput::stringURLSafe($subCategoryName).'\');" class="nextbutton">';
	        if($no_of_categories == $accordionIndex)
	         $last_accordion_next_button = '<div class="nextbutton" onclick="javascript:controllerObj.switchInnerDivs(\'main-category-div\',\'confirmation_div\',0,0);">';
			$html .= '<span class="accordianproducttitle">'.$brandName.'</span><div class="nextpreviouswrap">'.
			         $last_accordion_next_button.
			         'Next</div>'.
					 '<div onClick="javascript:controllerObj.switchInnerDivs(\''.$prev_id.'\', \''.$next_id.'\', 0, 0);" class="backbutton" style="margin-right:7px !important">'.
					 '<span>Back</span></div>'.
					 '</div>'.
			         '<div class="main_category_wrapper">';
			
			if(!empty($result->results))
			{	
				foreach ($result->results as $r)
				{
					
					$myArray = explode(',', $r->body);
					$productadded = JFilterOutput::stringURLSafe($subCategoryName).','.JFilterOutput::stringURLSafe($brandName).','.JFilterOutput::stringURLSafe($r->title);
					if (strrchr($brandName, '+'))
					{
						   
					$imag = explode("+", $brandName);
								 $img1=$imag[0];
								 $img2=$imag[1];
								 
								 $imagefile1="";
								 $imagefile2="";
								$imagefile1= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img1).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img1).'.jpg'))
							    {
									$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    if($img2)
							    {
								    $imagefile2= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img2).'.jpg';
	
									if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img2).'.jpg'))
								    {
										$imagefile2= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
								    }
							   
							
							    }
									
					$html .=	'<div class="brand_product_wrap" id="Brand_'.$r->id.'"><div class="BrandProductDetail">'.
					'<div class="chkbox" id="divchk_'.$r->id.'"><input type="checkbox" id="'.JFilterOutput::stringURLSafe('add-'.$subCategoryName.'-'.$brandName.'-'.$r->id).'" onClick="javascript:controllerObj.AddToMyBundle(\''.$productadded.','.$r->id.','.$r->contractLength.','.$r->quota.','.$r->monthlyPrice.','.$r->price.','.$r->equipmentPrice.','.$r->installationPrice.'\',\''.JURI::base().'images/basketimages/\')"  ></div>'.	
					'<div class="image"><img src="'.$imagefile1.'" class="two_imageSize"><img src="'.$imagefile2.'" class="two_imageSize"><br/><span class="title">'.$r->title.'</span></div>';
					
					}
					else
					{
					         $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($brandName).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($brandName).'.jpg'))
							    {
									$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
					$html .=	'<div class="brand_product_wrap" id="Brand_'.$r->id.'"><div class="BrandProductDetail">'.
					'<div class="chkbox" id="divchk_'.$r->id.'"><input type="checkbox" id="'.JFilterOutput::stringURLSafe('add-'.$subCategoryName.'-'.$brandName.'-'.$r->id).'" onClick="javascript:controllerObj.AddToMyBundle(\''.$productadded.','.$r->id.','.$r->contractLength.','.$r->quota.','.$r->monthlyPrice.','.$r->price.','.$r->equipmentPrice.','.$r->installationPrice.'\',\''.JURI::base().'images/basketimages/\')"  ></div>'.	
					'<div class="image"><img src="'.$imagefile.'" class="imageSize"><span class="title">'.$r->title.'</span></div>';
					
					}
//					.'<div class="category commen">'.$r->classifiers.'</div><div class="seperator"></div>';
					$flag= 0;
					if(!empty($r->monthlyPrice) && isset($r->monthlyPrice))
	                {	
	                    	
	                    	$flag++;
							$html .= '<div class="offer_div"><div class="header">Monthly Price</div>'.$r->monthlyPrice.'</div>';
//	                    	 elseif(strcmp ( $r->monthlyPrice , '$0.0' )==0 )
//	                    	 {
//	                    	 	$html .= '<div class="amount commen"> <span>'.$r->price.'</span><br/>  price per month<br/></div><div class="seperator"></div>';
//	                    	 }
//	                    	 elseif(strcmp ( $r->monthlyPrice , '$0' )!=0)
//	                    	 {
//	                    	 	$html .= '<div class="amount commen"> <span>'.$r->monthlyPrice.'</span><br/>  price per month<br/>&nbspminimum total cost <br/>'.$r->price.'</div><div class="seperator"></div>';
//	                    	 }
	                }
					if(!empty($r->price) && isset($r->price))
	                {	
	                	   $flag++;
						   $html .='<div class="offer_div"><div class="header">One-off Price</div>'.$r->price.'</div>';
	                    	
	                    	
	                }
					if(!empty($r->installationPrice) && isset($r->installationPrice))
	                {	
	                	   $flag++;
						   $html .= '<div class="offer_div"><div class="header">Installation Price</div>'.$r->installationPrice.'</div>';
	                    	
	                    	
	                }
					if(isset($r->quota) && !empty($r->quota))
					{
					   	    $flag++;
					        $html .= '<div class="offer_div"><div class="header">Quota</div>'.$r->quota.'</div>';
					}
					if(isset($r->contractLength) && !empty($r->contractLength))
					{
					   	$flag++;
						$html .='<div class="offer_div"><div class="header">Contract</div>'.$r->contractLength.'</div>';
					}
					if(isset($r->equipment) && !empty($r->equipment))
					{
					   	$flag++;
						$html .='<div class="offer_div"><div class="header">Equipment</div>'.$r->equipment.'</div>';
					}
				       $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($brandName).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($brandName).'.jpg'))
							    {
									$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
					
					
							$html .= '<div class="viewdetails" id="divViewDetails_'.$r->id.'"><a onClick="javascript:controllerObj.detailspopupwindow('.$r->id.',\''.$imagefile.'\',\'cb\')">Details</a></div>';
						
					//	'<div class="addtobundle" style="display:none" onClick="javascript:controllerObj.AddToMyBundle(\''.$productadded.','.$r->id.'\',\''.JURI::base().'/images/main_accordian/'.$brandnam.'.jpg\')" id="'.$r->id.'">Add to bundle</div>&nbsp;<div class="addtobundle" style="display:none" id="'.JFilterOutput::stringURLSafe('delete-'.$subCategoryName.'-'.$brandName.'-'.$r->id).'" style="display:none;margin-left:10px;" onclick="javascript:controllerObj.productRemove(\''.$productadded.','.$r->id.'\');">Remove from Bundle</div></div>';
					 if($flag <= 4) 
				    {
					 $html .= '</div></div>';
				    }
				}
			   
				
				 $html .= '</div>';
				 $result->html = $html;
				 echo json_encode($result);
			}
			else 
			{
				 echo "error_empty";
			}
			
	
			 // SDs for hide next and previous button on selection screen;
	/*	$html .= 	'</div><div style="position: absolute; top: 459px;"><div class="NoProducts">'.
						'<div onClick="javascript:controllerObj.switchInnerDivs(\''.$prev_id.'\', \''.$next_id.'\', 0, 0);" style="cursor: pointer; float: left;">'.
						'<img src="'.JURI::base().'templates/'.$app->getTemplate().'/images/previous.png'.'" /></div>'.
						'<div onClick="javascript:controllerObj.switchInnerDivs(\''.$prev_id.'\', \''.$next_id.'\', 0, 0);" style="cursor: pointer; float: right;">'.
						'<img src="'.JURI::base().'templates/'.$app->getTemplate().'/images/next.png'.'" /></div>'.
						'</div></div>'; */
			
		}
		catch(Exception $e)
		{
			echo $e;
		}
	}

	private function curlRequest($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		return curl_exec($ch);
	}

	// SDs setter getter for super categoris
	public function setLocationCategories( $categories)
	{
	   $this->categories = $categories;
	}

	public function getLocationCategories()
	{
		return $this->categories;
	}
	
	public function setRecommendedPackages( $packages)
	{
	   $this->recommendation = $packages;
	}

	public function getRecommendedPackages()
	{
		return $this->recommendation;
	}
	
	public function createcsv()
	{
		try 
		{
			$log = JRequest::getVar("log");
			$csv_fields = json_decode($log);
			$csv_folder = JPATH_ROOT.'/csv';
	
			$filename = JRequest::getVar("ip");
	
			$email	= explode("@", JRequest::getVar("emailid"));
			$CSVFileName = $csv_folder.'/'.$filename.'_'.$email[0].'.csv';
			$i = 1;
	
			while(file_exists($CSVFileName))
			{
				$CSVFileName=$csv_folder.'/'.$filename.'_'.$email[0].'('.$i.').csv';
				$i++;
			}
	
			// $TextFileName = $csv_folder.'/'.$filename.'_'.$email[0].'.text';
			$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
	
			// $FileHandle = fopen($TextFileName, 'w') or die("can't open file");
			fclose($FileHandle);
	
			$fp = fopen($CSVFileName, 'w');
			// $fp1 = fopen($TextFileName, 'w');
			//var_dump($csv_fields);exit;
			foreach ($csv_fields as $field)
			{
				fputcsv($fp, $field);
			}
	
			/* foreach ($csv_fields as $fields)
			{
				fwrite($fp1,$fields);
				fwrite($fp1,PHP_EOL);
			} */
			fclose($fp);
			// fclose($fp1);
		}
		catch(Exception $e)
		{
			echo $e;
		}
	}
	
	//SDs set supplier for thank u page
	
	public function setSuppliers()
	{
		$this->supplier = json_decode(JRequest::getVar("suppliers"));
		
	}
	
	public function getSuppliers()
	{
		return $this->supplier;
	}
   public function setSelectedData()
	{
		$body	= JRequest::getVar("data");
		$selectedData = explode("#$#",$body);
	 	foreach ($selectedData as $product)
              {
              	$row = explode(",",$product);

              	array_push($this->selectedDataArray, $row);
              	
              	
              }
              return $this->selectedDataArray;
		
		
	}

	public function sendEmail()
	{
		// Aks :
		try
		{
			$app		= JFactory::getApplication();

			$mailfrom	= $app->getCfg('mailfrom');
			$fromname	= $app->getCfg('fromname');
			$sitename	= $app->getCfg('sitename');

			$name		= 'ConnectUpMyHomeAdmin';
			$email		= JRequest::getVar("emailid");

			$subject	= JRequest::getVar("subject");
			$body		= JRequest::getVar("data");

			$selectedproduct = explode("#$#",$body);

			$custmer_name = JRequest::getVar("name");

			$price_array = explode(",", JRequest::getVar("final_prices"));
			$address_1	= JRequest::getVar("customer_address");
			$address_2	= JRequest::getVar("customer_installation_address");
			$contactNo	= JRequest::getVar("customer_number");

			if($address_1 != $address_2)
			{
				$address_2 = $address_1;
			}
			$address_arr_1 = explode("#$#", $address_1);
			$address_arr_2 = explode("#$#", $address_2);

			$address_1 = '<br>'.$address_arr_1[0].', '.$address_arr_1[1].',<br>'.$address_arr_1[2].', '.$address_arr_1[3].'<br>'.$address_arr_1[4];
		//	if(count($address_arr_1[5]) > 1)
			//{
				$address_1 .= " ".$address_arr_1[5];
			//}

			$address_2 = '<br>'.$address_arr_2[0].', '.$address_arr_2[1].',<br>'.$address_arr_2[2].', '.$address_arr_2[3].'<br>'.$address_arr_2[4];
//			if(count($address_arr_2[5]) > 1)
	//		{
				$address_2 .= " ".$address_arr_2[5];
		//	}

			$body ='<table width="700" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial,Helvetica,sans-serif;font-size:12px">'.
					'<tbody>'.
						'<tr>'.
							'<td valign="top" align="left" colspan="6"><img src="'.JURI::base().'images/logo.png"  ></td>'.
						'</tr>'.
						'<tr>'.
							'<td height="30" colspan="6">&nbsp;</td>'.
						'</tr>'.
						'<tr>'.
							'<td height="30" style="border-bottom:solid 1px #d6d6d6;padding:5px;" colspan="6">'.
							'Thank you, '.$custmer_name.', for using <a style="color:#018AB2;text-decoration:none;pointer:cursor" href="http://50.18.7.37/cumh">Connect Up My Home</a>.<br/><br/>Please see your selection below. Our partners and a Connect Up My Home installer will contact you soon to arrange the details to Connect Up Your Home. If you would like to speak to a Connect Up My Home customer service representative please call 12345678. Please email us at <a href="mailto:confirmations@connectupmyhome.com.au">confirmations@connectupmyhome.com.au</a> if any of your details below are not correct.<br>'.
							'</td>'.
						'</tr>'.
						'<tr>'.
							'<td height="30" style="border-bottom:solid 1px #d6d6d6;padding:5px;" colspan="3">'.
								'<strong>Postal Address : </strong>'.$address_1.
								'<br><br><strong>Contact No. : </strong>'.$contactNo.
							'</td>'.
							'<td height="30" style="border-bottom:solid 1px #d6d6d6;padding:5px;vertical-align: top;" colspan="3">'.
								'<strong>Installation Address : </strong>'.$address_2.
							'</td>'.
						'</tr>'.
						'<tr>'.
							'<td width="86" height="30">&nbsp;</td>'.
							'<td width="70">&nbsp;</td>'.
							'<td width="93">&nbsp;</td>'.
							'<td width="127">&nbsp;</td>'.
							'<td width="61">&nbsp;</td>'.
							'<td width="63">&nbsp;</td>'.
						'</tr>';

			$brands = array();
			$brands_wrk = array();
			$suppliers = $_SESSION['suppliers']; // JRequest::getVar('suppliers');

			$brands_wrk =	json_decode($suppliers);
			foreach ($brands_wrk as $b)
			{
				$temp = explode("::", $b);
				$brands[$temp[0]] = $temp[1]."$$$".$temp[2];
			}

			foreach ($selectedproduct as $product)
			{
				$row = explode(",",$product);
				$on_off_price ='NA';
				$final_monthly_price = $monthly_price ='NA';
				$eq_price = 0;
				$installation_price = 0;
				$onoffprice = 0;

				$product_final_name = $row[2];
				$final_array = explode("$$$", $brands[$row[3]]);
				$product_final_name = $final_array[1];

				$image_html = '<img src="'.JURI::base().'images/main_accordian/'.trim($row[1]).'.jpg" width="80px">';
				$image_html = $this->getImageHTML($final_array[0]);

				if(isset($row[6])&& $row[6]!="")
				{
					$monthly_price = ucfirst($row[6]);
					$zole = $monthly_price;

					$final_monthly_price = $monthly_price;
					if($row[3] == $price_array[0])
					{
						$final_monthly_price = ltrim (ucfirst($final_monthly_price),'$');
						$final_monthly_price += $price_array[1];
						$final_monthly_price = ucfirst("$".$final_monthly_price);
					}
				}
				if(isset($row[8])&& $row[8]!="")
				{
					$str1= ltrim (ucfirst($row[8]),'$');
					$eq_price = floatval($str1);
					$zole2 = $str1;
				}
				if(isset($row[9])&& $row[9]!="")
				{
					$str2= ltrim (ucfirst($row[9]),'$');
					$installation_price = floatval($str2);
					$zole3 = $str2;
				}
				if(isset($row[7])&& $row[7]!="")
				{
					$str3= ltrim (ucfirst($row[7]),'$');
					$onoffprice = floatval($str3);
					$zole4 = $str3;
				}

				$on_off_price = ($eq_price + $installation_price + $onoffprice);
				if($on_off_price == 0)
				{
					$on_off_price ='NA';
				}
				else
				{
					$on_off_price = '$'.$on_off_price ;
				}

				$row = explode(",",$product);
				// echo JURI::base().'images/cumhimages/'.trim($row[1]).'.png'; exit;
				$body.='<tr>'.
							'<td valign="middle" height="30" align="center" style="padding-left:10px; padding-right:10px">'.
							$image_html.
							'<p>&nbsp;</p>'.
							'</td>'.
							'<td valign="middle" align="center" style="padding-left:10px; padding-right:10px"><strong>'.ucfirst($product_final_name).'</strong></td>'.
							'<td valign="middle" align="center" style="padding-left:10px; padding-right:10px"><strong>Monthly Price: '.$final_monthly_price.'</strong></td>'.
							'<td valign="middle" align="center" style="padding-left:10px; padding-right:10px"><strong>One-off Price: '.$on_off_price.'</strong></td>'.
						'</tr>';

				$on_off_price ='NA';
			}
			// Prepare email body
			$body.= '<tr>'.
						'<td>&nbsp;</td><td>&nbsp;</td>'.
						'<td colspan="2" valign="middle" align="center" style="padding-left:7px; padding-right:7px;border-top:solid 1px #d6d6d6;padding:5px; color: #0087BA;"><strong>Your Estimated Costs : </strong></td>'.
					'</tr>';
			$body.= '<tr>'.
						'<td>&nbsp;</td><td>&nbsp;</td>'.
						'<td valign="middle" align="center" style="padding-left:7px; padding-right:7px;"><strong>Monthly Price : </strong></td>'.
						'<td valign="middle" align="center" style="padding-left:10px; padding-right:10px;"><strong>'.$price_array[2].'</strong></td>'.
					'</tr>';
			$body.= '<tr>'.
						'<td>&nbsp;</td><td>&nbsp;</td>'.
						'<td valign="middle" align="center" style="padding-left:7px; padding-right:7px;"><strong>One-Off Price : </strong></td>'.
						'<td valign="middle" align="center" style="padding-left:10px; padding-right:10px;"><strong>'.$price_array[3].'</strong></td>'.
					'</tr>'.
					'<tr><td colspan="6">&nbsp;</td></tr>';
					
			$body.= '</tbody></table>';

			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->AddBCC($mailfrom);
			$mail->IsHTML(true);
			$mail->addReplyTo(array($mailfrom, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($sitename.': '.$subject);
			$mail->setBody($body);

			$send = $mail->Send();
			if($send=="true")
			{
				return "success";
			}
			else
			{
				return $send;
			}
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function getImageHTML($suppliername)
	{
		if (strrchr($suppliername, '+'))
		{
			$imag = explode("+", $suppliername);
			$img1=$imag[0];
			$img2=$imag[1];

			$imagefile1="";
			$imagefile2="";
			$imagefile1= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img1).'.jpg';

			if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img1).'.jpg'))
			{
				$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
			}
			if($img2)
			{
				$imagefile2= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img2).'.jpg';

				if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img2).'.jpg'))
				{
					$imagefile2= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
				}
				return '<img src="'.$imagefile1.'" alt="'.$img1.'" class="two_imageSize"/><img src="'.$imagefile2.'" alt="'.$img2.'" class="two_imageSize"/>';
			}
		}
	    else
	    { 
			$imagefile1= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($suppliername).'.jpg';

			if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($suppliername).'.jpg'))
			{
				$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
			}
			return '<img src="'.$imagefile1.'" alt="'.$suppliername.'" class="imageSize"/>';
		}
	}

	public function getDescriptionLine($cat)
	{
		$db = JFactory::getDBO();
		$query = " SELECT `category_description`
					FROM `#__homeconnect`
					WHERE `category` LIKE '%".$cat."%'";
		$db->setQuery($query);
		return $db->loadResult();
	}

	function sendConfirmPost($product) 
	{
		$custmer_name = JRequest::getVar("name");
		$custmer_adress = JRequest::getVar("homeaddress");
		$custmer_phone = JRequest::getVar("phone");
		$data = array("products"=>$product ,"name" => $custmer_name, "address" => $custmer_adress, "telephone" => $custmer_phone);
		$data_string = json_encode($data);
		$ch = curl_init();
		$config_object = new JConfig();
		$url = $config_object->api_url."/v1/confirm";

	    curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
		$result = curl_exec($ch);
		return $result;
	}

	public function sendSelectionChanges($category, $selectedValues)
	{
		try {
		$config_object = new JConfig();
		$selectedValues = urlencode($selectedValues);
		$url = $config_object->api_url."/v1?category=".$category."&selection=".$selectedValues;
		$result = json_decode($this->curlRequest($url));
		
		 $data ="";
		 if(!empty($result->nav[0]->values))
			  {	
			  	
				foreach($result->nav[0]->values as $r)
				{
					$r->description = $this->getDescriptionLine($r->name);
					$data[] = $r;
					//var_dump($r);
					//echo '<br/>';
					//echo '<br/>';
				}
				
				$data = $this->reOrderAccordianData($data);
			  }
		$i=0;
		
		$brandhtml ="";
		
		foreach ($data as $d)
		{
			$i++;
			$j=0;
				
	            if(strcmp($category, JFilterOutput::stringURLSafe($d->name) ) == 0 )
	            {	
	            		            		
						$class = "BrandDisplayRightBorder";
					    foreach($d->values as $v)
						{
							
							$tip = $v->tips;
							//echo $tip;
							//var_dump($d->values);
							//exit;
							$j++;
							if( $j % 4 == 0)
							{
								$class = "BrandDisplayRightBorder BorderNone";
							}
							if($j > 4)
							{
								$class = "BrandDisplayTopBorder";
								if( $j % 4 == 0)
								{
									$class = "BrandDisplayTopBorder BorderNone";
								}
							}

						$brandhtml .='<a href="javascript:void(0);" class="brandtip"><div onClick="javascript:controllerObj.initSwitchInnerDivs(\''.JFilterOutput::stringURLSafe('main_category_inner_div_'.$d->name).'\', \''.JFilterOutput::stringURLSafe('main_category_inner_product_div_'.$d->name.'-'.$v->name).'\','. $i.', '.$j.',\''.$d->name.'\',\''.$v->name.'\')" class=\''.$class.'\'>';
						$brandhtml .="<div class='tile'><div class='tile_inner'>";
						$brandhtml .="";
						 if (strrchr($v->name, '+'))
						       {    
						         $imag = explode("+", $v->name);
								 $img1=$imag[0];
								 $img2=$imag[1];
								 
								 $imagefile1="";
								 $imagefile2="";
								$imagefile1= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img1).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img1).'.jpg'))
							    {
									$imagefile1= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    if($img2)
							    {
								    $imagefile2= JURI::base().'images/small_logo/'.JFilterOutput::stringURLSafe($img2).'.jpg';
	
									if(!file_exists(JPATH_SITE.DS.'images'.DS.'small_logo'.DS.JFilterOutput::stringURLSafe($img2).'.jpg'))
								    {
										$imagefile2= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
								    }
								$brandhtml .= "<img src='".$imagefile1."' alt='".$img1."' class='two_imageSize'/>";
								$brandhtml .= "<img src='".$imagefile2."' alt='".$img2."' class='two_imageSize'/>"; 
							    }
						       }
						       else 
							    {
							    $imagefile= JURI::base().'images/main_accordian/'.JFilterOutput::stringURLSafe($v->name).'.jpg';

								if(!file_exists(JPATH_SITE.DS.'images'.DS.'main_accordian'.DS.JFilterOutput::stringURLSafe($v->name).'.jpg'))
							    {
									$imagefile= JURI::base().'images/'.JFilterOutput::stringURLSafe('no-image-2').'.jpg';
							    }
							    	
							    $brandhtml .= "<img src='".$imagefile."' alt='". $imagefile."' class='imageSize'/>";	
							    }
							    $brandhtml .="</div></div></div>";
							    if(!empty($tip)&&isset($tip))
								{		
							      $brandhtml.="<span class='hovertitle'>".$tip."</span>";
								}
								$brandhtml.="</a>";
						}	    
							   
	            }
	           
			
		}
		
	     return $brandhtml;
		}
		catch (Exception $e)
		{
			return false;
		}
		
		
	}
}