<?php
/**
 * Description of TBCRegion
 * @author Administrator
 */
class TBCRegion {

    public $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getRegionList($region_code) {
       
        $prefix_region_code = substr($region_code, 0, 2);
        $prefix_region_code_like = $prefix_region_code . '____';
        $db = DB::getInstance();
        $sql = "SELECT CONCAT(region_code, ':' , region_name ) as region  "
                . " FROM t_region "
                . " WHERE region_code LIKE '" . $prefix_region_code_like . "' AND  region_code !='" . $region_code . "'"
                . " ORDER BY region_code";
       // echo $sql;
        $db->query($sql);
        $results = $db->results();
        $return_str = '';
        foreach ($results as $city) {
            $return_str .= $city['region'] . ";";
        }
        return  $return_str;
    }

}
