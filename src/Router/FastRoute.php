<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 28.09.2014
 * Time: 22:28
 */

namespace Bonefish\Router;


class FastRoute extends AbstractRouter
{

    public function route()
    {
        $routes = $this->environment->getFullCachePath() . '/route.cache';

        if (!file_exists($routes)) {
            throw new \InvalidArgumentException('No routes found please use Bonefish Core generateRoutes');
        }

        $dispatcher = \FastRoute\cachedDispatcher(function (){}, [
            'cacheFile' => $routes
        ]);

        $data = $dispatcher->dispatch('GET', '/' . urldecode($this->url->getPath()));
        switch ($data[0]) {
            case \FastRoute\Dispatcher::FOUND:
                /** @var DTO $dto */
                $dto = unserialize($data[1]);
                $package = $this->environment->createPackage($dto->getVendor(), $dto->getPackage());
                $this->environment->setPackage($package);
                $controller = $this->container->get($dto->getController());
                $this->parameter = $data[2];
                $this->callControllerAction($dto->getAction().'Action',$controller);
                break;
            default:
                throw new \Exception('No route found!');
                break;
        }
    }
} 