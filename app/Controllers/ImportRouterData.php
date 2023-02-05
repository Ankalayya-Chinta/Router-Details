<?php
namespace App\Controllers;
//if (!defined('BASEPATH'))
  //  exit('No direct script access allowed');
   
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;  
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use CodeIgniter\Files\File;
  use App\Models\Import_model;
    
class ImportRouterData extends BaseController {
    
    public function __construct() {
        
       // parent::__construct();
        $this->importModel  = new Import_model();
    }

    // upload xlsx|xls file
    public function index() {
        
        return View('import/index');
    }

    // import excel data
    public function import() {
		$path 			= 'documents/users/';
		$json 			= [];
		$file_name 		= $this->request->getFile('userfile');
		$file_name 		= $this->uploadFile($path, $file_name);
		$arr_file 		= explode('.', $file_name);
		$extension 		= end($arr_file);
		if('csv' == $extension) {
			$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}
		$spreadsheet 	= $reader->load($file_name);
		$sheet_data 	= $spreadsheet->getActiveSheet()->toArray();

		$list 			= [];
        $data = [];
		foreach($sheet_data as $key => $val) {
			if($key != 0) {
				
					$list [] = [
						'sap_id'					=> $val[0],
						'hostname'			=> $val[1],
						'loopback'				=> $val[2],
						'mac_addr'					=> $val[3],
						
					];
                  
			}
            
		}
        $data["result"] = $list;
            echo view("import/display", $data);
		
	}

	public function uploadFile($path, $image) {
    	if (!is_dir($path)) 
			mkdir($path, 0777, TRUE);
            //print($image); exit;
		if ($image->isValid() && ! $image->hasMoved()) {
			$newName = $image->getRandomName();
			$image->move('./'.$path, $newName);
			return $path.$image->getName();
		}
		return "";
	}

    public function saveData()
{
   $data = $this->request->getPost('data');
   foreach($data as $row){
    $data = [
        'sapid' => $row[0],
        'hostname' => $row[1],
        'loopback' => $row[2],
        'mac_addr' => $row[3],
    ];

    $insertID = $this->importModel->addRouter($data);
    if ($insertID != null) {
        echo "<script>alert('Submitted successfully.');</script>";
     } else {
        echo "<script>alert('Error inserting data.');</script>";
     }
   }
}
    
}
?>