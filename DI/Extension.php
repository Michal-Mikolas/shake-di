<?php
declare(strict_types=1);

namespace Shake\DI;

use Nette;


/**
 * DI\Extension
 *
 * @author  Michal Mikoláš <nanuqcz@gmail.com>
 * @package Shake
 */
class Extension extends Nette\DI\CompilerExtension
{

	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$class->setExtends('Shake\\DI\\Container');
	}

}
