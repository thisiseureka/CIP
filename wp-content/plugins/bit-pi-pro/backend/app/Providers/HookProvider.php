<?php

namespace BitApps\PiPro\Providers;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Config as FreePluginConfig;
use BitApps\PiPro\Config;
use BitApps\PiPro\Deps\BitApps\WPKit\Hooks\Hooks;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\RequestType;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Router;
use BitApps\PiPro\Plugin;
use BitApps\PiPro\src\MixInputFunctions\FunctionExecutor;
use BitApps\PiPro\src\SystemVariables;
use BitApps\PiPro\src\Tools\Delay\DelayTool;
use FilesystemIterator;

class HookProvider
{
    private $_pluginBackend;

    public function __construct()
    {
        $this->_pluginBackend = Config::get('BASEDIR') . DIRECTORY_SEPARATOR;
        $this->loadIntegrationAjaxRoute();

        $this->loadIntegrationAjaxRoute();
        $this->loadAppAjaxHooks();
        Hooks::addFilter(FreePluginConfig::VAR_PREFIX . 'mix_tag_input', [$this, 'proMixInputHandler'], 10, 2);
        Hooks::addAction(FreePluginConfig::VAR_PREFIX . 'run_scheduled_flow_node', [DelayTool::class, 'runScheduledFlowNode'], 10, 2);

        Hooks::addAction('rest_api_init', [$this, 'loadAppApiHooks']);
    }

    public function proMixInputHandler($values, $item)
    {
        switch ($item['type']) {
            case 'function':
                return FunctionExecutor::parseAndExecuteTree($item);

            case 'common-variable':
                return SystemVariables::getSystemVariableValue($item['slug']);

            default:
                return '';
        }
    }

    public function loadAppApiHooks()
    {
        if (
            is_readable($this->_pluginBackend . 'hooks' . DIRECTORY_SEPARATOR . 'api.php')
            && RequestType::is(RequestType::API)
        ) {
            $router = new Router(RequestType::API, FreePluginConfig::SLUG, 'v1');

            include $this->_pluginBackend . 'hooks' . DIRECTORY_SEPARATOR . 'api.php';
            $router->register();
        }
    }

    protected function loadAppAjaxHooks()
    {
        if (
            RequestType::is(RequestType::AJAX)
            && is_readable($this->_pluginBackend . 'hooks' . DIRECTORY_SEPARATOR . 'ajax.php')
        ) {
            $router = new Router(RequestType::AJAX, Config::VAR_PREFIX, '');
            $router->setMiddlewares(Plugin::instance()->middlewares());

            include $this->_pluginBackend . 'hooks' . DIRECTORY_SEPARATOR . 'ajax.php';
            $router->register();
        }

        if (is_readable($this->_pluginBackend . 'hooks.php')) {
            include $this->_pluginBackend . 'hooks.php';
        }
    }

    protected function loadIntegrationAjaxRoute()
    {
        $taskDir = $this->_pluginBackend . 'app' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Integrations';

        $dirs = new FilesystemIterator($taskDir);

        foreach ($dirs as $dirInfo) {
            if ($dirInfo->isDir()) {
                $taskName = basename($dirInfo);
                $taskPath = $taskDir . DIRECTORY_SEPARATOR . $taskName . DIRECTORY_SEPARATOR;
                if (is_readable($taskPath . 'Routes.php') && RequestType::is('ajax') && RequestType::is('admin')) {
                    $router = new Router(RequestType::AJAX, Config::VAR_PREFIX, '');
                    $router->setMiddlewares(Plugin::instance()->middlewares());

                    include $taskPath . 'Routes.php';
                    $router->register();
                }
            }
        }
    }
}
