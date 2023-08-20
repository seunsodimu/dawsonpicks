<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Authadmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(session()->get('isa') < 1)
	{
	//var_dump('111000'); exit;
		session()->setFlashdata('responseStatus', 'You do not have access to this resource');
		$prev = str_replace(base_url().'/index.php/', '', current_url());
		return redirect()->to('/?prev='.$prev);
	}	
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}