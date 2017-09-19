<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('qiniu_url'))
{
	function qiniu_url($uri = '')
	{
		$domain = get_instance()->config->item('domain', 'qiniu');
        if (0 !== stripos($domain, 'https://') && 0 !== stripos($domain, 'http://'))
        {
            $domain = "http://{$domain}";
        }

        return rtrim($domain, '/').'/'.$uri;
	}
}