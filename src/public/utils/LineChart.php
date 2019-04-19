<?php


/* Example Chart Call *

$chart = new LineChart();
        $chart->set_name('customer_transactions');
        $chart->set_y_name('value');
        $chart->set_y_title('Value');
        $chart->set_x_name('date');
        $chart->set_type('datetime');
        $chart->set_data([
          1=>['date'=>'2018-02-03 12:55', 'value'=>12],
          2=>['date'=>'2018-02-04 12:55', 'value'=>25]
        ]);
        $chart->draw();
*/



class LineChart{

    public $name;
    public $data;
    public $x_title;
    public $x_name;
    public $y_title;
    public $y_name;
    public $type;
    public $min_period;


    public function __construct(){
        $this->min_period = "ss";
    }


    public function set_name($name){$this->name=$name;}
    public function set_x_title($x_title){$this->x_title=$x_title;}
    public function set_y_title($y_title){$this->y_title=$y_title;}
    public function set_x_name($x_name){$this->x_name=$x_name;}
    public function set_y_name($y_name){$this->y_name=$y_name;}
    public function set_type($type){$this->type=$type;}
    public function set_data($data){$this->data=$data;}
    public function set_min_period($min_period){$this->min_period=$min_period;}




    public function data_hour_filter($my_array){
      $my_data=[];
      $counter=-1;
      $count=sizeof($my_array)-1;
      while($count>-1){
        $time=explode(':',$my_array[$count]['time']) ;
        $my_array[$count]['time']=$time[0].':00';
        $transaction=$my_array[$count];
        if($counter==-1){
          array_push($my_data,[$this->x_name=>$transaction['date'].' '.$transaction['time'], $this->y_name=>$transaction['value']]);
          $counter=$counter+1;
        }else if($my_data[$counter][$this->x_name]==$transaction['date'].' '.$transaction['time'])
          $my_data[$counter][$this->y_name]=$my_data[$counter][$this->y_name]+$transaction['value'];
        else{
          array_push($my_data,[$this->x_name=>$transaction['date'].' '.$transaction['time'], $this->y_name=>$transaction['value']]);
          $counter=$counter+1;
        }
        $count--;
      }
      return $my_data;
    }

    public function data_normal_filter($my_array){
        $my_data=[];
        foreach($my_array as $transaction){
          array_push($my_data,[$this->x_name=>$transaction['date'].' '.$transaction['time'], $this->y_name=>$transaction['value']]);
        }
        $my_data=array_reverse($my_data);
        return $my_data;
      }

      public function set_data_display_type($data_display_type){
           $my_data=[];
           foreach($this->data as $item){
             $array=explode(' ',$item[$this->x_name]);
             $transaction['date']=$array[0];
             $transaction['time']=$array[1];
             $transaction['value']=$item[$this->y_name];
             array_push($my_data,$transaction);

           }
           switch ($data_display_type){


            case 'hour':
              $this->data=$this->data_hour_filter($my_data) ;
              break;


            case 'unfiltered':
              break;


            default :
              $this->data=$this->data_normal_filter($my_data);
              break;
           }

      }



    public function draw(){
        switch($this->type){
            case 'datetime':
                $this->draw_datetime();
                break;
        }
    }



public function draw_datetime(){

?>
<style>
#<?php echo $this->name ?>_div {
  width : 100%;
  height  : 500px;
}
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/tools/datePadding/datePadding.min.js"></script>

<!-- Chart code -->
<script>

var chart = AmCharts.makeChart("<?php echo $this->name ?>_div", {
    "type": "serial",
    "theme": "light",
    "marginRight": 80,
    "mouseWheelZoomEnabled":true,
    "valueAxes": [{
        "position": "left",
        "title": "<?php echo $this->y_title; ?>"
    }],
    "graphs": [{
        "id": "g1",
        "valueField": "<?php echo $this->y_name; ?>",
        "balloonText": "<span style='font-size:17px;'>[[value]]</span>",
        "balloon":{
          "drop":true,
          "adjustBorderColor":false,
          "color":"#ffffff"
        },
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "valueScrollbar":{
      "oppositeAxis":false,
      "offset":50,
      "scrollbarHeight":10
    },
    "chartCursor": {
        "categoryBalloonDateFormat": "JJ:NN, DD MMMM",
        "cursorPosition": "mouse",
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":true
    },
    "categoryField": "<?php echo $this->x_name; ?>",
    "categoryAxis": {
        //"minPeriod": "mm",
        "parseDates": true,
        "minPeriod": "ss",
        //"prependPeriods": 0, // add 5 days start
        "appendPeriods": 1   // add 5 days to end
    },
    "export": {
        "enabled": true,
         "dateFormat": "YYYY-MM-DD HH:NN:SS"
    },
    "dataProvider": [


    /*{
        "date": "2012-07-27 07:55",
        "value": 13
    },*/

    <?php foreach($this->data as $data_key=>$data_row){
        if ( ($data_row[$this->x_name] != null && $data_row[$this->y_name] != null)
                && ($data_row[$this->x_name] != "" && $data_row[$this->y_name] != "")
                && ($data_row[$this->x_name] != 0 && $data_row[$this->y_name] != 0) ){
        echo    '{
                    "date": "'            .$data_row[$this->x_name].'",
                    "'.$this->y_name.'": '.$data_row[$this->y_name].'
                },';
    }}?>





    ]
});

chart.addListener("dataUpdated", zoomChart);
// when we apply theme, the dataUpdated event is fired even before we add listener, so
// we need to call zoomChart here
zoomChart();
// this method is called when chart is first inited as we listen for "dataUpdated" event
function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    chart.zoomToIndexes(chart.dataProvider.length - 250, chart.dataProvider.length - 100);
}

</script>

<!-- HTML -->
<div id="<?php echo $this->name ?>_div"></div>


<?php
    }


}
