<?php

namespace ApiClient;

use Httpful\Mime;
use Httpful\Request;
use Symfony\Component\Yaml\Yaml;

class ApiClient
{
    private $uri;
    private $username;
    private $password;

    private $resource;
    private $resource_id;

    private $method;
    private $query;
    private $data;
    private $resources;

    function __construct($resource = 'users')
    {
        $api = Yaml::parse((file_get_contents('config/api.yml')));

        $this->resources = $api['resources'];
        if (!$this->isResourceAvailable($resource)) {
            throw new \Exception('Resource not available.');
        }
        $this->resource = $resource;
        $this->uri = $api['uri'];
        $this->username = $api['credentials']['user'];
        $this->password = $api['credentials']['pw'];
        $this->methods = $api['resources'][$this->resource]['methods'];
    }

    protected function isResourceAvailable($resource)
    {
        return in_array($resource, array_keys($this->resources));
    }

    public function setMethod($method)
    {
        if (!$this->isMethodAvailable($method)) {
            throw new \Exception('Method not supported.');
        }
        $this->method = $method;
    }

    protected function isMethodAvailable($method)
    {
        return in_array($method, $this->methods);
    }

    public function setResourceId($id)
    {
        $this->resource_id = $id ? $id : null;
    }

    public function setQuery($query)
    {
        $this->query = $query ? $query : null;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function send()
    {
        if (method_exists($this, $this->method)) {
            $request = $this->{$this->method}();
            return $this->sendRequest($request);
        }
        throw new \Exception('Method not supported.');
    }

    protected function get()
    {
        $uri = $this->getUri();
        if (!$this->resource_id && $this->query != '') {
            $uri .= '?' . $this->query;
        }
        return Request::get($uri);
    }

    protected function post()
    {
        return Request::post($this->getUri())->body($this->data)->sendsType(Mime::FORM);
    }

    protected function put()
    {
        return Request::put($this->getUri())->body($this->data)->sendsType(Mime::FORM);
    }

    protected function delete()
    {
        return Request::delete($this->getUri());
    }

    protected function getUri()
    {
        return $this->uri
            . $this->resource . '/'
            . ($this->resource_id ? $this->resource_id : '');
    }

    protected function sendRequest(Request $request)
    {
        return $request->authenticateWith($this->username, $this->password)->send();
    }
}