<?php

namespace FormHelper;

class FormHelper
{
    private $prefix;

    function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function isSubmitted()
    {
        return array_key_exists($this->prefix . 'method', $_POST);
    }

    public function getMethod()
    {
        return $_POST[$this->prefix . 'method'];
    }

    public function getResourceId()
    {
        return (array_key_exists($this->prefix . 'id', $_POST)) ? $_POST[$this->prefix . 'id'] : false;
    }

    public function getQueryString()
    {
        $data = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, $this->prefix) !== false
                && $key !== $this->prefix . 'method'
                && $key !== $this->prefix . 'id'
                && $value
            ) {
                $data[str_replace($this->prefix, '', $key)] = $value;
            }
        }
        return count($data) > 0 ? http_build_query($data) : '';
    }

    public function getFormPostData()
    {
        $data = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, $this->prefix) !== false && $key !== $this->prefix . 'method') {
                $data[str_replace($this->prefix, '', $key)] = $value;
            }
        }
        return $data;
    }

    public function getFormData()
    {
        $data = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, $this->prefix . 'send_') === false
                && strpos($key, $this->prefix) !== false
                && $key !== $this->prefix . 'method'
                && array_key_exists($this->prefix . 'send_' . str_replace($this->prefix, '', $key), $_POST)
                && $_POST[$this->prefix . 'send_' . str_replace($this->prefix, '', $key)] == 'on'
            ) {
                $data[str_replace($this->prefix, '', $key)] = $value;
            }
        }
        return $data;
    }
}