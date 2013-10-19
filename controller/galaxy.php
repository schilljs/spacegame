<?php

/**
*
* @package SpaceGame Controller
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\controller;

class galaxy extends \schilljs\spacegame\controller\base
{
	protected $favorite_planets = array();

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper		$helper
	* @param \phpbb\template\template				$template
	* @param \phpbb\user					$user
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\favorites	$favorites
	* @param \schilljs\spacegame\navigation	$navigation
	* @param \schilljs\spacegame\user		$space_user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \schilljs\spacegame\core $space_core, \schilljs\spacegame\favorites $favorites, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\user $space_user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->space_core = $space_core;
		$this->favorites = $favorites;
		$this->navigation = $navigation;
		$this->space_user = $space_user;

		$this->user->add_lang_ext('schilljs/spacegame', 'galaxy');
	}

	/**
	* Display the favorite planets of the user
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function favorites()
	{
		$this->init();
		$this->favorite_planets = $this->favorites->get_planets();
		$this->display_favorite_quadrants();

		$this->display_map($this->favorite_planets);

		$this->template->assign_vars(array(
			'L_TITLE'			=> $this->user->lang('HL_GALAXY_FAVORITES'),
		));

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_FAVORITES'));
	}

	/**
	* Display the favorite planets of the user
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function map($mquad, $quad)
	{
		$this->init();
		$this->favorite_planets = $this->favorites->get_planets();
		$this->display_favorite_quadrants();

		$navigation = $this->space_user->get_navigation();
		if ($mquad == 0)
		{
			$mquad = $navigation['mquad'];
		}
		if ($quad == 0)
		{
			$quad = $navigation['quad'];
		}

		$planets = array();
		for ($i = 1; $i <= \schilljs\spacegame\core::SPACE_NUM_PLANETS; $i++)
		{
			$planets[$mquad][$quad][] = $i;
		}

		$this->display_map($planets);

		$this->template->assign_vars(array(
			'L_TITLE'			=> $this->user->lang('HL_GALAXY_MAP'),
		));

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_MAP'));
	}

	public function display_favorite_quadrants()
	{
		foreach ($this->favorites->get_quadrants() as $mquadrant => $quadrants)
		{
			foreach ($quadrants as $quadrant)
			{
				$this->template->assign_block_vars('favorite', array(
					'QUAD_NAME'	=> '[' . $mquadrant . ':' . $quadrant . ']',
					'U_QUAD'	=> $this->helper->url('galaxy/map/' . $mquadrant . '/' . $quadrant),
				));
			}
		}
	}

	public function display_map($planets)
	{
		foreach ($planets as $mquadrant => $quadrants)
		{
			foreach ($quadrants as $quadrant => $planets)
			{
				foreach ($planets as $planet)
				{
					$planet_image = 'black';
					switch ($planet % 5)
					{
						case 1:
							$planet_image = 'blue';
						break;
						case 2:
							$planet_image = 'green';
						break;
						case 3:
							$planet_image = 'grey';
						break;
						case 4:
							$planet_image = 'orange';
						break;
					}

					$s_favorite_planet = isset($this->favorite_planets[$mquadrant][$quadrant]) &&  in_array($planet, $this->favorite_planets[$mquadrant][$quadrant]);
					$this->template->assign_block_vars('planetrow', array(
						'PLANET_NAME'		=> $mquadrant . ':' . $quadrant . ':' . $planet,
						'PLANET_IMAGE'		=> 'planet_' . $planet_image,
						'S_FAVORITE_PLANET'		=> $s_favorite_planet,
						'U_FAVORITE_PLANET'		=> ($s_favorite_planet) ? $this->helper->url('galaxy/favorites/remove/' . $planet) : $this->helper->url('galaxy/favorites/add/' . $planet),
					));
				}
			}
		}
	}
}
