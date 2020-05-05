<?php

use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;

class UserPlugin extends Plugin_Abstract {

    public function routerStartup(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin routerStartup called <br/>\n";
    }

    public function routerShutdown(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin routerShutdown called <br/>\n";
    }

    public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin DispatchLoopStartup called <br/>\n";
    }

    public function preDispatch(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin PreDispatch called <br/>\n";
    }

    public function postDispatch(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin postDispatch called <br/>\n";
    }

    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response) {
        echo "Plugin DispatchLoopShutdown called <br/>\n";
    }
}
