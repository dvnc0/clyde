<?php
declare(strict_types=1);

namespace Clyde\Injector;

use ReflectionClass;
use Exception;
use Clyde\Application;

class Injector {
	
	protected array $resolved_classes = [];
	protected array $registered_classes = [];
	protected Application $Application;

	public function __construct(Application $Application, array $registered_classes = [], ) {
		$this->Application = $Application;
		$this->registered_classes = $registered_classes;
	}

	public function resolve(string $class_name, array $dependencies = []): object {
		if (!empty($this->resolved_classes[$class_name])) {
			return $this->resolved_classes[$class_name];
		}

		return $this->createFreshInstance($class_name, $dependencies);
	}

	public function resolveFresh(string $class_name, array $dependencies = []) {
		return $this->createFreshInstance($class_name, $dependencies);
	}


	protected function createFreshInstance(string $class_name, array $dependencies):object {
		$dependencies[Application::class] = $this->Application;
		$dependencies[Injector::class] = $this;
		$reflection = new ReflectionClass($class_name);
		$constructor = $reflection->getConstructor();

		if (empty($constructor)) {
			$instance = new $class_name();
		} else {
			$constructor_params = $constructor->getParameters();
			$constructor_args = [];

			foreach ($constructor_params as $param) {
				$param_class = $param->getType()->getName();
				$param_name = $param->getName();

				if (!empty($param_class) && class_exists($param_class)) {
					$constructor_args[] = $this->resolve($param_class);
				} else if (!empty($dependencies[$param_name])) {
					$constructor_args[] = $dependencies[$param_name];
				} else {
					continue;
				}
			}

			$instance = $reflection->newInstanceArgs($constructor_args);
		}

		$this->resolved_classes[$class_name] = $instance;

		return $instance;
	}

}