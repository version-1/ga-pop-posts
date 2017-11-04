<?php
require_once __DIR__ . '/GA_POP_Post.php';
require_once __DIR__ . '/GA_POP_Database.php';
class GA_POP_GAReport{

    private $analytics;
    private $key_file;
    private $view_id;
    private $date_from;
    private $ga_date_range;
    private $ga_metrics = [];
    private $ga_dimension = [];
    private $ga_filters = [];
    private $ga_orderbies = [];
    private $reports;

    function __construct($key_file, $view_id , $date_from)
    {
        // Creates and returns the Analytics Reporting service object.
        $this->key_file = $key_file;
        $this->view_id = $view_id;
        $this->date_from = $date_from;

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Popular Post Analytics Reporting");
        $client->setAuthConfig($this->key_file);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->analytics = new Google_Service_AnalyticsReporting($client);

        $this->set_date_range();
        $this->set_metrics();
        $this->set_filter();
        $this->set_dimension();
        $this->set_orderby();
    }

    function getReport() {
        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->view_id);
        $request->setDateRanges($this->ga_date_range);
        $request->setMetrics($this->ga_metrics);
        $request->setDimensions($this->ga_dimentions);
        $request->setDimensionFilterClauses($this->ga_filters);
        $request->setOrderBys($this->ga_orderbies);

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request) );

        $this->reports =  $this->analytics->reports->batchGet( $body );
    }

    private function set_date_range(){
        // Create the DateRange object.
        $this->ga_date_range = new Google_Service_AnalyticsReporting_DateRange();
        $this->ga_date_range->setStartDate($this->date_from);
        $this->ga_date_range->setEndDate("today");
    }

    private function set_metrics(){
        // Create the Metrics object.
        $metric = new Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression("ga:pageviews");
        $metric->setAlias("pv");
        $this->ga_metrics[] = $metric;
    }

    private function set_filter(){
        // Filter
        $filter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $filter->setDimensionName("ga:pagePathLevel4");
        $filter->setNot(true);
        $filter->setOperator("IN_LIST");
        $filter->setExpressions( ["/", "/profile/"] );
        $this->ga_filters = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $this->ga_filters->setFilters([$filter]);
    }

    private function set_dimension(){
        // Create the Dimension object.
        $dimention = new Google_Service_AnalyticsReporting_Dimension();
        $dimention->setName("ga:pagePathLevel4");
        $this->ga_dimentions = [$dimention];
    }

    private function set_orderby(){
        // Create the DateRange object.
        $orderby = new Google_Service_AnalyticsReporting_OrderBy();
        $orderby->setFieldName("ga:pageviews");
        $orderby->setOrderType("VALUE");
        $orderby->setSortOrder("DESCENDING");
        $this->ga_orderbies[] = $orderby;
    }


    function fetchResults($display_count) {
        $posts = [];
        for ( $reportIndex = 0; $reportIndex < count($this->reports); $reportIndex++ ) {
            $report = $this->reports[ $reportIndex ];
            $rows = $report->getData()->getRows();

            for ( $rowIndex = 0; $rowIndex < $display_count; $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();

                $path_name = $dimensions[0];
                $pv = $metrics[0]->getValues()[0];
                $post = new GA_POP_Post($path_name,$pv);

                $title = GA_POP_Database::get_tilte_by_path_name($post->get_post_name());
                if ( strlen($title) > 0){
                    $post->set_title($title);
                    $posts[] = $post;
                }
            }
        }
        return $posts;
    }
}
