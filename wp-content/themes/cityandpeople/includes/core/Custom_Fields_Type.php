<?php
class Custom_Fields_Type
{
    public function __construct()
    {
    }

    public function true_custom_fields()
    {
        add_post_type_support('city-object', 'custom-fields');
    }

    public static function is_post_type($type)
    {
        return false;
    }
}