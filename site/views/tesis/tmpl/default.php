<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script src="<?php echo $this->baseurl ?>/components/com_conference/js/val.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_conference/js/style.css" type="text/css" />

<?php
  if(isset($this->_message)){
    $this->display('message');
  }
  
  
  $u =& JFactory::getURI();
  $url=$u->toString();

  if($this->params->get('end_tesis')<JFactory::getDate()->toFormat('%Y-%m-%d')){?>
<?= JText::_('Tesis is over')?>
<?php } else { ?>
<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" id="josForm" name="josForm" class="form-validate"  autocomplete="off" enctype="multipart/form-data">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif;?>
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if (  $this->params->def( 'show_comp_description', 1 ) ) : ?>
<tr>
  <td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php
    echo $this->params->get('description');
  ?>
  </td>
</tr>
<?php endif; ?>
</table>

<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
  <td>
    <label id="authorsmsg" for="authors">
      <?php echo JText::_( 'authors' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="authors" id="authors" size="40" value="" class="inputbox required" maxlength="100" /><noscript> *</noscript>
    <div id="authorsstk" class="stiker"><?php echo JText::_('Please enter authors.');?></div>
  </td>
</tr>
<tr>
  <td>
    <label id="supervisormsg" for="supervisor">
      <?php echo JText::_( 'supervisor' ); ?>:
    </label>
  </td>
  <td>
      <input type="text" name="supervisor" id="supervisor" size="40" value="" class="inputbox required" maxlength="100" /><noscript> *</noscript>
    <div id="supervisorstk" class="stiker"><?php echo JText::_('Please enter supervisor.');?></div>
  </td>
</tr>
          <tr>
            <td>
              <label id="name_lecturemsg" for="name_lecture">
                <?php echo JText::_( 'name_lecture' ); ?>:
              </label>
            </td>
            <td>
              <textarea class="inputbox required" cols="36" rows="8" name="name_lecture" id="name_lecture"></textarea><noscript> *</noscript>
              <div id="name_lecturestk" class="stiker"><?php echo JText::_('Please enter name lecture.');?></div>
            </td>
          </tr>
<?php if ( $this->params->get( 'show_type_lecture', 1 ) ) : ?>
          <tr>
            <td>
              <label id="type_lecturemsg" for="type_lecture">
                <?php echo JText::_( 'type_lecture' ); ?>:
              </label>
            </td>
            <td>
<?php
$type_lecture = explode('|',$this->params->get( 'type_lecture' ));
$type[] = JHTML::_('select.option',  "", JText::_('--Select type_lecture--') );
for ($i=0;$i<count($type_lecture);$i++):
$type[] = JHTML::_('select.option',  $type_lecture[$i], $type_lecture[$i] );
endfor;
echo JHTML::_('select.genericlist', $type, 'type_lecture', 'class="inputbox required"', 'value', 'text');
unset($type);
?><noscript> *</noscript>
<div id="type_lecturestk" class="stiker"><?php echo JText::_('Please select type lecture.');?></div>
            </td>
          </tr>
<?php endif;?>
<?php if ( $this->params->get( 'show_sections', 1 ) ) : ?>
          <tr>
            <td>
              <label id="sectionsmsg" for="sections">
                <?php echo JText::_( 'sections' ); ?>:
              </label>
            </td>
            <td>
<?php
$sections = explode('|',$this->params->get( 'sections' ));
$type[] = JHTML::_('select.option',  "", JText::_('--Select sections--') );
for ($i=0;$i<count($sections);$i++):
$type[] = JHTML::_('select.option',  $sections[$i], $sections[$i] );
endfor;
echo JHTML::_('select.genericlist', $type, 'sections', 'class="inputbox required"', 'value', 'text');
unset($type);
?><noscript> *</noscript>
<div id="sectionsstk" class="stiker"><?php echo JText::_('Please select sections.');?></div>
            </td>
          </tr>
<?php endif;?>

          <tr>
            <td>
              <label id="filemsg" for="file">
                <?php echo JText::_( 'Upload File' ); ?><br /> (<?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo ($this->config->get('upload_maxsize') / 1000000); echo JText::_( 'Mb' ); ?>):
              </label>
            </td>
            <td>
            <input type="file" id="upload-file" name="file_name" class="inputbox" accept="application/msword"/><noscript> *</noscript>
            <div id="filestk" class="stiker"><?php echo JText::_('Please enter file name.');?></div>
            </td>
          </tr>
          <tr>
  <td colspan="2">
    <div id="common" class="common"><?php echo JText::_('One or more fields empty');?></div>
    <noscript><div id="common_noscript"><?php echo JText::_('REGISTER_REQUIRED');?></div></noscript>
  </td>
</tr>
</table>
  <button class="button validate" type="submit"><?php echo JText::_('Send'); ?></button>
  <button class="button" type="reset"><?php echo JText::_('Reset'); ?></button>
  <input type="hidden" name="task" value="save_tesis" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php } ?>