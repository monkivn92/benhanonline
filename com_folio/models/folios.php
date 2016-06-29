<?php
defined('_JEXEC') or die;
class FolioModelFolios extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array('id', 'a.id',
											'title', 'a.title',
											'state', 'a.state',
											'company', 'a.company',
											'publish_up', 'a.publish_up',
											'publish_down', 'a.publish_down',
											'ordering', 'a.ordering');
											
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)	
	{
		parent::populateState('a.ordering', 'asc');
	}

	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(
						$this->getState(
						'list.select',
						'a.id, a.title,'.
						' a.state, a.company,'.
						'a.publish_up, a.publish_down, a.ordering')						
					 
					);
		
		$query->from($db->quoteName('#__folio').' AS a');

		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol == 'a.ordering')
		{
			$orderCol = 'a.title '.$orderDirn.', a.ordering';
		}

		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
}