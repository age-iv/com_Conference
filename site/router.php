<?php
function ConferenceBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	if (isset($query['task'])) {
		$segments[] = $query['task'];
		unset($query['task']);
	}
	
	if (isset($query['tid'])) {
		$segments[] = $query['tid'];
		unset($query['tid']);
	}

	return $segments;
}

function ConferenceParseRoute($segments)
{
	$vars = array();
	
	if (isset($segments[0]) && $segments[0] == 'tesis')
		{
			$vars['view']	= 'tesis';
			$vars['task']	= 'delete_tesis';
			$vars['tid']	= $segments[2];
		}
		
	return $vars;

}
