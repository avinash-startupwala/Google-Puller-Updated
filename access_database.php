<?php
require_once("database_config.php");
require_once("database.php");
require_once("json_data.php");
$showjsondata = new ShowJsonData();
class AccessDatabase
{
    //insert data to database
    public function save_to_database()
    {
        global $user_search;
        global $mysqldatabase;
        global $total_titles;
        global $title_description_array;
        //global $user_search_query;
        for ($i = 0, $j = 1; $i < $total_titles; $i++, $j++) {
            //for titles
            $temp_title         = $title_description_array[$i][0][0];
            $save_title_db      = $mysqldatabase->escape_value($temp_title);
            //for links:
            $save_link_db       = $title_description_array[$i][1];
            //for descriptions
            $temp_description   = $title_description_array[$i][2][0];
            $save_descriptions  = $mysqldatabase->escape_value($temp_description);
            $insertdata         = "INSERT INTO googleresults (search_query,title,url,description) 
							 values
							 
							  ('$user_search','$save_title_db','$save_link_db','$save_descriptions')";
            $insertintodatabase = $mysqldatabase->query($insertdata);
        } //for loop end
    } //save to databse() end
    public function check_database($search_query)
    {
        global $mysqldatabase;
        //   $mysqldatabase->open_connection();
        $show_data_query = "SELECT title,url,description FROM googleresults WHERE search_query ='$search_query'";
        $show_data       = $mysqldatabase->query($show_data_query);
        $rowcount        = $mysqldatabase->num_rows($show_data);
        $ret_value       = 0;
        if ($rowcount > 0) {
            $return_info = array(
                $show_data,
                1
            );
            return $return_info;
        } //close if
    } //function check_database
} //class AccessDatabase ends
?>