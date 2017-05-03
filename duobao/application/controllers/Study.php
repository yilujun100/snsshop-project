<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Study extends MY_Controller
{
	public function index()
	{
		$error = <<<E
aaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111aaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111
bbbbbbbbbbbbbbbbbbbbaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111bbbbbbbbbbbbbbbbbbbbaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111
ddddddddddddddddddddaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111bbbbbbbbbbbbbbbbbbbbaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111
&&&&&&&&&&&&&aaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111bbbbbbbbbbbbbbbbbbbbaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111
#########!11111111111111111111111aaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111bbbbbbbbbbbbbbbbbbbbaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbdddddddddddddddddddd&&&&&&&&&&&&&#########!11111111111111111111111
E;
		$error .= $error;
		$error = str_replace("\n", '', $error);

		$len = strlen($error);

		for ($i = 0; $i < 100; $i ++) {

			$errorlabel = substr($error, mt_rand(0, $len / 2), 100);
			$errorinfo = substr($error, mt_rand(0, $len / 2), $len / 2);

			$this->log->debug($errorlabel, $errorinfo, (array)$this);
			$this->log->info($errorlabel, $errorinfo, (array)$this);
			$this->log->notice($errorlabel, $errorinfo,(array) $this);
			$this->log->warning($errorlabel,$errorinfo, (array)$this);
			$this->log->critical($errorlabel, $errorinfo, (array)$this);
			$this->log->alert($errorlabel, $errorinfo, (array)$this);
			$this->log->error($errorlabel, $errorinfo, (array)$this);
			$this->log->error($errorlabel, $errorinfo, (array)$this);
			$this->log->error($errorlabel, $errorinfo, (array)$this);
			$this->log->debug($errorlabel, $errorinfo, (array)$this);
			$this->log->info($errorlabel, $errorinfo, (array)$this);
		}
	}

	public function clear()
	{
		$this->load->helper('cookie');
		foreach (array_keys($_COOKIE) as $name) {
			delete_cookie($name);
			delete_cookie($name, ltrim(strstr($_SERVER['HTTP_HOST'], '.'), '.'));
		}
	}
}
