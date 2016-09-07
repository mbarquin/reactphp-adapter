<?php

namespace mbarquin\reactSlim\App;

class SlimAdapter extends \Slim\App implements AppInterface
{
    public function dryProcess($request, $response)
    {
        $this->process($request, $response);
    }
}

