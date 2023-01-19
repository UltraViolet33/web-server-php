<?php

namespace App;

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
}
