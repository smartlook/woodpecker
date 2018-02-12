<?php

namespace Smartsupp\Woodpecker;

use Psr\Http\Message\ResponseInterface;

class Woodpecker
{

	/** @var WoodpeckerRequest */
	private $request;


	/**
	 * Initialise the Insightly class
	 *
	 * @param string $apiKey
	 * @param string $apiVersion
	 * @throws \Exception
	 */
	public function __construct($apiKey, $apiVersion = null)
	{
		$this->request = new WoodpeckerRequest($apiKey, $apiVersion);
	}


	/**
	 * Get Prospects
	 *
	 * @return object
	 */
	public function getProspects()
	{
		$response = $this->request->get('prospects');
		return $this->getJsonResponse($response);
	}


	private function getJsonResponse(ResponseInterface $response)
	{
		return json_decode((string) $response->getBody());
	}

}
