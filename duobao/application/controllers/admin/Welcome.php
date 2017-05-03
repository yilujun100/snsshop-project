<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Admin_Base
{
	public function index()
	{
		$this->render(array(), 'common/index');
	}
}
