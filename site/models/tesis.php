<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ConferenceModelTesis extends JModel
{
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;

		$config = JFactory::getConfig();
	}

	function store($data)
	{
		$user = $this->getData($data['username']);

//������� �������
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'tesis.php');
//����������� ������� reg
		$row =& JTable::getInstance('Tesis', 'Table');
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
		}
		
		if ( $data['username'] == $user->username)
		{
			if (!$row->store(true)) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
			}
		} else {
			if (!$row->store()) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
			}
		}

		return true;
	}
	
	
	function getData($username)
	{
//���� ������ ������
		if (empty($this->_data))
		{
//����������� ������ � ��
			$query = $this->_buildQuery($username);
//��������� ������ ���������� � ������������ � ��������
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
//���������� ������
		return $this->_data;
	}
		function _buildQuery($username)
	{
//����� �� ��
		$query = 'SELECT *' .
			' FROM #__tesis'.
			' WHERE username = "'.$username.'"'
			;
//������� �������
		return $query;
	}
	
		function mailSelect()
	{
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"'
				;
		$this->_db->setQuery( $query );
		$this->_data = $this->_db->loadObjectList();

		return $this->_data;
	}
}