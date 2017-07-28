<?php
ini_set('max_execution_time', 240);
define("TITLE", "h3 class=\".r\"");
require_once("html_parser.php");
require_once("simple_html_dom.php");
$titlesarray             = array();
$descriptionarray        = array();
$linksarray              = array();
$count                   = 0;
$sorted_links_array      = array();
$title_description_array = array();
$total_titles;
$page_number   = 0;
$count_tracker = 0;
$html_all;
//Main class GoogleInfoManager
$htmlparser = new HtmlParser();
class GooglePuller
{
    public $user_search_query;
    //public $html;
    public function __construct($user_search_new)
    {
        $this->user_search_query = "$user_search_new";
    }
    function get_html($page_no)
    {
        global $html_all;
        global $user_search;
        $user_search_new = str_replace(" ", "+", $user_search);
        $url             = "https://www.google.co.in/search?q={$user_search_new}&start={$page_no}";
        $ch              = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        $query = curl_exec($ch);
        curl_close($ch);
        //  echo $this->html = str_get_html($query);
        //  $html_all .= str_get_html($query);
        return $this->html = str_get_html($query);
        // return $html_all;
    } //end curl to php 
    //parse html to get title,url and description
    public function parse_html($html)
    {
        global $htmlparser;
        global $showjsondata;
        global $savetodatabase;
        global $accessdatabase;
        global $titlesarray;
        $titlesarray = array();
        global $linksarray;
        $linksarray = array();
        global $descriptionarray;
        $descriptionarray = array();
        global $sorted_links_array;
        $sorted_links_array = array();
        global $title_description_array;
        $title_description_array = array();
        global $linksarray;
        global $titlesarray;
        $descriptionarray        = $htmlparser->fetch_all_descriptions($html, ".st");
        $total_descriptions      = count($descriptionarray);
        $titlesarray             = $htmlparser->fetch_all_titles($html, TITLE);
        $linksarray              = $htmlparser->get_all_links($html, "a");
        $all_availabel_links     = count($linksarray);
        $total_titles            = count($titlesarray);
        $sorted_links_array      = $htmlparser->save_useful_links($total_titles, $all_availabel_links);
        $total_links             = count($sorted_links_array);
        $title_description_array = $htmlparser->save_all_info();
        //  insert all data in database table
        $accessdatabase->save_to_database();
    }
    //starting point of the program
    public function start()
    {
        global $page_number;
        global $title_description_array;
        global $showjsondata;
        global $html_all;
        for ($x = 0; $x <= 3; $x++) {
            $html = $this->get_html($page_number);
            $this->parse_html($html);
            $json_data_google = $showjsondata->convert_arrays_to_json($title_description_array);
            $showjsondata->display_results($json_data_google);
            $page_number = $page_number + 10;
        } //end of for loop
        $mysqldatabase->close_connection();
     } //start() ends
}
?> 