<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientModel;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $clients = [];
        $clientsData = [
            ['CAROL', 'GUNTALILIB', '92-055', 'Provincial Government Assistant Department Head	Permanent',''],
            ['MA. CARLA LUCIA', 'TORRALBA', '98-021', 'Provincial Human Resource Management Officer', ''],
            ['JOSE ESTEPHEN JOHN', 'ESTRELLA', '02-033', 'Administrative Aide III (Driver I)', 'PERSONNEL RELATIONS AND DISCIPLINE DIVISION'],
            ['SANIATA', 'TARLIT', '95-034', 'Administrative Assistant II (HRM Assistant)', 'PERSONNEL RELATIONS AND DISCIPLINE DIVISION'],
            ['ROWENA VI', 'ESTEBAN', '04-010', 'Administrative Officer V (HRMO III)', 'PERSONNEL RELATIONS AND DISCIPLINE DIVISION'],
            ['CYD JOAN', 'SIMBALA', '06-013', 'Supervising Administrative Officer (HRMO IV)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['JULIUS GERON', 'IGLESIAS', '09-031', 'Administrative Officer IV (HRMO II)', 'BENEFITS AND WELFARE DIVISION'],
            ['RENZ BRAINERD', 'CACHOLA', '13-077', 'Administrative Officer IV (HRMO II)', 'BENEFITS AND WELFARE DIVISION'],
            ['MARIELLE BERNADETTE', 'MANAIG', '17-019', 'Administrative Assistant II (HRM Assistant)', 'PERSONEL RELATIONS  AND DISCIPLINE DIVISION'],
            ['DOLORES MARINATHA', 'BELARAS', '17-027', 'Administrative Officer IV (HRMO II)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['CHRISTIAN DAVE', 'PASCUA', '17-062', 'Administrative Aide IV (Clerk II)', 'PERSONNEL RELATIONS AND DISCIPLINE DIVISION'],
            ['GWENDOLYN IVA', 'DAWAY-CABUYABAN', '19-001', 'Administrative Assistant II (HRM Assistant)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['KEEMPEE PAUL', 'DIMAL', '19-067', 'Administrative Assistant II (Data Controller II)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['PRINCESS', 'MARTINEZ', '20-001', 'Administrative Aide IV (HRM Aide)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['REIMARK', 'MALAMUG', '20-002', 'Administrative Assistant II (HRM Assistant)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['ALISHA ASHLEIGH', 'LA CORDA', '20-109', 'Administrative Officer IV (HRMO II)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['BRIGIT', 'GAMBOA', '21-066', 'Administrative Aide III (Clerk I)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            // ['CHRISTINE JOY', 'BARTOLOME', '20-095', 'Administrative Aide IV (Clerk II)', 'OFFICER IN CHARGE'],
            ['ROCHELLE', 'TORRES', '21-065', 'Administrative Aide IV (HRM Aide)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['ALLEN', 'NAVARRO', '21-034', 'Administrative Officer V', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['KIMJO', 'ACOB', '22-003', 'Administrative Aide III (Clerk I)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['OCEAN MHAY', 'ALINDADA', '22-027', 'Administrative Aide IV (Clerk II)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['JESSALYN MAY', 'DOMINGO', '17-0261', 'Administrative Aide IV (Clerk II)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['ANNA RIZZA', 'PUMANES', '22-112', 'Administrative Aide Iv (Clerk II)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['MARC ERVIN', 'CALIMAG', '24-308', 'Administrative Aide III (Clerk I)', 'BENEFITS AND WELFARE DIVISION'],
            ['ELLEN CARR', 'GARINGAN', '23-834', 'Administrative Aide III (Clerk I)', 'ORGANIZATIONAL DEVELOPMENT DIVISION'],
            ['JHIENETTE ROSE', 'MAGNO', '24-220', 'Administrative Aide III (Clerk I)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['DONALYN', 'MANGHI', '24-386C', 'Administrative Aide III (Clerk I)', 'PERSONNEL RELATIONS AND DISCIPLINE DIVISION'],
            ['HARBIE', 'PANIS', '24-387', 'Administrative Aide III (Driver I)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['JESUS', 'PALADIN', '17-007', 'Administrative Aide III (Driver I)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['EVANGELINE', 'MEDINA', '17-055', 'Administrative Aide II (Messenger)', 'APPOINTMENTS AND ADMINISTRATIVE DIVISION'],
            ['ROVIELENE', 'DANIEL', '17-026', 'Administrative Assistant II (Clerk IV)', 'BENEFITS AND WELFARE DIVISION'],

            ['IRISH COLEEN ANN', 'BASCO', '25-103C', 'Administrative Assistant III (Clerk I)', 'PERSONNEL AND DISCIPLINE DIVISION'],
            ['DHONA MARIE', 'DIAMA', '24-294C', 'Administrative Assistant III (Clerk I)', 'PERSONNEL AND DISCIPLINE DIVISION'],
            ['JAILEEN VIA', 'GANDIA', '25-109C', 'Administrative Assistant III (Clerk I)', 'PERSONNEL AND DISCIPLINE DIVISION'],
            ['JANELLE', 'REYES', '24-313C', 'Administrative Assistant III (Clerk I)', 'PERSONNEL AND DISCIPLINE DIVISION'],
            
        ];

       foreach ($clientsData as $data) {
            $firstName = $data[0];
            $lastName = $data[1];
            $office = strtolower('PHRMO');  
            $employeeNumber = $data[2];
            $division = $data[4];
            $position = $data[3];
            $username = $office . '.' . strtolower(substr($firstName, 0, 1)) . strtolower($lastName); 
            $full_name = strtolower($firstName) . ' ' . strtolower($lastName);
            $clients[] = [
                'role_id' => 4, 
                'full_name' => ucwords($full_name) ,
                'employee_number' => $employeeNumber,
                'office' => $office,
                'position' => $position,
                'division' => $division,
                'username' => $username,
                'password' => Hash::make($username),
                'status' => 'Active',  
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
       }
       ClientModel::insert($clients); 
    }
}
