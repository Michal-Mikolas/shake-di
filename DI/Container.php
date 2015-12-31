<?php
namespace Shake\DI;

use Shake\Utils\Strings;
use Nette;


/**
 * DI\Container
 *
 * @package Shake
 * @author  Michal Mikoláš <nanuqcz@gmail.com>
 */
class Container extends Nette\DI\Container
{
	/** @var array */
	private $registry;


	/**
	 * @param string
	 * @return object
	 */
	public function &__get($name)
	{
		return $this->getService($name);
	}

}