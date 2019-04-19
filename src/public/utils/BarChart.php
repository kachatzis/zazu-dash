<?php




class BarChart{

    public $name;
    public $data;
    public $x_title;
    public $x_name;
    public $y_title;
    public $y_name;
    public $type;
    public $min_period;


    public function __construct(){
        $this->min_period = 'hh';
    }


    public function set_name($name){$this->name=$name;}
    public function set_x_title($x_title){$this->x_title=$x_title;}
    public function set_y_title($y_title){$this->y_title=$y_title;}
    public function set_x_name($x_name){$this->x_name=$x_name;}
    public function set_y_name($y_name){$this->y_name=$y_name;}
    public function set_type($type){$this->type=$type;}
    public function set_data($data){$this->data=$data;}
    public function set_min_period($min_period){$this->min_period=$min_period;}


    public function draw(){
        /*switch($this->type){
            case 'datetime':
                $this->draw_datetime();
                break;
        }*/
        $this->draw_categories();
    }



public function draw_categories(){
 
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

<!-- Chart code -->
<script>

var chart = AmCharts.makeChart("<?php echo $this->name ?>_div", {
    "theme": "light",
    "type": "serial",
	"startDuration": 2,
    "dataProvider": [

    	<?php foreach($this->data as $data_key=>$data_row){
        echo    '{ "'.$this->x_name.'": "'.$data_row[$this->x_name].'",'.
                    '"'.$this->y_name.'": '.$data_row[$this->y_name].''.
                '},';
    	} ?>

    ],
    "valueAxes": [{
        "position": "left",
        "title": "<?php echo $this->y_title ?>"
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "fillColorsField": "color",
        "fillAlphas": 1,
        "lineAlpha": 0.1,
        "type": "column",
        "valueField": "<?php echo $this->y_name ?>"
    }],
    "depth3D": 20,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "<?php echo $this->x_name ?>",
    "categoryAxis": {
        "gridPosition": "start",
        "labelRotation": 90
    },
    "export": {
    	"enabled": true
     }

 });

</script>

<!-- HTML -->
<div id="<?php echo $this->name ?>_div"></div> 


<?php
    }


}