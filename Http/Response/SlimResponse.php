<?php
namespace mbarquin\reactSlim\Response;

class SlimResponse implements ResponseInterface
{
    public function setReactResponse(\React\Http\Response $reactResp, \Slim\Http\Response $slimResponse, $endRequest = false)
    {
        $reactResp->writeHead(
                $slimResponse->getStatusCode(), $slimResponse->getHeaders()
            );

        $reactResp->write($slimResponse->getBody());

        if ($endRequest === true) {
            $reactResp->end();
        }
    }

    public function getResponse() {
        return new \Slim\Http\Response();
    }
}