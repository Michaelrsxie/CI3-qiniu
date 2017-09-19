<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper('qiniu_helper');
        $this->output->enable_profiler();
    }

	public function index() {
		$this->load->library('qiniu');
		$fileName = str_replace('.', '', microtime(TRUE)).'.jpg';
		$res = $this->qiniu->putFile($fileName, FCPATH.'static/img/weixin-qrcode.jpg');

		var_dump($res);
		var_dump(qiniu_url($res['key']));
	}
}