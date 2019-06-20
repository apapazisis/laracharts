<?php

namespace Apapazisis\Echarts\Classes;

use Illuminate\Support\Collection;

class Dataset
{
    public $name = 'Undefined';

    public $type = '';

    public $data = [];

    public $options = [];

    public function __construct(string $name, string $type, array $data)
    {
        $this->name = $name;
        $this->type = $type;
        $this->data = $data;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function data($data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $this->data = $data;

        return $this;
    }

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function format()
    {
        return array_merge($this->options, [
            'data' => $this->data,
            'name' => $this->name,
            'type' => strtolower($this->type),
        ]);
    }
}
