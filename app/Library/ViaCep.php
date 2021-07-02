<?php

namespace App\Library;

use GuzzleHttp\Client;

class ViaCep
{
	/**
	 * @param string $cep
	 * @return array
	 */
	public static function cepConsult(string $cep)
	{
		try {
			$cep = str_pad(preg_replace("/[^0-9]/", "", $cep), 8, 0, STR_PAD_LEFT);
			if (strlen($cep) === 8) {
				$client = new Client();
				$response = $client->get("https://viacep.com.br/ws/$cep/json/");
				return json_decode($response->getBody(), true);
			}
			return [];
		} catch (\Exception $e) {
			\Log::error($e);
			return [];
		}
	}
}
