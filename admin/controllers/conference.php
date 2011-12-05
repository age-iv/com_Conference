<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


class ConferencesControllerConference extends JController
{

	function __construct( $config = array() )
	{
		parent::__construct( $config );
	}
	
	function display()
	{
//подключаем вид	
	require_once(JPATH_COMPONENT.DS.'views'.DS.'conference.php');
//вызываем функцию, непосредственно отвечающую за отображение
	ConferencesViewConference::Conferences();
	}
	
	
	function quickiconButton($link, $image, $text)
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}
}
?>