<?php

require_once dirname(__FILE__) . '/util/ParameterHandler.class.php';
require_once dirname(__FILE__) . '/util/HttpClient.class.php';

class PayFacadeBase
{
	/**
	 * @var ParameterHandler
	 */
	protected $reqParams;

	/**
	 * @var HttpClient
	 */
	protected $httpClient;

	/**
	 * @var ParameterHandler
	 */
	protected $resParams;

	/**
	 * @var int	-1:curl error	-2:sign error
	 */
	protected $iRetCode;
	protected $sErrMsg;

	public function __construct()
	{
		$this->reqParams = new ParameterHandler();
		$this->httpClient = new HttpClient();
		$this->resParams = new ParameterHandler();
	}

	protected function init()
	{
		$this->iRetCode = 0;
		$this->sErrMsg = '';
	}

	public function _getRetCode()
	{
		return $this->iRetCode;
	}

	public function _getErrMsg()
	{
		return $this->sErrMsg;
	}

	public function _getRequestURL()
	{
		return $this->httpClient->getRequestURL();
	}

	public function _getRequestDebugInfo()
	{
		return $this->reqParams->getDebugInfo();
	}

	public function _getCurlErrno()
	{
		return $this->httpClient->getCurlErrno();
	}

	public function _getCurlErrMsg()
	{
		return $this->httpClient->getCurlErrMsg();
	}

	public function _getCurlHttpCode()
	{
		return $this->httpClient->getResponseCode();
	}

	public function _getResponseContent()
	{
		return $this->httpClient->getResponseBody();
	}

	public function _getResponseDebugInfo()
	{
		return $this->resParams->getDebugInfo();
	}

	public function _getResponseParameters()
	{
		return $this->resParams->getAll();
	}

}