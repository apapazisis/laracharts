## Laravel Charts with WebWorker

### Documentation

     

          namespace App\Charts;

          use Apapazisis\Echarts\Classes\BaseChart;
          use Carbon\Carbon;

          class TestChart extends BaseChart
          {
            protected $date = null;

            protected $data = [];

            public function __construct()
            {
                $this->setRoute();
                parent::__construct();
            }

            public function make($filters = [])
            {
                return $this
                    ->setFilters($filters) // first we set the filters and then we can use them wherever we want in other functions
                    ->setData()
                    ->setOptions()
                    ->setLabels()
                    ->setDataset()
                    ;
            }

            public function setData()
            {
                if (isset($this->date)) $i = 25;
                else $i = 10;

                $this->data['mailsentdates'] = [$i, 20, 30];
                $this->data['accessclearingdate'] = [3, 6, 9];

                return $this;
            }

            public function setDataset()
            {
                $this->dataset('Sales1', 'bar', $this->data['mailsentdates'])->options([
                    'itemStyle' => [
                        'normal' => [
                            'color' => 'function (params){console.log(params);if (params.dataIndex > 0) return "red"; else return "green";}',
                            'barBorderColor' => 'gray',
                            'barBorderWidth' => 0,
                            'label' => [
                                'show' => true,
                                'position' => 'top',
                                'textStyle' => [
                                    'fontWeight' => 500
                                ],
                                'color' => 'gray'
                            ]
                        ]
                    ]
                ]);

                $this->dataset('Sales2', 'bar', $this->data['accessclearingdate'])->options([
                    'itemStyle' => [
                        'normal' => [
                            'color' => 'function (params){console.log(params);if (params.dataIndex > 0) return "yellow"; else return "blue";}',
                            'barBorderColor' => 'gray',
                            'barBorderWidth' => 0,
                            'label' => [
                                'show' => true,
                                'position' => 'top',
                                'textStyle' => [
                                    'fontWeight' => 500
                                ],
                                'color' => 'gray'
                            ]
                        ]
                    ]
                ]);

                return $this;
            }

            public function setOptions()
            {
                if (isset($this->date)) $data = ['apos', 'xlbl2', 'xlbl3'];
                else $data = ['xlbl1', 'xlbl2', 'xlbl3'];

                $this->options([
                    'title' => [
                        'text' => 'The title',
                        'show' => true
                    ],
                    'tooltip' => [
                        'trigger' => 'axis',
                        'axisPointer' => [
                            'type' => 'cross'
                        ]
                    ],
                    'legend' => [
                        'data' => ['lg1', 'lg2', 'lg3']
                    ],
                    'xAxis' => [
                        'type' => 'category',
                        'data' => $data
                    ],
                    'yAxis' => [
                        'type' => 'value'
                    ],
                    'grid' => [
                        'left' => '0%',
                        'right' => '0%',
                        'bottom' => '0%',
                        'containLabel' => true
                    ]
                ]);

                return $this;
            }

            public function setLabels()
            {
                $this->labels([
                    'lbl1', 'lbl2', 'lbl3', 'lbl4'
                ]);

                return $this;
            }

            public function setRoute()
            {
                $this->route = url('data');

                return $this;
            }

            public function setFilters($filters)
            {
                $this->date = isset($filters['date']) ? Carbon::parse($filters['date'])->startOfWeek() : null;

                return $this;
            }
        }
