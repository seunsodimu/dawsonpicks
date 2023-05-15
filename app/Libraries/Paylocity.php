<?php

namespace App\Libraries;

class Paylocity
{
    
    private function getAccessToken() 
	{
    $client_id = getenv('paylocityClientID');
    $client_secret = getenv('paylocityClientSecret');
    $token_url = getenv('paylocityTokenURL');

    $content = "grant_type=client_credentials&scope=WebLinkAPI";
    $authorization = base64_encode("$client_id:$client_secret");
    $header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $token_url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $content
    ));
    $response = curl_exec($curl); 
    curl_close($curl);

    return json_decode($response)->access_token;
	}
	
	public function getEmployees()
	{
		$apiurl = 'https://api.paylocity.com/api/v2/companies/114216/employees/?pagesize=1000';
		$cookie = '';
		$curl = curl_init();
		$token = "Authorization: Bearer ".$this->getAccessToken();
		curl_setopt_array($curl, array(
  			CURLOPT_URL => $apiurl,
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_ENCODING => '',
  			CURLOPT_MAXREDIRS => 10,
  			CURLOPT_TIMEOUT => 0,
  			CURLOPT_FOLLOWLOCATION => true,
  			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			CURLOPT_CUSTOMREQUEST => 'GET',
  			CURLOPT_HTTPHEADER => array($token,$cookie),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		
		return json_decode($response, true);
	}
	
	public function getEmployee($id)
	{
		$curl = curl_init();
		$cookie = '';
		$token = "Authorization: Bearer ".$this->getAccessToken();
		
		curl_setopt_array($curl, array(
  			CURLOPT_URL => 'https://api.paylocity.com/api/v2/companies/114216/employees/'.$id,
  			CURLOPT_RETURNTRANSFER => true,
  			CURLOPT_ENCODING => '',
  			CURLOPT_MAXREDIRS => 10,
  			CURLOPT_TIMEOUT => 0,
  			CURLOPT_FOLLOWLOCATION => true,
  			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  			CURLOPT_CUSTOMREQUEST => 'GET',
  			CURLOPT_HTTPHEADER => array($token,$cookie),
		));
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		return json_decode($response, true);	
	}
}