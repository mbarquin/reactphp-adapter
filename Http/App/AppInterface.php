<?php
/**
 * Request Interface file for an React Adapter request object
 */
namespace mbarquin\reactSlim\App;
/**
 * Contract to have a request to adapt a react request object to a new one
 */
interface AppInterface
{
    public function dryProcess($request, $response);
}
