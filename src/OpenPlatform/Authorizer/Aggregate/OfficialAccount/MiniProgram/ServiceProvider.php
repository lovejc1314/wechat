<?php


namespace EasySwoole\WeChat\OpenPlatform\Authorizer\Aggregate\OfficialAccount\MiniProgram;

use EasySwoole\WeChat\OpenPlatform\Authorizer\Aggregate\OfficialAccount\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app[Application::MiniProgram] = function ($app) {
            return new Client($app);
        };
    }
}
