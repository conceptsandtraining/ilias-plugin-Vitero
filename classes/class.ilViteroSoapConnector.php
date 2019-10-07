<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Abstract vitero soap connector
 * 
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 * $Id: class.ilViteroSoapConnector.php 33586 2012-03-07 13:12:56Z smeyer $
 */
abstract class ilViteroSoapConnector
{
	const ERR_WSDL = 2001;

	const WS_TIMEZONE = 'Africa/Ceuta';
	const CONVERT_TIMZONE = 'Africa/Ceuta';
	const CONVERT_TIMEZONE_FIX = 'Africa/Ceuta';

	private $settings;
	private $plugin;

	private $client = null;

	private $logger = null;

	/**
	 * Get instance
	 */
	public function __construct()
	{
		global $DIC;

		$this->logger = $DIC->logger()->xvit();

		$this->plugin = ilViteroPlugin::getInstance();
		$this->settings = ilViteroSettings::getInstance();
	}

	/**
	 * @return ilLogger $logger
	 */
	protected function getLogger()
	{
		return $this->logger;
	}

	/**
	 * Get wsdl name
	 * @return string
	 */
	abstract protected function getWsdlName();

	/**
	 *
	 * @return <type>
	 */
	public function getPluginObject()
	{
		return $this->plugin;
	}

	/**
	 * Get vitero settings
	 * @return ilViteroSettings
	 */
	public function getSettings()
	{
		return $this->settings;
	}

	/**
	 * Get soap client
	 * @return SoapClient
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * init soap client
	 * @return void
	 * @throws ilViteroConnectorException
	 */
	protected function initClient()
	{

		try {
			$options = [
				'cache_wsdl' => 0,
				'trace' => 1,
				'exceptions' => true,
				'classmap' => [
					'phonetype' => 'ilViteroPhone'
				]
			];

			$proxy = $this->getSettings()->getProxy();
			$port = $this->getSettings()->getPort();
			if(
				$proxy !== '' &&
				$port !== ''
			) {
				$options['proxy_host'] = $proxy;
				$options['proxy_port'] = $port;
			}

			$this->client = new SoapClient(
				$this->getSettings()->getServerUrl().'/'.$this->getWsdlName(),
				$options
			);
			$this->client->__setSoapHeaders(
				$head = new ilViteroSoapWsseAuthHeader(
					$this->getSettings()->getAdminUser(),
					$this->getSettings()->getAdminPass()
				)
			);

			#$GLOBALS['ilLog']->write(__METHOD__. ': HEADER TO STRING : '. $head);
			return;
		}
		catch(SoapFault $e) {
			$GLOBALS['ilLog']->write('VITERO: '. $e->getMessage());
			$GLOBALS['ilLog']->write($this->getSettings()->getServerUrl().'/'.$this->getWsdlName());
			throw new ilViteroConnectorException('',self::ERR_WSDL);
		}
	}

	protected function parseErrorCode(Exception $e)
	{
		return (int) $e->detail->error->errorCode;
	}

}

?>
