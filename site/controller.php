<?php
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class ConferenceController extends JController
{
  function display()
  {
//Определение параметров сom_user
    $usersConfig = &JComponentHelper::getParams( 'com_users' );

//проверка вошел ли пользователь
    $user   =& JFactory::getUser();

//если пользователь - гость
    if ( !$user->get('guest')&&(JRequest::getVar('view')=='registration')&&(JRequest::getVar('layout')!=='form')) {
//иначе "вы уже зарегистрированы"
      $this->setRedirect(JURI::base(),JText::_('You are already registered.'));
    } else {
      parent::display();
    }
    
//parent::display();
  }

  function save_tesis()
  {
  JRequest::checkToken() or jexit( 'Invalid Token' );
  global $mainframe;
//определение БД
    $user = JFactory::getUser();
    $post = JRequest::get('post');
    
    $sections = $post['sections'];
    $type = $post['type_lecture'];
    $name_lecture = trim($post['name_lecture']);
    
    $name = $user->get('name');
    $name = explode(' ',$name);
    for($i=1;$i<count($name);$i++)
    {
      $name[$i] = mb_substr($name[$i],0,1,utf8);
    }   
    $name = implode('_',$name);
    
    $name_lecture = explode(' ',$name_lecture);
    for($i=0;$i<count($name_lecture);$i++)
    {
      $name_lecture[$i] = mb_substr($name_lecture[$i],0,1,utf8);  
    }
    $name_lecture = implode('.',$name_lecture);
    $name_lecture = mb_substr($name_lecture,0,12,utf8);
    
    $file     = JRequest::getVar( 'file_name', null, 'files', 'array' );
    $folder   = JPATH_BASE.DS.'uploads'.DS;

//имя сайта
    $sitename     = $mainframe->getCfg( 'sitename' );
//мыло с какого сайта
    $mailfrom     = $mainframe->getCfg( 'mailfrom' );
//от админа
    $fromname     = $mainframe->getCfg( 'fromname' );

    $post['file_name'] = $file['name'];
    $post['username']= $user->get('username');
  
    $model =& $this->getModel('tesis');


//**************** Upload ****************************************
$file['name'] = $name.'-'.$name_lecture.'-'.$user->get('username').'-'.JFactory::getDate()->toFormat('%d.%m.%Y-%H.%M').'.'.JFile::getExt($file['name']);
$file['name'] = $this->imTranslite($file['name']);
echo $file['name'];
$sections = $this->imTranslite($sections);
$type = $this->imTranslite($type);

$filename = JFile::makeSafe($file['name']); // очищаем имя файла от всякой фигни
if ( trim($file['name']) == '') 
  {
    JError::raiseWarning( 500, "file empty");
    parent::display();
    return false;
  }
if(JFolder::exists($folder.$sections.$type)) { 
// вот этой конструкцией можно проверить расширение файла
// для использования нескольких типов файлов можно заюзать массив... 
if ( strtolower(JFile::getExt($filename) ) == 'doc') {
   if ( JFile::upload($file['tmp_name'], $folder.$sections.DS.$type.DS.$filename) ) 
   {
      //если все хорошо и файл залился - сообщаем юзеру что все гут или еще ченить на ваше усмотрение
//    echo "good";
   } else {
      //если что то вдруг пошло не так, то райзим сообщение об ошибочке или еще ченить 
//    echo "bad";
    JError::raiseWarning( 500, JText::_('error upload file'));
    parent::display();
    return false;
   }
} else {
   // ну тут тоже все понятно - если тип файла не соответствует условию, то ченить выводим.
    JError::raiseWarning( 500, JText::_('not doc or not file'));
    parent::display();
    return false;
}
} else {
  if(JFolder::create($folder))
  {
  if ( strtolower(JFile::getExt($filename) ) == 'doc') {
  if ( JFile::upload($file['tmp_name'], $folder.$sections.DS.$type.DS.$filename) ) 
   {
      //если все хорошо и файл залился - сообщаем юзеру что все гут или еще ченить на ваше усмотрение
//    echo "good";
   } else {
      //если что то вдруг пошло не так, то райзим сообщение об ошибочке или еще ченить 
//    echo "bad";
    JError::raiseWarning( 500, JText::_('error upload file'));
    parent::display();
    return false;
   }
} else {
   // ну тут тоже все понятно - если тип файла не соответствует условию, то ченить выводим.
    JError::raiseWarning( 500, JText::_('not doc or not file'));
    parent::display();
    return false;
}
  }
}

//****************************************************************
$post['file_name'] = '../uploads'.'/'.$sections.'/'.$type.'/'.$filename;

//сохранение
    if (!$model->store($post)) {
      JError::raiseWarning( 500, $model->getError() );
      parent::display();
      return false;
    }
//**************** Оповещение *****************************
//выбор всех супер админ-ов
  $adm = $model->mailSelect();
//заголовок для админ-ов
    $subject = sprintf ( JText::_( 'Details for tesis' ), $user->get('name'));
    $subject = html_entity_decode($subject, ENT_QUOTES);
//отправка админ-ам
    foreach ( $adm as $row )
    {
      if ($row->sendEmail)
      {
        $message = sprintf ( JText::_( 'SEND_MSG_ABOUT_TESIS_ADMIN' ), $row->name, $sitename, $post['authors'], $post['name_lecture'], $post['type_lecture']);
        $message = html_entity_decode($message, ENT_QUOTES);
        JUtility::sendMail($mailfrom, $fromname, $row->email, $subject, $message);
      }
    }
//****************************************************************

$message = JText::_( 'TESIS_COMPLETE' );
//редирект
    $this->setRedirect(JURI::base(), $message);
  }
  
  function save_hotel()
  {
  JRequest::checkToken() or jexit( 'Invalid Token' );
  global $mainframe;
  
  $user = JFactory::getUser();
  $post = JRequest::get('post');
  //имя сайта
  $sitename     = $mainframe->getCfg( 'sitename' );

  $post['username']= $user->get('username');
  
  $model =& $this->getModel('hotel');
//сохранение
    if (!$model->store($post)) {
      JError::raiseWarning( 500, $model->getError() );
      parent::display();
      return false;
    }
//**************** Оповещение *****************************
//выбор всех супер админ-ов
    $adm = $model->mailSelect();
//заголовок для админ-ов
    $subject = sprintf ( JText::_( 'Details for hotel' ), $user->name);
    $subject = html_entity_decode($subject, ENT_QUOTES);
//отправка админ-ам
    foreach ( $adm as $row )
    {
      if ($row->sendEmail)
      {
        $message = sprintf ( JText::_( 'SEND_MSG_ABOUT_HOTEL_ADMIN' ), $row->name, $sitename, $user->get('name'), $post['type_room'], $post['visit_date'], $post['exit_date']);
        $message = html_entity_decode($message, ENT_QUOTES);
        JUtility::sendMail($mailfrom, $fromname, $row->email, $subject, $message);
      }
    }
//****************************************************************
    $msg  = JText::_( 'HOTEL_COMPLETE' );
//редирект
    $this->setRedirect(JURI::base(), $msg);   
  }

  function register_edit()
  {
  JRequest::checkToken() or jexit( 'Invalid Token' );

  $user = JFactory::getUser();
  $post = JRequest::get('post');
  $userid = JRequest::getVar( 'id', 0, 'post', 'int' );
  
  $user_post['id'] = $user->get('id');
  $user_post['name'] = $post['surname'].' '.$post['name'];
  $user_post['username'] = $post['username'];
  $user_post['email'] = $post['email'];
  
  if ($user->get('id') == 0 || $userid == 0 ) {
    JError::raiseError( 403, JText::_('Access Forbidden') );
    return;
  }
  
  $post['username']= $user->get('username');
  
    $model =& $this->getModel('registration');
//сохранение
    if ($model->store($post, $user_post)) {
      $msg  = JText::_( 'EDIT_COMPLETE' );
//редирект
    $this->setRedirect(JURI::base(), $msg);
    } else {
      $msg  = $model->getError();
      parent::display();
    }
    

  }
  
  function register()
  {
//Определение параметров сom_user
    $usersConfig = &JComponentHelper::getParams( 'com_users' );

//проверка вошел ли пользователь
    $user   =& JFactory::getUser();

//если пользователь - гость
    if ( $user->get('guest')) {
//значит регистрация
      JRequest::setVar('view', 'registration');
    } else {
//иначе "вы уже зарегистрированы"
      $this->setRedirect(JURI::base(),JText::_('You are already registered.'));
    }

    parent::display();
  }
  
  
  function register_save()
  {
    JRequest::checkToken() or jexit( 'Invalid Token' );
    global $mainframe;
//объявление переменных
    $user     = clone(JFactory::getUser());
    $pathway  =& $mainframe->getPathway();
    $config   =& JFactory::getConfig();
    $authorize  =& JFactory::getACL();
    $document   =& JFactory::getDocument();
    $post = JRequest::get('post');
//массив пост для юзера
    $user_post['name'] = $post['surname'].' '.$post['name'];
    $user_post['username'] = $post['username'];
    $user_post['email'] = $post['email'];
    $user_post['password'] = $post['password'];
    $user_post['password2'] = $post['password2'];
    $user_post['gid'] = $post['gid'];
//имя по которому будут обращаться в письме    
    $name_for_mail = $post['name'];
//получение параметров
    $usersConfig = &JComponentHelper::getParams( 'com_users' );
//видимо шифрование пароля, проверка валидности мыла и юзернэйма
    if (!$user->bind( $user_post)) {
      JError::raiseError( 500, $user->getError());
    } 
    /*if (!$user->check()) {
      JError::raiseError( 500, $user->getError());
    }*/
    
    $model = &$this->getModel('registration');
    
//добавление переменных в массив user
  if ($user_post['id'] < 1)
    $user->set('id', 0);
    $user->set('gid', $user_post['gid']);
//получение даты
    $date =& JFactory::getDate();
//установка даты регистрации
    $user->set('registerDate', $date->toMySQL());

//проверка включена ли активация
    $useractivation = $usersConfig->get( 'useractivation' );
    if ($useractivation == '1')
    {
//если включена видимо отправляется письмо для активации
      jimport('joomla.user.helper');
//сохраняется произвольный пароль и пользователь блокируется до активации
      $user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
      $user->set('block', '1');
      $user->set('sendEmail', '1');
    }
    
//если ошибка при сохранении пользователя
    if (!$user->save())
    {
//если ошибка сохранения участника
      if ( !$model->store($post, $user_post))
      {
//вывод ошибки
        JError::raiseWarning( 500, $model->getError() );
        parent::display();
        return false;
      } else {
        JError::raiseWarning( 500, $model->getError() );
//если участник сохраняется получаем max id
        $total = $model->getMaxID();
        $model->deleteFromRegister($total);
        parent::display();
        return false;
        
      }

//если юзер гость, вывод ошибки, переход к функции register
    /*if($user->get('guest'))
      JError::raiseWarning('', JText::_( $user->getError()));
      $this->register();    
      return false;*/
    } else {
      if ( !$model->store($post, $user_post))
      {
        JError::raiseWarning( 500, $model->getError() );
        $model->deleteFromUser($user_post['username']);
        parent::display();
        return false;
      }
      }
//если все сохранилось, получение "чистого" пароля
    $password = $user->password_clear;
//удаление управляющих символов из пароля и отправка мыла
    $password = preg_replace('/[\x00-\x1F\x7F]/', '', $password);
    ConferenceController::_sendMail($user, $password, $name_for_mail);
//если активация включена    
    if ( $useractivation == 1 ) {
//"регистрация окончена, активируйте"
      $message  = JText::_( 'REG_COMPLETE_ACTIVATE' );
    } else {
//регистрация окончена
      $message = JText::_( 'REG_COMPLETE' );
    }
//редирект
    $this->setRedirect(JURI::base(), $message);
  }

  function imTranslite($str){

  static $tbl= array(
    'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
    'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
    'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
    'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
    'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
    'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
    'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"", 'ь'=>"", 'ю'=>"yu", 'я'=>"ya",
    'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ъ'=>"", 'Ь'=>"",
    'Ю'=>"YU", 'Я'=>"YA" , ' '=>"_"
  );
  $result = strtr($str, $tbl);
    return $result;
}
  
  function delete_tesis()
  {
    $user     = clone(JFactory::getUser());
    $username   = $user->get('username');
//объявление переменных
    $db   =& JFactory::getDBO();
    $tid  = JRequest::getVar( 'tid', array(), 'get', 'array' );
//удаляет тезисы
      $query = 'DELETE FROM #__tesis'
      . ' WHERE id = ' . implode( ' OR id = ', $tid )
      . ' AND username = '.$username
      ;
      $db->setQuery( $query );
//если не проходит запрос
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
        $this->setRedirect( JURI::base());
      } else {
//сообщение: все удачно!
      $this->setRedirect( JURI::base(), JText::_( 'Item removed' ) );
      }
  }
  
    function _sendMail(&$user, $password,$name_for_mail)
  {
    global $mainframe;
//определение БД
    $db   =& JFactory::getDBO();
//переменные для пользователя
    $name     = $user->get('name');
    $email    = $user->get('email');
    $username   = $user->get('username');
//определение конфигурации ком_юзер
    $usersConfig  = &JComponentHelper::getParams( 'com_users' );
//имя сайта
    $sitename     = $mainframe->getCfg( 'sitename' );
//активация пользователя
    $useractivation = $usersConfig->get( 'useractivation' );
//мыло с какого сайта
    $mailfrom     = $mainframe->getCfg( 'mailfrom' );
//от админа
    $fromname     = $mainframe->getCfg( 'fromname' );
//урл сайта
    $siteURL    = JURI::base();
//заголовок    
    $subject  = sprintf ( JText::_( 'Account details for' ), $name);
    $subject  = html_entity_decode($subject, ENT_QUOTES);
    if ( $useractivation == 1 ){
//если активация включена - ссылка для активации
      $message = sprintf ( JText::_( 'SEND_MSG_ACTIVATE' ), $name_for_mail, $sitename, $siteURL."index.php?option=com_user&task=activate&activation=".$user->get('activation'), $siteURL, $username, $password);
    } else {
//если нет, просто информационное письмо
      $message = sprintf ( JText::_( 'SEND_MSG' ), $name_for_mail, $sitename, $siteURL);
    }

    $message = html_entity_decode($message, ENT_QUOTES);

//выбор всех супер админ-ов
    $query = 'SELECT name, email, sendEmail' .
        ' FROM #__users' .
        ' WHERE LOWER( usertype ) = "super administrator"';
    $db->setQuery( $query );
    $rows = $db->loadObjectList();

//получение маилфром и фромнэйм, если их нет
    if ( ! $mailfrom  || ! $fromname ) {
      $fromname = $rows[0]->name;
      $mailfrom = $rows[0]->email;
    }
//отправка
    JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

//заголовок для админ-ов
    $subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
    $subject2 = html_entity_decode($subject2, ENT_QUOTES);

//отправка админ-ам
    foreach ( $rows as $row )
    {
      if ($row->sendEmail)
      {
        $message2 = sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username);
        $message2 = html_entity_decode($message2, ENT_QUOTES);
        JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
      }
    }
  }
}
?>