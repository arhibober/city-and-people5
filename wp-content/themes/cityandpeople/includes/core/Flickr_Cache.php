<?php
class Flickr_cache
{
    public function __construct()
    {
    }

    public function get_User_ID()
    {
        $transient_key = 'Flickr User ID';
        if (!$data = get_transient($transient_key)) {
            $data = $this->get_data_User_ID();
            set_transient($transient_key, $data, 120);
        }
        return $data;
    }

    public function get_data_User_ID(): array
    {
        return [
            'Flickr User ID' => '186103280@N03',
        ];
    }

    public function get_APi_Key()
    {
        $transient_key = 'Flickr API Key';
        if (!$data = get_transient($transient_key)) {
            $data = $this->get_data_API_key();
            set_transient($transient_key, $data, 120);
        }
        return $data;
    }

    public function get_data_API_key(): array
    {
        return [
            'Flickr API Key' => '186103280@N03',
        ];
    }

    public function get_APi_Secret()
    {
        $transient_key = 'Flickr API Secret';
        if (!$data = get_transient($transient_key)) {
            $data = $this->get_data_API_secret();
            set_transient($transient_key, $data, 120);
        }
        return $data;
    }

    public function get_data_API_secret(): array
    {
        return [
            'Flickr API Secret' => '8bf750cd95167e18e22f88f98d9cde62',
        ];
    }
}