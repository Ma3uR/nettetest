<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
        $router->addRoute('profile/<username>', 'Profile:show');
        $router->addRoute('profile/<username>', 'Profile:list');
        $router->addRoute('<presenter>/<action>', 'Admin:Dashboard:default');
        return $router;
	}
}
