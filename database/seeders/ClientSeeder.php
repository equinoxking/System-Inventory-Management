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
            ['HELEN', 'DUMLAO', 'EMP1001'],
            ['CECILIA', 'FELIPE', 'EMP1002'],
            ['JUANITA', 'TAMILAG', 'EMP1003'],
            ['LORETO', 'AGRAAM', 'EMP1004'],
            ['FRANCISCA', 'ABALOS', 'EMP1005'],
            ['ELMA', 'BALANGATAN', 'EMP1006'],
            ['VICTORINA', 'TALLUNGAN', 'EMP1007'],
            ['ARABELLA', 'DUMLAO', 'EMP1008'],
            ['WILLIAM', 'GURAT', 'EMP1009'],
            ['RODRIGO', 'ACOSTA', 'EMP1010'],
            ['CAROL', 'GUNTALILIB', 'EMP1011'],
            ['MA. CARLA LUCIA', 'TORRALBA', 'EMP1012'],
            ['MARISSA', 'CASTILLO', 'EMP1013'],
            ['JOSE ESTEPHEN JOHN', 'ESTRELLA', 'EMP1014'],
            ['VILMA', 'MEIMBAN', 'EMP1015'],
            ['SANIATA', 'TARLIT', 'EMP1016'],
            ['ROWENA VI', 'ESTEBAN', 'EMP1017'],
            ['LUIS, JR.', 'LIBAN', 'EMP1018'],
            ['CYD JOAN', 'SIMBALA', 'EMP1019'],
            ['JULIUS GERON', 'IGLESIAS', 'EMP1020'],
            ['CHRISTINE GAY', 'PUDIQUET', 'EMP1021'],
            ['RENZ BRAINERD', 'CACHOLA', 'EMP1022'],
            ['RONALD', 'GALIZA', 'EMP1023'],
            ['MARIELLE BERNADETTE', 'MANAIG', 'EMP1024'],
            ['CHEYSERR ANN', 'AGAMATA', 'EMP1025'],
            ['ROVIELENE', 'DANIEL', 'EMP1026'],
            ['DOLORES MARINATHA', 'BELARAS', 'EMP1027'],
            ['EVANGELINE', 'MEDINA', 'EMP1028'],
            ['CHRISTIAN DAVE', 'PASCUA', 'EMP1029'],
            ['GWENDOLYN IVA', 'DAWAY-CABUYABAN', 'EMP1030'],
            ['KEEMPEE PAUL', 'DIMAL', 'EMP1031'],
            ['PRINCESS', 'MARTINEZ', 'EMP1032'],
            ['REIMARK', 'MALAMUG', 'EMP1033'],
            ['ALISHA ASHLEIGH', 'LA CORDA', 'EMP1034'],
            ['BRIGIT', 'GAMBOA', 'EMP1035'],
            ['CHRISTINE JOY', 'BARTOLOME', 'EMP1036'],
            ['JOVIE ANN', 'BAWANAN', 'EMP1037'],
            ['ROCHELLE', 'TORRES', 'EMP1038'],
            ['ALLEN', 'NAVARRO', 'EMP1039'],
            ['KIMJO', 'ACOB', 'EMP1040'],
            ['OCEAN MHAY', 'ALINDADA', 'EMP1041'],
            ['JESSALYN MAY', 'DOMINGO', 'EMP1042'],
            ['ANNA RIZZA', 'PUMANES', 'EMP1043'],
            ['MARC ERVIN', 'CALIMAG', 'EMP1044'],
            ['EULA', 'LACSA', 'EMP1045'],
            ['DARLENE SARAH', 'LICYAYO', 'EMP1046'],
            ['ELLEN CARR', 'GARINGAN', 'EMP1047'],
            ['CHERRY MAY', 'BIAGAN', 'EMP1048'],
            ['JAN MYLE', 'PADILLA', 'EMP1049'],
            ['DEXTER', 'BARTOL', 'EMP1050'],
            ['JHIENETTE ROSE', 'MAGNO', 'EMP1051'],
            ['DONALYN', 'MANGHI', 'EMP1052'],
            ['HARBIE', 'PANIS', 'EMP1053'],
        ];

       foreach ($clientsData as $data) {
            $firstName = $data[0];
            $lastName = $data[1];
            $office = strtolower('PHRMO');  
            $employeeNumber = $data[2]; 
            $phrmoPositions = [
                'HR Manager',
                'HR Officer',
                'HR Assistant',
                'Recruitment Officer',
                'Compensation and Benefits Officer',
                'Training and Development Officer',
                'Employee Relations Officer',
                'Performance Management Officer',
                'Payroll Officer',
                'Records Officer',
                'Administrative Assistant',
                'Documentary Controller',
                'Staffing Specialist',
                'Labor Relations Officer',
                'HR Analyst',
                'Driver',
            ];
            $position = $phrmoPositions[array_rand($phrmoPositions)]; 
            $email = $faker->unique()->safeEmail;  
            $username = $office . '.' . strtolower(substr($firstName, 0, 1)) . strtolower($lastName); 
            $full_name = strtolower($firstName) . ' ' . strtolower($lastName);
            $clients[] = [
                'role_id' => 4, 
                'full_name' => ucwords($full_name) ,
                'employee_number' => $employeeNumber,
                'office' => $office,
                'position' => $position,
                'email' => $email,
                'email_verified_at' => null,
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
