
<?php
require_once("database_config.php");
require_once("database.php");
class ShowJsonData
{
    public function convert_sql_rows_to_json($sql_rows)
    {
        $json_result = array();
        while ($res = mysqli_fetch_array($sql_rows)) {
            // print_r($res);
            // exit;
            array_push($json_result, array(
                "title" => $res['title'],
                "url" => $res['url'],
                "description" => $res['description']
            ));
        } //close while
        $json_data = json_encode(array(
            "result" => $json_result
        ));
        return $json_data;
    }
    public function convert_arrays_to_json($alll_info)
    {
        $json_result = array();
        foreach ($alll_info as $res) {
            array_push($json_result, array(
                "title" => $res['0'],
                "url" => $res['1'],
                "description" => $res['2']
            ));
        } //close for each
        $json_data = json_encode(array(
            "result" => $json_result
        ));
        return $json_data;
    } //end convet_to_json()
    public function display_results($new_json_data)
    {
        //global $json_data;
        //exit;
        $temp              = explode('result', $new_json_data);
        $title_array       = explode('title', $temp[1]);
        $description_array = explode('description', $temp[1]);
        $new_arry          = explode('{', $temp[1]);
        for ($i = 1; $i < count($title_array); $i++) {
            echo "<br><br> <br>";
            $title_array_1 = explode(',', $title_array[$i]);
            //working for title:
            $title         = $title_array_1[0];
            $title         = str_replace(":", "", $title);
            $title         = str_replace('"', "", $title);
            $title         = str_replace('[', "", $title);
            $title         = str_replace(']', "", $title);
            // $title = preg_match('/^/', subject);
            echo "<br><h2> {$title} </h2><br>";
            //working for url:
            $url = $title_array_1[1];
            $url = str_replace('"', "", $url);
            $url = str_replace("\\", "", $url);
            $url = str_replace("url:", "", $url);
            echo "<br> <a href = {$url}>URL: </a> {$url}<br> ";
            //working for description
            $new_desc    = explode('description', $new_arry[$i]);
            $description = $new_desc[1];
            $description = str_replace('"', "", $description);
            $description = str_replace(':', "", $description);
            $description = str_replace('},', "", $description);
            $description = str_replace('[', "", $description);
            $description = str_replace(']', "", $description);
            //<\/b>
            $description = str_replace('<\/b>', "", $description);
            //<\/span>
            $description = str_replace('<\/span>', "", $description);
            echo "<br> <h4>{$description} </h4><br>";
        } //close for loop
    } //close display_results()
} //close class
?>