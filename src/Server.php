<?php

namespace App;

use Request;
use Response;
use Exception;
use Socket;

class Server
{
    protected string $host;
    protected int $port;
    protected Socket $socket;


    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->connect();
    }


    protected function connect(): void
    {
        $this->createSocket();
        $this->bindSocket();
    }


    protected function createSocket(): void
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }


    protected function bindSocket(): void
    {
        if (!socket_bind($this->socket, $this->host, $this->port)) {
            throw new \Exception('Could not bind: ' . $this->host . ':' . $this->port . ' - ' . socket_strerror(socket_last_error()));
        }
    }

    public function listen(callable $callback): void
    {
        // check if the callback is valid
        if (!is_callable($callback)) {
            throw new Exception("The argument should be callable");
        }


        while (true) {
            //listen for connections
            socket_listen($this->socket);


            if (!$client = socket_accept($this->socket)) {
                socket_close($client);
                continue;
            }


            $request = Request::withHeaderString(socket_read($client, 1024));

            $response = call_user_func($callback, $request);


            if (!$response || !$response instanceof Response) {
                $response = Response::error(404);
            }

            $response = (string) $response;

            socket_write($client, $response, strlen($response));

            socket_close($client);
        }
    }
}
