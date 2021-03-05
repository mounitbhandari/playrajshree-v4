<?php
class Draw_report_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }






    function select_draw_wise_sale_report($start_date,$series_id=1){
        $result = array();
        $sql="call draw_wise_report(?,?)";
        $result = $this->db->query($sql,array($start_date,$series_id));
        return $result;
    }








}//final

?>