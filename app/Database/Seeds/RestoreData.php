<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RestoreData extends Seeder
{
    public function run()
    {
        $this->call('AuthData');
        $this->call('CustomerCategoryData');
        $this->call('CustomerData');
        $this->call('MaterialCategoryData');
        $this->call('MaterialData');
        $this->call('RoutesData');
        $this->call('SatuanData');
        $this->call('WorkshopData');
        $this->call('ApqpLevel');
        $this->call('ApqpApprover');
        $this->call('ApqpDocument');
    }
}
