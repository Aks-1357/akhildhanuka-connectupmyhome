<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class fsssModelticketemail extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__fss_ticket_email '.
					'  WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = '';
			$this->_data->server = '';
			$this->_data->type = '';
			$this->_data->port = '110';
			$this->_data->username = '';
			$this->_data->password = '';
			$this->_data->checkinterval = '5';
			$this->_data->newticketsfrom = '';
			$this->_data->allowunknown = 0;
			$this->_data->prod_id = '';
			$this->_data->dept_id = '';
			$this->_data->cat_id = '';
			$this->_data->pri_id = '';
			$this->_data->handler = '';
			$this->_data->usessl = '';
			$this->_data->usetls = '';
			$this->_data->validatecert = '';
			$this->_data->allow_joomla = '';
			$this->_data->published = '1';
			$this->_data->toaddress = '';
			$this->_data->ignoreaddress = '';
			$this->_data->onimport = 'markread';
			$this->_data->cronid = 0;
		}
		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();

		// put entry into cron table
		$cronid = $data['cronid'];
		//print_r($data);

		$aparams = serialize($data);
		
		if ($cronid > 0)
		{
			$qry = "REPLACE INTO #__fss_cron (id, cronevent, class, `interval`, published, params) VALUES (";
			$qry .= "\"".$data['cronid'] . "\", ";
		} else {
			$qry = "INSERT INTO #__fss_cron (cronevent, class, `interval`, published, params) VALUES (";
		}
		$qry .= "\"EMail Check - " . FSSJ3Helper::getEscaped($this->_db,$data['name']) . "\", ";
		$qry .= "\"EMailCheck\", ";
		$qry .= "\"" . FSSJ3Helper::getEscaped($this->_db,$data['checkinterval']) . "\", ";
		$qry .= "\"" . FSSJ3Helper::getEscaped($this->_db,$data['published']) . "\", ";
		$qry .= "\"" . FSSJ3Helper::getEscaped($this->_db,$aparams) . "\")";

		//echo $qry."<br>";

		$this->_db->SetQuery($qry);
		$this->_db->query();

		if (!$cronid)
		{
			$data['cronid'] = $this->_db->insertid();	
		}

		/*print_p($data);
		exit;*/
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}


		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {

				$qry = "SELECT cronid FROM #__fss_ticket_email WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				//echo $qry."<br>";
				$temp = $this->_db->loadObject();

				$cronid = $temp->cronid;
				if ($cronid)
				{
					$qry = "DELETE FROM #__fss_cron WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cronid)."'";	
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$this->_db->query();
				}
				
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
			}
		}

		return true;
	}

	function unpublish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table =& $this->getTable();

		$result = $table->publish($cids, 0);
		if ($result)
		{
			if (count( $cids )) {
				foreach($cids as $cid) {

					$qry = "SELECT cronid FROM #__fss_ticket_email WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cid)."'";
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$temp = $this->_db->loadObject();

					$cronid = $temp->cronid;
					if ($cronid)
					{
						$qry = "UPDATE #__fss_cron SET published = 0 WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cronid)."'";	
						$this->_db->setQuery($qry);
						//echo $qry."<br>";
						$this->_db->query();
					}				
				}	
			}
		}
		return $result;
	}

	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table =& $this->getTable();

		$result = $table->publish($cids, 1);
		if ($result)
		{
			if (count( $cids )) {
				foreach($cids as $cid) {

					$qry = "SELECT cronid FROM #__fss_ticket_email WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cid)."'";
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$temp = $this->_db->loadObject();

					$cronid = $temp->cronid;
					if ($cronid)
					{
						$qry = "UPDATE #__fss_cron SET published = 1 WHERE id = '".FSSJ3Helper::getEscaped($this->_db,$cronid)."'";	
						$this->_db->setQuery($qry);
						//echo $qry."<br>";
						$this->_db->query();
					}				
				}	
			}
		}
		return $result;
	}
}

