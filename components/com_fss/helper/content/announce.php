<?php
/*
Generic editor and list class

List:
	Fields to list
	Main Field for link
	always has published
	always has author
	sometimes has ordering
	sometimes has added date
	sometimes has modifed date
	
	optional lookup fields for category
	
	optional split fields based on page break (annoucne + faq)
	
	*/
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content.php');

class FSS_ContentEdit_Announce extends FSS_ContentEdit
{
	function __construct()
	{
		$this->id = "announce";
		$this->descs = JText::_("Announcements");
		
		$this->table = "#__fss_announce";
		$this->has_added = 1;
		
		$this->fields = array();

		$field = new FSS_Content_Field("title",JText::_("TITLE"));
		$field->link = 1;
		$field->search = 1;
		$this->AddField($field);

		$field = new FSS_Content_Field("subtitle",JText::_("DESCRIPTION_FOR_MODULE"));
		$field->search = 1;
		$this->AddField($field);

		$field = new FSS_Content_Field("body",JText::_("ARTICLE"),"text");
		$field->show_pagebreak = 1;
		$field->more = "fulltext";
		$this->AddField($field);
		
		$this->list = array();
		$this->list[] = "title";
		
		$this->edit = array();
		$this->edit[] = "title";
		$this->edit[] = "subtitle";
		$this->edit[] = "body";
				
		$this->order = "added DESC";
		
		$this->link = "index.php?option=com_fss&view=announce&announceid=%ID%";
		
		$this->list_added = 1;
	}
}