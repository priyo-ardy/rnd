<?php

namespace App\Models\DataTable;

use CodeIgniter\HTTP\RequestInterface;

use CodeIgniter\Model;

class DataTableModel extends Model
{
    protected $table;
    protected $column_order;
    protected $column_search;
    protected $order;
    protected $request;
    protected $db;
    protected $dt;

    public function __construct(RequestInterface $request, $table, $column_order, $column_search, $order)
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = $request;

        $this->table = $table;
        $this->column_order = $column_order;
        $this->column_search = $column_search;
        $this->order = $order;

        $this->dt = $this->db->table($this->table);
    }

    private function _get_datatables_query()
    {
        $searchValue = $this->request->getPost('search')['value'] ?? null;

        // Filter umum, misal hanya data yang belum dihapus
        $this->dt->where('deleted_at', null);

        if ($searchValue) {
            $this->dt->groupStart(); // Mulai grouping kondisi LIKE

            $i = 0;
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->dt->like($item, $searchValue);
                } else {
                    $this->dt->orLike($item, $searchValue);
                }
                $i++;
            }

            $this->dt->groupEnd(); // Akhiri grouping kondisi LIKE
        }

        // Pengaturan order
        if ($this->request->getPost('order')) {
            $orderColumnIndex = $this->request->getPost('order')['0']['column'];
            $orderDir = $this->request->getPost('order')['0']['dir'];
            $this->dt->orderBy($this->column_order[$orderColumnIndex], $orderDir);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($this->request->getPost('length') != -1)
            // $this->dt->where('status', '1');
            $this->dt->where('deleted_at', null);
        $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    public function count_all()
    {
        return $this->db->table($this->table)
            // ->where('status', '1')
            ->where('deleted_at', null)
            ->countAllResults();
    }
}
