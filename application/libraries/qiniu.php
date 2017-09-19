<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

/**
 * 七牛
 */
class qiniu
{
	/**
     * CodeIgniter instance
     *
     * @var object
     */
    private $_CI;

    private $auth;

    private $token;

    private $uploadMgr;

    private $errorMsg;

	public function __construct()
	{
		$this->_CI = &get_instance();

		$this->_CI->load->config('qiniu', TRUE, TRUE);

		$this->auth = new Auth($this->_CI->config->item('access_key', 'qiniu'), $this->_CI->config->item('secret_key', 'qiniu'));

		$this->token = $this->auth->uploadToken($this->_CI->config->item('bucket', 'qiniu'));
	}

	/**
	 * 上传图片(路径)
	 * @param $fileName 上传后的文件名
	 * @param $file     上传的文件地址
	 */
	public function putFile($fileName, $filePath)
	{
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->putFile($this->token, $fileName, $filePath);
		if ($err !== NULL)
		{
			$this->errorMsg = $err;
			return FALSE;
		}

		return $ret;
	}

	/**
	 * 获取错误信息
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}
}