<?php
declare(strict_types=1);

namespace Clyde\Injector;

use ReflectionClass;
use Clyde\Application;

class Injector
{
	
	/**
	 * Resolved Classes
	 *
	 * @var array<class-string, object>
	 */
	protected array $resolved_classes = [];

	/**
	 * Registered classes
	 *
	 * @var array<class-string, object>
	 */
	protected array $registered_classes = [];

	/**
	 * Application
	 *
	 * @var Application
	 */
	protected Application $Application;

	/**
	 * Construct
	 *
	 * @param Application                 $Application        The Application instance
	 * @param array<class-string, object> $registered_classes The registered classes
	 */
	public function __construct(Application $Application, array $registered_classes = []) {
		$this->Application        = $Application;
		$this->registered_classes = $registered_classes;
	}

	/**
	 * Register a class
	 *
	 * @param string        $class_name   The class name
	 * @param array<string> $dependencies The dependencies
	 * @return object
	 */
	public function resolve(string $class_name, array $dependencies = []): object {
		if (!empty($this->registered_classes[$class_name])) {
			return $this->registered_classes[$class_name];
		}

		if (!empty($this->resolved_classes[$class_name])) {
			return $this->resolved_classes[$class_name];
		}

		return $this->createFreshInstance($class_name, $dependencies);
	}

	/**
	 * Register a class
	 *
	 * @param class-string $class_name The class name
	 * @param object       $instance   The instance
	 * @return void
	 */
	public function registerClass(string $class_name, object $instance): void {
		$this->registered_classes[$class_name] = $instance;
	}

	/**
	 * Resolve a fresh instance
	 *
	 * @param class-string  $class_name   The class name
	 * @param array<string> $dependencies The dependencies
	 * @return object
	 */
	public function resolveFresh(string $class_name, array $dependencies = []): object {
		return $this->createFreshInstance($class_name, $dependencies);
	}

	/**
	 * Create a fresh instance
	 *
	 * @param class-string  $class_name   The class name
	 * @param array<string> $dependencies The dependencies
	 * @return object
	 */
	protected function createFreshInstance(string $class_name, array $dependencies):object {
		$dependencies[Application::class] = $this->Application;
		$dependencies[Injector::class]    = $this;

		$reflection  = new ReflectionClass($class_name);
		$constructor = $reflection->getConstructor();

		if (empty($constructor)) {
			$instance = new $class_name();
		} else {
			$constructor_params = $constructor->getParameters();
			$constructor_args   = [];

			foreach ($constructor_params as $param) {
				$param_class = $param->getType()->getName(); // @phpstan-ignore-line
				$param_name  = $param->getName();

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