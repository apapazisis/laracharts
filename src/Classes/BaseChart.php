<?php

namespace Apapazisis\Echarts\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class BaseChart
{
    public $id;

    public $class;

    public $route;

    public $datasets = [];

    protected $dataset = Dataset::class;

    public $view = 'echarts::render';

    public $options = [];

    /**
     * BaseChart constructor.
     */
    public function __construct()
    {
        $this->class = class_basename($this);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return View::make($this->view, ['chart' => $this]);
    }

    public function dataset(string $name, string $type, $data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $dataset = new $this->dataset($name, $type, $data);

        array_push($this->datasets, $dataset);

        return $dataset;
    }

    public function options($options)
    {
        if ($options instanceof Collection) {
            $options = $options->toArray();
        }

        $this->options = $options;

        return $this;
    }

    public function formatDatasets()
    {
        return Collection::make([
            'series' => Collection::make($this->datasets)
            ->map(function (Dataset $dataset) {
                return $dataset->format();
            }),
        ])
            ->merge($this->options)
            ->toArray();
    }

    public function get()
    {
        return $this->formatDatasets();
    }
}
