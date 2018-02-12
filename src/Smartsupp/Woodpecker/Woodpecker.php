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
	 * @return array
	 */
	public function getProspects()
	{
		$response = $this->request->get('prospects');
		return $this->getJsonResponse($response);
	}


	/**
	 * Add Prospect
	 *
	 * @return int|null
	 */
	public function addProspect($campaignId, $email)
	{
		$response = $this->addProspects($campaignId, [$email]);
		return array_key_exists($email, $response) ? $response[$email] : null;
	}


	/**
	 * Add Prospects
	 *
	 * @return array
	 */
	public function addProspects($campaignId, array $emails = [])
	{
		$prospects = [];
		foreach ($emails as $email) {
			$prospects[] = [
				'email' => $email,
				'status' => 'ACTIVE',
			];
		}
		$data = [
			'campaign' => [
				'campaign_id' => $campaignId,
			],
			'update' => true,
			'prospects' => $prospects,
		];
		$response = $this->request->post('add_prospects_campaign', $data);
		$responseObject = $this->getJsonResponse($response);

		$prospects = [];
		if (isset($responseObject->prospects) && is_array($responseObject->prospects)) {
			foreach ($responseObject->prospects as $prospect) {
				$prospects[$prospect->email] = $prospect->id;
			}
		}
		return $prospects;
	}


	private function getJsonResponse(ResponseInterface $response)
	{
		return json_decode((string) $response->getBody());
	}

}
