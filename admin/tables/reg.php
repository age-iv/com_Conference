<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
class TableReg extends JTable
{
  var $id = null;
  var $surname = null;
  var $name = null;
  var $sex = null;
  var $status = null;
  var $date_of_birth = null;
  var $academic_degree = null;
  var $academic_rank = null;
  var $job = null;
  var $organisation = null;
  var $dept = null;
  var $course_group = null;
  var $street = null;
  var $house = null;
  var $flat = null;
  var $city = null;
  var $region = null;
  var $country = null;
  var $post_code = null;
  var $phone = null;
  var $fax = null;
  var $email = null;
  var $acomodation_person = null;
  var $username = null;
  var $hostel = null;

  function __construct(& $db) {
    parent::__construct('#__register', 'id', $db);
  }
  
  function check()
  {
    global $mainframe;
    
    jimport('joomla.mail.helper');
    
    
    $params = &$mainframe->getParams();
    // Validate user information
    if (trim( $this->surname ) == '') {
      $this->setError( JText::_( 'Please enter your surname.' ) );
      return false;
    }
    
    if (trim( $this->name ) == '') {
      $this->setError( JText::_( 'Please enter your name.' ) );
      return false;
    }
    
    if ( $params->get( 'show_sex', 1 ) )
    if (trim( $this->sex ) == '') {
      $this->setError( JText::_( 'Please enter your sex.' ) );
      return false;
    }
    
    if ( $params->get( 'show_status', 1 ) )
    if (trim( $this->status ) == '') {
      $this->setError( JText::_( 'Please enter your status.' ) );
      return false;
    }
    
    if ( $params->get( 'show_job', 1 ) )
    if (trim( $this->job ) == '') {
      $this->setError( JText::_( 'Please enter your job.' ) );
      return false;
    }
    
    if ( $params->get( 'show_organisation', 1 ) )
    if (trim( $this->organisation ) == '') {
      $this->setError( JText::_( 'Please enter your organisation.' ) );
      return false;
    }
    
    if ( $params->get( 'show_course_group', 1 ) )
    if (trim( $this->course_group ) == '') {
      $this->setError( JText::_( 'Please enter course_group.' ) );
      return false;
    }
    
    if ( $params->get( 'show_country', 1 ) )
    if (trim( $this->country ) == '') {
      $this->setError( JText::_( 'Please enter your country.' ) );
      return false;
    }
    
    if ( $params->get( 'show_city', 1 ) )
    if (trim( $this->city ) == '') {
      $this->setError( JText::_( 'Please enter your city.' ) );
      return false;
    }
    
    if ( $params->get( 'show_street', 1 ) )
    if (trim( $this->street ) == '') {
      $this->setError( JText::_( 'Please enter street.' ) );
      return false;
    }
    
    if ( $params->get( 'show_house', 1 ) )
    if (trim( $this->house ) == '') {
      $this->setError( JText::_( 'Please enter house.' ) );
      return false;
    }
    
    if ( $params->get( 'show_post_code', 1 ) )
    if (trim( $this->post_code ) == '') {
      $this->setError( JText::_( 'Please enter post code.' ) );
      return false;
    }
    
    if ( $params->get( 'show_phone', 1 ) )
    if (trim( $this->phone ) == '') {
      $this->setError( JText::_( 'Please enter phone.' ) );
      return false;
    }
    
    if ( $params->get( 'show_dept', 1 ) )
    if (trim( $this->dept ) == '') {
      $this->setError( JText::_( 'Please enter dept.' ) );
      return false;
    }
    
    if ( $params->get( 'show_course_group', 1 ) )
    if (trim( $this->course_group ) == '') {
      $this->setError( JText::_( 'Please enter course_group.' ) );
      return false;
    }
    
    if (trim( $this->email ) == '') {
      $this->setError( JText::_( 'Please enter e-mail.' ) );
      return false;
    }
    
    if (eregi( "[<>\"'%;()&]", $this->surname) || strlen(utf8_decode($this->surname )) < 2) {
      $this->setError( JText::sprintf( 'VALID_AZ09', JText::_( 'Surname' ), 2 ) );
      $this->setError( JText::_( 'Please enter correct surname.' ) );
      return false;
    }
    
    if (eregi( "[<>\"'%;()&]", $this->name) || strlen(utf8_decode($this->name )) < 2) {
      $this->setError( JText::sprintf( 'VALID_AZ09', JText::_( 'Name' ), 2 ) );
      $this->setError( JText::_( 'Please enter correct name.' ) );
      return false;
    }
    
    if ((trim($this->email) == "") || ! JMailHelper::isEmailAddress($this->email) ) {
      $this->setError( JText::_( 'WARNREG_MAIL' ) );
      return false;
    }
    
    if (trim( $this->surname ) == 'test') {
      $this->setError( JText::_( 'Forbidden use "test" in surname.' ) );
      return false;
    }
    
    if (trim( $this->name ) == 'test') {
      $this->setError( JText::_( 'Forbidden use "test" in name.' ) );
      return false;
    }
    ///////////////////////////////////////       TODO        ///////////////////////////
    // check for existing username
    $query = 'SELECT id'
    . ' FROM #__register '
    . ' WHERE LOWER (surname) = ' . $this->_db->Quote($this->surname)
    . ' AND LOWER (name) = ' . $this->_db->Quote($this->name)
    . ' AND LOWER (job) = ' . $this->_db->Quote($this->job)
    . ' AND LOWER (organisation) = ' . $this->_db->Quote($this->organisation)
    . ' AND email = ' . $this->_db->Quote($this->email)
	. ' AND id != '. (int) $this->id
    ;
    $this->_db->setQuery( $query );
    $xid = intval( $this->_db->loadResult() );
    if ($xid && $xid != intval( $this->id )) {
      $this->setError(  JText::_('MEMBER_REGISTER'));
      return false;
    }
  
  
      return true;
  }
    
  function store( $updateNulls=false )
  {
    
    $k = $this->_tbl_key;
    $key =  $this->$k;
    
    if ($key)
    {
      // existing record
      $ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, $updateNulls );
    }
    else
    {
      // new record
      $ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
    }

    if( !$ret )
    {
      $this->setError( strtolower(get_class( $this ))."::". JText::_( 'store failed' ) ."<br />" . $this->_db->getErrorMsg() );
      return false;
    }
    else
    {
      return true;
    }
  }
}
?>