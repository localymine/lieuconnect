<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Dashboard" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
Common::register_js('https://www.google.com/jsapi', CClientScript::POS_HEAD);

Common::register('jquery.flot.js', 'pro/flot', CClientScript::POS_HEAD);
Common::register('jquery.flot.pie.js', 'pro/flot', CClientScript::POS_HEAD);
?>

<div class="content-header content-header-media">
    <div class="header-section">
        <div class="row">
            <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                <h1>Dash<strong>board</strong><br /><small>Overview!</small></h1>
            </div>
            <div class="col-md-8 col-lg-6">
                <div class="row text-center">
                    <?php $last = isset($results) ? count($results) - 1 : 0; ?>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? number_format($results[$last]->getPageviews()) : '0' ?></strong><br />
                            <small><i class="fa fa-thumbs-o-up"></i> Pageviews</small>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? number_format($results[$last]->getUniquepageviews()) : '0' ?></strong><br />
                            <small><i class="fa fa-heart-o"></i> Unique pageviews</small>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? Common::second_minute($results[$last]->getAvgtimeonpage()) : '0' ?></strong><br />
                            <small><i class="fa fa-calendar-o"></i> Avg time on page</small>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? round($results[$last]->getEntrancebouncerate(), 2) : '0' ?>%</strong><br />
                            <small><i class="fa fa-calendar-o"></i> Bounce rate</small>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? round($results[$last]->getExitrate(), 2) : '0' ?>%</strong><br />
                            <small><i class="fa fa-calendar-o"></i> Exit rate</small>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-3">
                        <h2 class="animation-hatch">
                            <strong><?php echo isset($results) ? $results[$last]->getNewVisits() : '0' ?></strong><br />
                            <small><i class="fa fa-bolt"></i> New Visits</small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <img src="<?php echo Yii::app()->theme->baseUrl ?>/img/placeholders/headers/dashboard_header.jpg" alt="header image" class="animation-pulseSlow" />
</div>

<div class="row">
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="<?php echo Yii::app()->createUrl('backend/post/winner') ?>" class="widget-icon pull-left themed-background-fancy animation-fadeIn">
                    <i class="hi hi-star-empty"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>Winner</strong>
                    <small><?php echo isset($summary->winner) ? $summary->winner : '0' ?> Published</small>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="<?php echo Yii::app()->createUrl('backend/post/story') ?>" class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                    <i class="gi gi-notes_2"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>Stories</strong><br />
                    <small><?php echo isset($summary->story) ? $summary->story : '0' ?> Published</small>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="<?php echo Yii::app()->createUrl('backend/post/scholarship') ?>" class="widget-icon pull-left themed-background-spring animation-fadeIn">
                    <i class="gi gi-cup"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>Scholarship</strong><br />
                    <small><?php echo isset($summary->scholarship) ? $summary->scholarship : '0' ?> Published</small>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="<?php echo Yii::app()->createUrl('backend/post/internship') ?>" class="widget-icon pull-left themed-background-fire animation-fadeIn">
                    <i class="gi gi-group"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>Internship</strong>
                    <small><?php echo isset($summary->internship) ? $summary->internship : '0' ?> Published</small>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="<?php echo Yii::app()->createUrl('backend/post/college') ?>" class="widget-icon pull-left themed-background-amethyst animation-fadeIn">
                    <i class="gi gi-cargo"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>College</strong>
                    <small><?php echo isset($summary->college) ? $summary->college : '0' ?> Published</small>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2">
        <div class="widget">
            <div class="widget-simple">
                <a href="#" class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-cogwheel"></i>
                </a>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>...</strong>
                    <small>...</small>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="block full">
    <div class="block-title"><h2><strong>Overview</strong></h2></div>
    <div id="chart" class="chart" style="overflow: auto;overflow-y: hidden;"></div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="block full">
            <div class="block-title"><h2><strong>Chart Test</strong></h2></div>
            <div id="chart-pie" class="chart"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Day');
        data.addColumn('number', 'Pageviews');

        data.addRows([<?php
                    foreach ($chart as $result) {
                        echo '["' . date('M j', strtotime($result->getDate())) . '", ' . $result->getPageviews() . '],';
                    }
                    ?>
        ]);

        var chart = new google.visualization.AreaChart(document.getElementById('chart'));
        chart.draw(data, {width: 1065, height: 360, title: '<?php echo date('M j, Y', strtotime('-30 day')) . ' - ' . date('M j, Y'); ?>',
            colors: ['#058dc7', '#e6f4fa'],
            areaOpacity: 0.1,
            hAxis: {textPosition: 'in', showTextEvery: 5, slantedText: false, textStyle: {color: '#058dc7', fontSize: 10}},
            pointSize: 5,
            legend: 'none',
            chartArea: {left: 0, top: 30, width: "100%", height: "100%"}
        });
    }

    //
    function labelFormatter(label, series) {
        return '<div class="chart-pie-label">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
    }
    var chart_pie = $('#chart-pie');
    var data = [
        {label: "Support", data: 20, color: "#333333"}, 
        {label: "Earnings", data: 45, color: "#1abc9c"}, 
        {label: "Sales", data: 35, color: "#16a085"}
    ];
    var options = {
        series: {
            pie: {
                show: true, 
                radius: 1, 
                label: {
                    show: true,
                    formatter: labelFormatter,
                    radius: .75,
                    background: {
                        opacity: 0.5,
                        color: '#000'
                    }
                }
            }
        },
        legend: {
            show: false
        }
    };
    $.plot(chart_pie, data, options);

</script>