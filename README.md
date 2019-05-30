## Laravel Charts with WebWorker

### Documentation

1. Publish the files through php artisan vendor:public

2. Add scripts in header 
          
          <script src="{{ asset('js/echarts.min.js') }}"></script>
          <script src="{{ asset('vendor/charts/charts.js')}}"></script>

3. Routing

          Route::get('/', function ()
          {
              $testChart = new \App\Charts\TestChart();

              $testChart2 = new \App\Charts\Test2Chart();

              return view('welcome', compact('testChart', 'testChart2'));
          });


          Route::post('/data', function (\Illuminate\Http\Request $request)
          {
              $class = 'App\\Charts\\' . $request->get('chartClass');
              $chart = (new $class)->make($request->except('chartClass'));

              return response()->json(
                  $chart
              );
          });

4. Blade

          <body>
              <div class="flex-center position-ref full-height">
                  <div class="content">
                      {!! $testChart->render() !!}
                      {!! $testChart2->render() !!}
                      1o <input type="text" onchange="{{ $testChart->id }}CreateOrUpdateChart({'date': '2019-01-01'})">
                      2o <input type="text" onchange="{{ $testChart2->id }}CreateOrUpdateChart()">
                  </div>
              </div>
          </body>
              
4. TestChart Class
          
          namespace App\Charts;

          use Apapazisis\Echarts\Classes\BaseChart;
          use Carbon\Carbon;

          class TestChart extends BaseChart
          {
            protected $date = null; // it is used for filtering

            protected $data = [];

            public function __construct()
            {
                $this->setRoute(); // Set the route 
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
                if (isset($this->date)){
                    $i = 25;
                } else {
                    $i = 10;
                }
                
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
                if (isset($this->date)) {
                    $data = ['apos', 'xlbl2', 'xlbl3'];
                 } else {
                    $data = ['xlbl1', 'xlbl2', 'xlbl3'];
                }
                
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
