<?php
/**
*
* @package SpaceGame Navigation
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'PLANET'			=> 'Planet',
	'PLANETS'			=> 'Planets',
	'PLAYER'			=> 'Player',
	'PLAYERS'			=> 'Players',
));
