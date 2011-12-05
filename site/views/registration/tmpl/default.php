<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script src="<?php echo $this->baseurl ?>/components/com_conference/js/val.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_conference/js/style.css" type="text/css" />
<script type="text/javascript">
<!--
  Window.onDomReady(function(){
    document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); } );
  });
// -->
</script>

<?php
  if(isset($this->_message)){
    $this->display('message');
  }
  
  
  $u =& JFactory::getURI();
  $url=$u->toString();
?>
<?php if($this->params->get('end_registration')<JFactory::getDate()->toFormat('%Y-%m-%d')){?>
<?= JText::_('Registration is over')?>
<?php } else { ?>
<form action="<?php echo JRoute::_( $url ); ?>" method="post" id="josForm" name="josForm" class="form-validate" autocomplete="off">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif;?>

<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if (  $this->params->def( 'show_comp_description', 1 ) ) : ?>
<tr>
  <td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?= $this->params->get('description') ?>
  </td>
</tr>
<?php endif; ?>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
  <td height="40">
    <label id="surnamemsg" for="surname">
      <?= JText::_( 'Surname' ) ?>:
    </label>
  </td>
  <td>
      <input type="text" name="surname" id="surname" size="55%" value="<?= $this->post['surname'] ?>" class="inputbox required" maxlength="50" /><noscript> *</noscript>
    <div id="surnamestk" class="stiker">
      <?= JText::_('Please enter your surname.')?>
    </div>
  </td>
</tr>
<tr>
  <td width="30%" height="40">
    <label id="namemsg" for="name">
      <?= JText::_( 'Name' ) ?>:
    </label>
  </td>
    <td>
      <input type="text" name="name" id="name" size="55%" value="<?php echo $this->post['name'];?>" class="inputbox required" maxlength="50" /><noscript> *</noscript>
    <div id="namestk" class="stiker"><?= JText::_('Please enter your name.') ?></div>
    </td>
</tr>

<?php if ( $this->params->get( 'show_sex', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="sexmsg" for="sex">
      <?php echo JText::_( 'sex' ); ?>:
    </label>
  </td>
  <td>
<?php
$type[] = JHTML::_('select.option',  "", JText::_('--Select sex--') );
$type[] = JHTML::_('select.option',  JText::_('m'), JText::_('man') );
$type[] = JHTML::_('select.option',  JText::_('w'), JText::_('woman') );
echo JHTML::_('select.genericlist', $type, 'sex', 'class="inputbox required"', 'value', 'text', $this->post['sex']);
unset($type);
?><noscript> *</noscript>
<div id="sexstk" class="stiker"><?php echo JText::_('Please enter your sex.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_date_of_birth', 1 ) ) : ?>
<tr>
  <td>
    <label id="date_of_birthmsg" for="date_of_birth">
    <?php echo JText::_( 'date_of_birth' ); ?>:
    </label>
  </td>
  <td>
    <?php echo JHTML::calendar($this->post['date_of_birth'], 'date_of_birth', 'date_of_birth', '%d.%m.%Y','size="40" class="inputbox validate-date" maxlength="12"'); ?>
    <div id="date_of_birthstk" class="stiker"><?php echo JText::_('Please enter your date of birth.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_status', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="statusmsg" for="status">
      <?php echo JText::_( 'status' ); ?>:
    </label>
  </td>
  <td>
<?php
$status = explode('|',$this->params->get( 'status' ));
$type[] = JHTML::_('select.option',  "", JText::_('--Select status--') );
for ($i=0;$i<count($status);$i++):
$type[] = JHTML::_('select.option',  $status[$i], $status[$i] );
endfor;
echo JHTML::_('select.genericlist', $type, 'status', 'class="inputbox required"', 'value', 'text', $this->post['status']);
unset($type);
?><noscript> *</noscript>
<div id="statusstk" class="stiker"><?php echo JText::_('Please enter your status.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_academic_degree', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="academic_degreemsg" for="academic_degree">
      <?php echo JText::_( 'academic_degree' ); ?>:
    </label>
  </td>
  <td>
<?php
for ($i=0;$i<count($this->academic_degree);$i++):
$type[] = JHTML::_('select.option',  $this->academic_degree[$i]->value, $this->academic_degree[$i]->title );
endfor;
echo JHTML::_('select.genericlist', $type, 'academic_degree', 'class="inputbox required"', 'value', 'text', $this->post['academic_degree']);
unset($type);
?>
<div id="academic_degreestk" class="stiker"><?php echo JText::_('Please enter your academic degree.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_academic_rank', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="academic_rankmsg" for="academic_rank">
      <?php echo JText::_( 'academic_rank' ); ?>:
    </label>
  </td>
  <td>
<?php
for ($i=0;$i<count($this->academic_rank);$i++):
$type[] = JHTML::_('select.option',  $this->academic_rank[$i]->value, $this->academic_rank[$i]->title );
endfor;
echo JHTML::_('select.genericlist', $type, 'academic_rank', 'class="inputbox"', 'value', 'text', $this->post['academic_rank']);
unset($type);
?>
<div id="academic_rankstk" class="stiker"><?php echo JText::_('Please enter your academic rank.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_job', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="jobmsg" for="job">
      <?php echo JText::_( 'job' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="job" id="job" size="55%" value="<?php echo $this->post['job'];?>" maxlength="50" class="inputbox required" /><noscript> *</noscript>
    <div id="jobstk" class="stiker"><?php echo JText::_('Please enter your job.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_organisation', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="organisationmsg" for="organisation">
      <?php echo JText::_( 'organisation' ); ?>:
    </label>
  </td>
  <td>
    <textarea style="width: 74%;" rows="5" name="organisation" id="organisation" class="inputbox required"><?php echo $this->post['organisation'];?></textarea><noscript> *</noscript>
    <div id="organisationstk" class="stiker"><?php echo JText::_('Please enter your organisation.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'dept', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="deptmsg" for="dept">
      <?php echo JText::_( 'dept' ); ?>:
    </label>
  </td>
  <td>
    <input type="text" name="dept" id="dept" size="55%" value="<?php echo $this->post['dept'];?>" maxlength="50" class="inputbox" />
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_course_group', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="course_groupmsg" for="course_group">
      <?php echo JText::_( 'course_group' ); ?>:
    </label>
  </td>
  <td>
    <input type="text" name="course_group" id="course_group" size="55%" value="<?php echo $this->post['course_group'];?>" maxlength="50" class="inputbox required" /><noscript> *</noscript>
    <div id="course_groupstk" class="stiker"><?php echo JText::_('Please enter course_group.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_country', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="countrymsg" for="country">
      <?php echo JText::_( 'country' ); ?>:
    </label>
  </td>
  <td>
<?php
$type[] = JHTML::_('select.option',  "", JText::_('--Select country--') );
for ($i=0;$i<count($this->country);$i++):
$type[] = JHTML::_('select.option',  $this->country[$i]->value, JText::_($this->country[$i]->value) );
endfor;
echo JHTML::_('select.genericlist', $type, 'country', ' class="inputbox required"', 'value', 'text', $this->post['country']);
unset($type);
?><noscript> *</noscript>
<div id="countrystk" class="stiker"><?php echo JText::_('Please enter your country');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_region', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="regionmsg" for="region">
      <?php echo JText::_( 'region' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="region" id="region" size="55%" value="<?php echo $this->post['region'];?>" class="inputbox" maxlength="100" />
    <div id="regionstk" class="stiker"><?php echo JText::_('Please enter your region.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_city', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="citymsg" for="city">
      <?php echo JText::_( 'city' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="city" id="city" size="55%" value="<?php echo $this->post['city'];?>" class="inputbox required" maxlength="100" /><noscript> *</noscript>
    <div id="citystk" class="stiker"><?php echo JText::_('Please enter your city.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_street', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="streetmsg" for="street">
      <?php echo JText::_( 'street' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="street" id="street" size="55%" value="<?php echo $this->post['street'];?>" class="inputbox required" maxlength="100" /><noscript> *</noscript>
    <div id="streetstk" class="stiker"><?php echo JText::_('Please enter street.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_house', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="housemsg" for="house">
      <?php echo JText::_( 'house' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="house" id="house" size="5" value="<?php echo $this->post['house'];?>" class="inputbox required" maxlength="5" /><noscript> *</noscript>
    <div id="housestk" class="stiker"><?php echo JText::_('Please enter house.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_flat', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="flatmsg" for="flat">
      <?php echo JText::_( 'flat' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="flat" id="flat" size="5" value="<?php echo $this->post['flat'];?>" class="inputbox validate-numeric" maxlength="5" />
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_post_code', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="post_codemsg" for="post_code">
      <?php echo JText::_( 'post_code' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="post_code" id="post_code" size="55%" value="<?php echo $this->post['post_code'];?>" class="inputbox required" maxlength="20" /><noscript> *</noscript>
    <div id="post_codestk" class="stiker"><?php echo JText::_('Please enter post code.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_phone', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="phonemsg" for="phone">
      <?php echo JText::_( 'phone' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="phone" id="phone" size="55%" value="<?php echo $this->post['phone'];?>" maxlength="20" class="inputbox required" /><noscript> *</noscript>
    <div id="phonestk" class="stiker"><?php echo JText::_('Please enter phone.');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_fax', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="faxmsg" for="fax">
      <?php echo JText::_( 'fax' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="fax" id="fax" size="55%" value="<?php echo $this->post['fax'];?>" class="inputbox" maxlength="20" />
  </td>
</tr>
<?php endif;?>
<tr>
  <td height="40">
    <label id="emailmsg" for="email">
      <?php echo JText::_( 'Email' ); ?>:
    </label>
  </td>
  <td>
    <input type="text" id="email" name="email" size="55%" value="<?php echo $this->post['email'];?>" class="inputbox required validate-email" maxlength="100" /><noscript> *</noscript>
    <div id="emailstk" class="stiker"><?php echo JText::_('Please enter e-mail.');?></div>
  </td>
</tr>
<?php if ( $this->params->get( 'show_hostel', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="hostelmsg" for="hostel">
      <?php echo JText::_( 'hostel' ); ?>:
    </label>
  </td>
  <td>
      <input type="checkbox" name="hostel" id="hostel" value="1" class="inputbox" <?php if($this->post['hostel']==1) echo "checked"; ?>/>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_acomodation_person', 1 ) ) : ?>
<tr>
  <td height="40">
    <label id="acomodation_personmsg" for="acomodation_person">
      <?php echo JText::_( 'acomodation_person' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="acomodation_person" id="acomodation_person" size="55%" value="<?php echo $this->post['acomodation_person'];?>" class="inputbox" maxlength="50" />
  </td>
</tr>
<?php endif;?>
<tr>
  <td height="40">
    <label id="username1msg" for="username1">
      <?php echo JText::_( 'username' ); ?>:
    </label>
  </td>
  <td>
  <?php $username=rand(10,99)*1000+$this->id;
  ?>
    <input type="text" size="55%" value="<?php echo $username;?>" class="inputbox" maxlength="150" disabled="disabled" />
    <input type="hidden" name="username" class="inputbox required validate-username" value="<?php echo $username;?>" />
  </td>
</tr>
<tr>
  <td height="40">
    <label id="pwmsg" for="password">
      <?php echo JText::_( 'Password' ); ?>:
    </label>
  </td>
    <td>
      <input class="inputbox required validate-password" type="password" id="password" name="password" size="55%" value="" /><noscript> *</noscript>
    <div id="passwordstk" class="stiker"><?php echo JText::_('Please enter password.');?></div>
    </td>
</tr>
<tr>
  <td height="40">
    <label id="pw2msg" for="password2">
      <?php echo JText::_( 'Verify Password' ); ?>:
    </label>
  </td>
  <td>
    <input class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="55%" value="" /><noscript> *</noscript>
    <div id="password2stk" class="stiker"><?php echo JText::_('Please enter password2.');?></div>
  </td>
</tr>
<tr>
  <td colspan="2">
    <div id="common" class="common"><?php echo JText::_('One or more fields empty');?></div>
    <noscript><div id="common_noscript"><?php echo JText::_('REGISTER_REQUIRED');?></div></noscript>
  </td>
</tr>
</table>
  <button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
  <button class="button" type="reset"><?php echo JText::_('Reset'); ?></button>
  <input type="hidden" name="task" value="register_save" />
  <input type="hidden" name="id" value="0" />
  <input type="hidden" name="usertype" value="registred" />
  <input type="hidden" name="gid" value="18" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php }?>