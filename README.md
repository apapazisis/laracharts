## Laravel Ajax Charts with WebWorker

   
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/97da0999afa84e54ada46b1cabc8ee7c)](https://app.codacy.com/app/apapazisis/laracharts?utm_source=github.com&utm_medium=referral&utm_content=apapazisis/laracharts&utm_campaign=Badge_Grade_Dashboard)
<a href="https://github.styleci.io/repos/189413172"><img src="https://github.styleci.io/repos/189413172/shield?branch=master" alt="StyleCI"></a>
[![Latest Stable Version](https://poser.pugx.org/apapazisis/laravel-echarts/v/stable)](https://packagist.org/packages/apapazisis/laravel-echarts)
[![License](https://poser.pugx.org/apapazisis/laravel-echarts/license)](https://packagist.org/packages/apapazisis/laravel-echarts)


### What is Laravel Echarts?

Generate your Charts using **Laravel Echarts PHP library**. It supports the <a href="https://ecomfe.github.io/echarts-doc/public/en/tutorial.html#Get%20Started%20with%20ECharts%20in%205%20minutes" target="_blank">Echarts.js library</a>. Charts are loaded through WebWorker in the background, independently of user-interface scripts and without affecting the performance of the page.


### Documentation

1. Publish the files through 
```php
php artisan vendor:publish
Provider: Apapazisis\Echarts\EchartsServiceProvider
```

2. Add scripts in header 
```php
<script src="{{ asset('js/echarts.min.js') }}"></script> <!-- Download the Echarts library -->
<script src="{{ asset('vendor/charts/charts.js')}}"></script>
```

3. Routing
```php
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
         $chart->get()
   );
});
```

4. Blade
```php
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
```        
4. TestChart Class
```php          
 namespace App\Charts;

 use Apapazisis\Echarts\Classes\BaseChart;
 use Carbon\Carbon;

 class TestChart extends BaseChart
 {
   public $id = 'kjdshksdjfsjdfg'; 

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
                       'color' => 'gray',
                       'formatter' => 'function(params){var array = ' . json_encode($this->data['mailsentdates']) . '; return params.data + array[params.dataIndex];}'

                   ]
               ]
           ]
       ]);

       $this->dataset('Sales2', 'bar', $this->data['accessclearingdate'])->options([
           'itemStyle' => [
               'normal' => [
                   'color' => 'function (params){console.log(window.' . $this->id . 'Options);if (params.dataIndex > 0) return "yellow"; else return "blue";}',
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
           'toolbox' => [
                 'feature' => [
                     'saveAsImage' => [
                         'show' => true,
                         'title' => 'save as image',
                         'pixelRatio' => 2
                     ]
                 ]
           ],
           'animationEasing' => 'elasticOut',
           'legend' => [
               'data' => ['Sales1', 'Sales2']
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
```
