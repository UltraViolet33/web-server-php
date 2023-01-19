<?php

namespace App;

class Request
{

    protected string $method;
    protected string $uri;
    protected array $parameters = [];
    protected array $headers = [];


    public function __construct(string $method, string $uri, array $headers = [])
    {
        $this->headers = $headers;
        $this->method = strtoupper($method);

        list($this->uri, $params) = explode("?", $uri);

        parse_str($params, $this->parameters);
    }


    public static function withHeaderString(string $header): static
    {
        $lines = explode("\n", $header);
        list($method, $uri) = explode(' ', array_shift($lines));

        $headers = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, ": ") !== false) {

                list($key, $value) = explode(": ", $line);
                $headers[$key] = $value;
            }
        }


        return new static($method, $uri, $headers);
    }


    public function method(): string
    {
        return $this->method;
    }


    public function uri(): string
    {
        return $this->uri;
    }


    public function header(string $key, string $default = null): string
    {
        if (!isset($this->headers[$key])) {
            return $default;
        }

        return $this->headers[$key];
    }


    public function param(string $key, string  $default = null): string
    {
        if (!isset($this->parameters[$key])) {
            return $default;
        }

        return $this->parameters[$key];
    }
}
