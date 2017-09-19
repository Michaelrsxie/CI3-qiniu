<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

/**
 * 七牛
 */
class Qiniu
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
	 * 获取错误信息
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}

	public function makeFileName()
	{
		return str_replace('.', '-', microtime(TRUE));
	}

	/**
	 * 获取文件扩展名
	 */
	public function getExtension($fileName)
	{
		$x = explode('.', $fileName);

		if (count($x) === 1)
		{
			return '';
		}

		return '.'.end($x);
	}

	protected function upload($fileName, $filePath)
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
	 * 上传图片(路径)
	 * @param $file 上传的文件地址
	 */
	public function putFile($filePath, $newName = FALSE)
	{
		if ( ! $newName)
		{
			$fileName = $this->makeFileName().$this->getExtension($filePath);
		}
		else
		{
			$fileName = $newName;
		}

		return $this->upload($fileName, $filePath);
	}

	/**
	 * 上传图片(表单)
	 * @param $file 上传的文件名
	 */
	public function uploadFile($file, $newName = FALSE)
	{
		if (isset($_FILES[$file]) && $_FILES[$file]['size'] > 0)
		{
			if ( ! $newName)
			{
				$fileName = $this->makeFileName().$this->getExtension($_FILES[$file]['name']);
			}
			else
			{
				$fileName = $newName;
			}

			$filePath = $_FILES[$file]['tmp_name'];
			return $this->upload($fileName, $filePath);
		}

		$this->errorMsg = '文件未找到';
		return FALSE;
	}
}