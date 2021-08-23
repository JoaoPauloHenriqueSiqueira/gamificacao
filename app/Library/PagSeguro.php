<?php

namespace App\Library;

use GuzzleHttp\Client;

class PagSeguro
{

	public static function getSession()
	{
		try {

			$client = new Client();
			$url = env('PAGSEGURO_API') . "v2/sessions?email=" . env('PAGSEGURO_EMAIL') . "&token=" . env('PAGSEGURO_TOKEN');
			$response = $client->post($url);
			return simplexml_load_string($response->getBody());
		} catch (\Exception $e) {
			\Log::error($e);
			return [];
		}
	}
}
