<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class RenderImage extends BaseController
{
public function index()
{ 
    $uri = $this->request->getUri();
    $fileName = str_replace('/render/','', $uri->getPath());
    $img_arr = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    $xls_arr = ['xls', 'xlsx', 'csv'];
    $wd_arr = ['doc', 'docx'];
    $pdf_arr = ['pdf'];
    $file_arr = explode('.', $fileName);
    $file_ext = end($file_arr);
    if(in_array($file_ext, $xls_arr)){
       $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; 
   }elseif(in_array($file_ext, $wd_arr)){
       $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; 
    }elseif(in_array($file_ext, $pdf_arr)){
       $mimeType = 'application/pdf'; 
    }elseif(in_array($file_ext, $img_arr)){
        $mimeType = 'image/'.$file_ext;
    }else{
      $mimeType = 'image/jpg';  
    }
    //var_dump($mimeType); exit;
if(($file = file_get_contents($fileName)) === FALSE)
show_404();



$this->response
->setStatusCode(200)
->setContentType($mimeType)
->setBody($file)
->send();

}
    
    public function pdf()
{ 
    $uri = $this->request->getUri();
    $imageName = str_replace('/renderpdf/','', $uri->getPath());
    //var_dump($imageName); exit;
if(($image = file_get_contents($imageName)) === FALSE)
show_404();

// choose the right mime type
$mimeType = 'application/pdf';

$this->response
->setStatusCode(200)
->setContentType($mimeType)
->setBody($image)
->send();

}
    
    public function files()
{ 
    $uri = $this->request->getUri();
    $fileName = str_replace('/renderfiles/','', $uri->getPath());
    var_dump($fileName); exit;
if(($file = file_get_contents($fileName)) === FALSE)
show_404();
$mimeType =   'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

$this->response
->setStatusCode(200)
->setContentType($mimeType)
->setBody($file)
->send();

}
    
    public function xls()
{ 
    $uri = $this->request->getUri();
    $fileName = str_replace('/renderxls/','', $uri->getPath());
    //var_dump($imageName); exit;
if(($file = file_get_contents($fileName)) === FALSE)
show_404();
$mimeType =   'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

$this->response
->setStatusCode(200)
->setContentType($mimeType)
->setBody($file)
->send();

}

}