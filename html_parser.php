<?php
//echo "html_parser.php<br>";
require_once("simple_html_dom.php");
class HtmlParser
{
    // get description
    public function fetch_all_descriptions($html, $element)
    {
        global $descriptionarray;
        foreach ($html->find($element) as $tag) {
            $descriptionarray[] = array(
                $tag->innertext
            );
        }
        //print_r($descriptionarray);
        return $descriptionarray;
    } //end of fetch_all_descriptions();
    //get titles
    public function fetch_all_titles($html, $element)
    {
        global $total_titles;
        global $titlesarray;
        global $count_tracker;
        foreach ($html->find($element) as $tag) {
            // to print titles with link attached on them use :$titlesarray[] = array($tag->innertext);
            //if title is about word or dictionary meaning then continue..
            $title_text = explode('/', $tag->plaintext);
            if (isset($title_text[1]) && $count_tracker == 0) {
                continue;
            }
            $count_tracker++;
            $titlesarray[] = array(
                $tag->plaintext
            );
            $total_titles  = count($titlesarray);
        } //for each ends
        //to escape forword slash in pronounciation result of google:
        $count_tracker++;
        return $titlesarray;
    } //fetch all titles ends
    // get all links from google page 
    public function get_all_links($html, $element)
    {
        global $linksarray;
        foreach ($html->find($element) as $tag) {
            $linksarray[] = array(
                $tag->href,
                $tag->plaintext
            );
        } //for each loop ends
        return $linksarray;
    } //get_all_links() complete
    // save useful links functin
    public function save_useful_links($totaltitles, $totallinks)
    {
        global $titlesarray;
        global $linksarray;
        global $sorted_links_array;
        for ($title_at = 0; $title_at < $totaltitles; $title_at++) {
            global $count;
            global $sorted_links_array;
            for ($link_at = 0; $link_at < $totallinks; $link_at++) {
                $title_to_match      = $titlesarray[$title_at][0];
                $link_title_to_match = $linksarray[$link_at][1];
                if ($title_to_match == $link_title_to_match) {
                    $formated_link = $linksarray[$link_at][0];
                    $new_url       = str_replace("/url?q=h", "h", $formated_link);
                    $temp_url_2    = explode('sa', $new_url);
                    if (isset($temp_url_2[0])) {
                        unset($temp_url_2[1]);
                        $t                    = $temp_url_2[0];
                        $fully_formed_url     = str_replace("&", "", $t);
                        $fully_formed_url_2   = str_replace("amp;", " ", $fully_formed_url);
                        $sorted_links_array[] = $fully_formed_url_2;
                    }
                }
            }
        }
        return $sorted_links_array;
    }
    //save all info (title+descriptipn+URL) i.e title,URLs and description together in array
    public function save_all_info()
    {
        global $title_description_array;
        global $descriptionarray;
        global $titlesarray;
        global $sorted_links_array;
        $i = 0;
        foreach ($descriptionarray as $temp[$i]) {
            $title_description_array[] = array(
                $titlesarray[$i],
                $sorted_links_array[$i],
                $descriptionarray[$i]
            );
            $i                         = $i + 1;
        }
        return $title_description_array;
    } //end save all info();
   
}
?>