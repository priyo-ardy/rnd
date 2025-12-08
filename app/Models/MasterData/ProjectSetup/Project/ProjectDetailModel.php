<?php

namespace App\Models\MasterData\ProjectSetup\Project;

use CodeIgniter\Model;

class ProjectDetailModel extends Model
{
    protected $table            = 'm_project_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = ['id', 'id_project', 'id_material', 'due_date', 'status', 'remark', 'created_by', 'updated_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getProjectDetails($id_project)
    {
        $query = "
            select 
                mpd.id,
                mpd.id_project,
                mpd.id_material,
                mm.code as material_code,
                mm.name as material_name,
                mm.spesifikasi,
                mpd.due_date,
                mpd.status,
                case
                    when mpd.status = '0' then 'Open'
                    when mpd.status = '1' then 'On Progress'
                    when mpd.status = '2' then 'Hold'
                    when mpd.status = '3' then 'Rejected'
                    when mpd.status = '4' then 'Close'
                end as status_name,
                mpd.remark,
                (
                    select 
                        count(mpd2.id) 
                    from m_project_document mpd2
                    where 
                        (mpd2.id_project = mpd.id_project and mpd2.id_material = mpd.id_material) and
                        (mpd2.file_name = '')
                )as open_status,
                (
                    select 
                        count(mpd3.id) 
                    from m_project_document mpd3 
                    where 
                        (mpd3.id_project = mpd.id_project and mpd3.id_material = mpd.id_material) and 
                        (mpd3.file_name <> '')		
                ) as close_status,
                case
                    WHEN mpd.due_date < CURDATE() and mpd.status = '1' THEN 
                        DATEDIFF(CURDATE(), mpd.due_date)  -- Hitung berapa hari sudah lewat
                    ELSE 0
                end as overdue,
                mpd.created_at,
                mpd.updated_at,
                mpd.deleted_at
            from m_project_details mpd
                left join m_material mm on mpd.id_material = mm.id
            where 
                mpd.id_project = ?
            order by overdue desc

        ";

        $result = $this->db->query($query, [$id_project], true)->getResult();

        return $result;
    }
}
