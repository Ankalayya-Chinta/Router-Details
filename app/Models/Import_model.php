<?php
//if (!defined('BASEPATH'))
  //  exit('No direct script access allowed');

  namespace App\Models;
  use CodeIgniter\Model;

class Import_model extends Model {

    protected $table = 'router_details';
    private $_batchImport;

    public function setBatchImport($batchImport) {
        $this->_batchImport = $batchImport;
    }

    public function addRouter(array $data)
{
    $this->db->table($this->table)->insert($data);
    return $this->db->insertID();
}

}

?>