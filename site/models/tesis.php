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

//реквайр таблицы
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'tesis.php');
//подключение таблицы reg
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
//если данные пустые
		if (empty($this->_data))
		{
//выполняется запрос к БД
			$query = $this->_buildQuery($username);
//заполняет данные содержимым в соответствии с лимитами
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
//возвращает данные
		return $this->_data;
	}
		function _buildQuery($username)
	{
//выбор из БД
		$query = 'SELECT *' .
			' FROM #__tesis'.
			' WHERE username = "'.$username.'"'
			;
//возврат запроса
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