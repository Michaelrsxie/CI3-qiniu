<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

		$this->load->library('qiniu');
        $this->load->helper('qiniu_helper');

        $this->output->enable_profiler();
    }

	public function index()
	{
		$res = $this->qiniu->putFile(FCPATH.'static/img/weixin-qrcode.jpg');

		var_dump($res);
		var_dump(qiniu_url($res['key']));
	}

	public function upload()
	{
		$this->load->view('upload.html');
	}

	public function upload_img()
	{
		$res = $this->qiniu->uploadFile('image');

		var_dump($res);
		var_dump(qiniu_url($res['key']));
	}
}