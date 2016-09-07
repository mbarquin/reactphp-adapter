<?php
namespace mbarquin\reactSlim;

class Server
{
    /**
     * Reference to a request adapter
     *
     * @var RequestInterface
     */
    private $requestAdapter = null;


    /**
     * Reference to a response adapter
     *
     * @var ResponseInterface
     */
    private $responseAdapter = null;

    /**
     * Sets which port will be listened
     *
     * @var int
     */
    private $port = 1337;

    /**
     * Public constructor, sets request adapter if defined
     */
    public function __construct(RequestInterface $request = null, ResponseInterface $response = null)
    {
        if ($request !== null) {
           $this->withRequestAdapter($request);
        }

        if ($response !== null) {
           $this->withResponseAdapter($response);
        }

        return $this;
    }

    /**
     * Sets the request adapter in use
     *
     * @param \mbarquin\reactSlim\RequestInterface $request
     */
    public function withRequestAdapter(Request\RequestInterface $request) {
        $this->requestAdapter = $request;

        return $this;
    }


    /**
     * Sets the request adapter in use
     *
     * @param \mbarquin\reactSlim\RequestInterface $request
     */
    public function withResponseAdapter(Response\ResponseInterface $response) {
        $this->responseAdapter = $response;

        return $this;
    }

    /**
     * Sets the listened port
     *
     * @param type $port
     *
     * @return \mbarquin\reactSlim\Server
     */
    public function withPort($port)
    {
        if (is_int($port) === true) {
            $this->port = $port;
        }

        return $this;
    }

    private function getCallbacks($app)
    {
        $server = $this;
        return function (
               \React\Http\Request $request,
               \React\Http\Response $response) use ($app, $server){

            $slRequest  = $server->requestAdapter->createFromReactRequest($request);
            $slResponse = $this->responseAdapter->getResponse();

            $app->dryProcess($slRequest, $slResponse);

            $this->responseAdapter->setReactResponse($response, $slResponse, true);
        };
    }

    /**
     * Checks Adapters and runs the server with the app
     */
    public function run(\mbarquin\reactSlim\App\AppInterface $app)
    {
        $this->setAdapters();
        $serverCallback = $this->getCallbacks($app);

        // We make the setup of ReactPHP.
        $loop           = \React\EventLoop\Factory::create();
        $socket         = new \React\Socket\Server($loop);
        $http           = new \React\Http\Server($socket, $loop);

        // Ligamos la closure al evento request.
        $http->on('request', $serverCallback);

        echo "Server running at http://127.0.0.1:1337\n";

        $socket->listen($this->port);
        $loop->run();
    }

    /**
     * Sets default slim adapters
     */
    private function setAdapters()
    {
        if ($this->requestAdapter === null) {
            $this->withRequestAdapter(new Request\SlimRequest());
        }

        if ($this->responseAdapter === null) {
            $this->withResponseAdapter(new Response\SlimResponse());
        }

    }
}

