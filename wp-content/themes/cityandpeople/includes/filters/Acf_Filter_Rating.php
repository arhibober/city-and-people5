<?php
class acf_filter_rating
{
    public $query;
    public function __construct($my_query)
    {
        print " mk: ";
        print_r($my_query);
        $this->query = $my_query;
    }

    public function acf_filter_rating($query)
    {
        if (isset($_GET["rating"]) && is_archive()) {
            $query->set('post_type', ['high-school']);
            $query->set('numberposts', -1);
            $query->set('meta_key', ['rating']);
            $query->set('meta_value', $_GET['rating']);
            $query->set('type', 'NUMERIC');
            $query->set('compare', "=");
        }
    }
}