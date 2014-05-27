<?php
namespace Shake\DI;

use Shake\Utils\Strings;
use Nette;


/**
 * DI\Container
 *
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
	public function getService($name)
	{
		if (isset($this->registry[$name]))
			return $this->registry[$name];

		// Base Nette service loading
		try {
			return parent::getService($name);
		
		// Try automatic creation
		} catch (Nette\DI\MissingServiceException $e) {

			// Repository
			if (strrpos($name, 'Repository') === (strlen($name) - 10)) {
				$this->registry[$name] = $this->createRepository($name);
				return $this->registry[$name];
			}

			// Service
			if (strrpos($name, 'Service') === (strlen($name) - 7)) {
				$this->registry[$name] = $this->createAppService($name);
				return $this->registry[$name];
			}

			throw $e;
		}
	}



	/**
	 * @param string
	 * @return object
	 */
	public function &__get($name)
	{
		return $this->getService($name);
	}



	/**
	 * @param string
	 * @return object
	 */
	private function createRepository($serviceName)
	{
		$className = $serviceName;
		$className[0] = strtoupper($className[0]);

		// User's repository
		if (class_exists($className)) {
			$repository = $this->createInstance($className);
		
		// Virtual repository
		} else {
			$repository = $this->createInstance('\Shake\Repository');

			$tableName = substr($className, 0, strrpos($className, 'Repository'));
			$tableName = Strings::toUnderscoreCase($tableName);
			$repository->setTableName($tableName);
		}

		return $repository;
	}



	/**
	 * @param string
	 * @return object
	 */
	private function createAppService($serviceName)
	{
		$className = $serviceName;
		$className[0] = strtoupper($className[0]);

		// User's service
		if (class_exists($className)) {
			$service = $this->createInstance($className);
		
		// Virtual service
		} else {
			$service = $this->createInstance('\Shake\Service');

			$repositoryName = substr($className, 0, strrpos($className, 'Service'));
			$repositoryName .= 'Repository';
			$service->setRepositoryName($repositoryName);
		}

		return $service;
	}

}