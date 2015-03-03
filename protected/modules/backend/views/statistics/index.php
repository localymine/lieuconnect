<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Statistics" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
//Common::register_js('https://www.google.com/jsapi', CClientScript::POS_HEAD);

Common::register('jsapi.js', 'pro', CClientScript::POS_HEAD);
?>

<div class="content-header content-header themed-background-dark-fancy">
    <div class="header-section">
        <div class="row">
            <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                <h1 class="themed-color-fire"><strong>Statistics</strong><br /><small>Report Overview!</small></h1>
            </div>
            <div class="col-md-8 col-lg-6">
                <form method="POST">
                    <div class="row text-center">
                        <div class="col-xs-10 col-sm-8">
                            <div class="input-group input-daterange" data-date-format="yyyy-mm-dd">
                                <input type="text" id="recruit_startDate" name="startDate" class="form-control text-center" placeholder="From" value="<?php echo $startDate ?>" />
                                <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                <input type="text" id="recruit_endDate" name="endDate" class="form-control text-center" placeholder="To" value="<?php echo $endDate ?>" />
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-4">
                            <button class="btn btn-info">View</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--<img src="<?php echo Yii::app()->theme->baseUrl ?>/img/placeholders/headers/profile_header_black.jpg" alt="header image" class="animation-pulseSlow" />-->
</div>

<div class="block full">
    <div class="block-title"><h2><strong>Overview User / Non-User Use Search Function</strong></h2></div>
    <div id="chart-user" class="chart" style="overflow: auto;overflow-y: hidden;"></div>
</div>

<div class="block full">
    <div class="block-title"><h2><strong>Overview Kind of User Use Search Function</strong></h2></div>
    <div id="chart-kind-of-student" class="chart" style="overflow: auto;overflow-y: hidden;"></div>
</div>

<div class="block full">
    <div class="block-title"><h2><strong>Overview Use Search Function</strong></h2></div>
    <div class="row">
        <div class="col-md-8">
            <div id="chart" class="chart" style="overflow: auto;overflow-y: hidden;"></div>
        </div>
        <div class="col-md-4">
            <div id="chart-2" class="chart" style="overflow: auto;overflow-y: hidden;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    google.setOnLoadCallback(drawChart2);
    google.setOnLoadCallback(drawChartStudent);
    google.setOnLoadCallback(drawChartSumary);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'all', 'college', 'internship', 'scholarship'],
        <?php if(isset($data_use_search_function)): ?>
        <?php foreach ($data_use_search_function as $key => $value): ?>
            <?php
            $t1 = $value['all'][0];
            $t2 = $value['college'][0];
            $t3 = $value['internship'][0];
            $t4 = $value['scholarship'][0];
            echo "['$key', $t1, $t2, $t3, $t4],";
            ?>
        <?php endforeach; ?>
        <?php endif; ?>
        ]);

        var options = {
            title: '<?php echo $from . ' ~ ' . $to ?>',
            curveType: 'function',
            legend: {position: 'bottom'},
            chartArea: {left:0,top:20,width:'100%',height:'75%'},
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
    
    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Kind of Search', '-'],
        <?php if(isset($data_use_search_function_2)): ?>
        <?php foreach ($data_use_search_function_2 as $key => $value): ?>
            <?php
            $key = $value['kind_of_search'];
            $t1 = $value['total'];
            echo "['$key', $t1],";
            ?>
        <?php endforeach; ?>
        <?php endif; ?>
        ]);

        var options = {
            title: '<?php echo $from . ' ~ ' . $to ?>',
            legend: 'none',
            chartArea: {left:6,top:10,width:'95%',height:'100%'},
//            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart-2'));
        chart.draw(data, options);
    }

    function drawChartStudent() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'high-school', 'college'],
            <?php if(isset($data_use_search_function_of_student)): ?>
            <?php foreach ($data_use_search_function_of_student as $key => $value): ?>
                <?php
                $t1 = $value['high-school'][0];
                $t2 = $value['college'][0];
                echo "['$key', $t1, $t2],";
                ?>
            <?php endforeach; ?>
            <?php endif; ?>
        ]);

        var options = {
            title: '<?php echo $from . ' ~ ' . $to ?>',
            curveType: 'function',
            legend: {position: 'bottom'},
            chartArea: {left:0,top:20,width:'100%',height:'75%'},
        };

        var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart-kind-of-student'));
        chart.draw(data, options);
    }

    function drawChartSumary() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'user', 'non-user'],
            <?php if(isset($data_use_search_function_user)): ?>
            <?php foreach ($data_use_search_function_user as $key => $value): ?>
                <?php
                $t1 = $value['user'][0];
                $t2 = $value['non-user'][0];
                echo "['$key', $t1, $t2],";
                ?>
            <?php endforeach; ?>
            <?php endif; ?>
        ]);
        
        var options = {
            title: '<?php echo $from . ' ~ ' . $to ?>',
//            hAxis: {title: 'user / non-user', titleTextStyle: {color: 'red'}},
            legend: {position: 'bottom'},
            chartArea: {left:0,top:20,width:'100%',height:'75%'},
        };

        var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart-user'));
        chart.draw(data, options);
    }

</script>