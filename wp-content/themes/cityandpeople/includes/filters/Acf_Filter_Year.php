<?php
class acf_filter_year
{
    public $query;
    public function __construct($my_query)
    {
        $this->$query = $my_query;
    }

    public function acf_filter_year($query)
    {
        if (isset($_GET["o-year"]) && is_archive()) {
            $query->set('post_type', ['high-school']);
            $query->set('numberposts', -1);
            $query->set('meta_key', ['o-year']);
            $query->set('meta_value', $_GET['o-year']);
            $query->set('type', 'NUMERIC');
            $query->set('compare', "=");
        }
    }
}