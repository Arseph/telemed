<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Facility;
use App\Barangay;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('users')->insert([
            'facility_id' => '0',
	        'username' => 'admin_doh1',
	        'password' => Hash::make('s3cur1ty'),
	        'level' => 'superadmin',
	        'fname' => 'Admin',
	        'mname' => 'RO XII',
	        'lname' => 'DOH',
	        'title' => '',
	        'contact' => '',
	        'email' => 'helpdeskro12@gmail.com',
	        'accrediation_no' => '',
	        'accrediation_validity' => '',
	        'license_no' => '',
	        'prefix' => '',
	        'picture' => '',
	        'designation' => '',
	        'status' => '',
	        'last_login' => '',
	        'login_status' => '',
	        'void' => '0'
        ]);

	    foreach(range(1, 50) as $index) {
	        $firstName = $faker->firstName;
	        $lastName = $faker->lastName;
	        $middleName = $faker->lastName;
	        $username = $firstName.$lastName;
	        $level = ['patient', 'admin', 'doctor'];
	        $gender = ['Male', 'Female'];
	        $civil = ['Single', 'Married', 'Divorce', 'Separated'];
	        $religion = ['AGLIP','ALLY','ANGLI','BAPTI','BRNAG','BUDDH','CATHO','XTIAN','CHOG','EVANG','IGNIK','MUSLI','JEWIT','MORMO','LRCM','LUTHR','METOD','PENTE','PROTE','SVDAY','UCCP','UNKNO','WESLY'];
	        $randlevel = $level[array_rand($level)];
	        $email = $username.'@'.$faker->safeEmailDomain;
	        $facility_id = Facility::all()->random()->id;
	        $doctor_id = [];
	        $edu = ['01','02','03','04','05', '06', '07'];
	        $dob = $faker->dateTimeBetween('1990-01-01', '2012-12-31')->format('Y-m-d');
	        $brgy = Barangay::where('muni_psgc', '126306000')->get()->random()->brg_psgc;
	        DB::table('users')->insert([
	            'facility_id' => $facility_id,
		        'username' => $username,
		        'password' => Hash::make('password123'),
		        'level' => $randlevel,
		        'fname' => $firstName,
		        'mname' => $middleName,
		        'lname' => $lastName,
		        'title' => '',
		        'contact' => $faker->e164PhoneNumber,
		        'email' => $email,
		        'accrediation_no' => '',
		        'accrediation_validity' => '',
		        'license_no' => '',
		        'prefix' => '',
		        'picture' => '',
		        'designation' => '',
		        'status' => '',
		        'last_login' => '',
		        'login_status' => '',
		        'void' => '0'
	        ]);
	        $id = DB::getPdo()->lastInsertId();
	        if($randlevel == 'doctor') {
	        	array_push($doctor_id, $id);
	        }

	        if($randlevel == 'patient') {
	        	DB::table('patients')->insert([
		            'unique_id' => $username.mt_rand(0,5),
			        'account_id' => $id,
			        'doctor_id' => $doctor_id[array_rand($doctor_id)],
			        'facility_id' => $facility_id,
			        'phic_id' => mt_rand(1,15),
			        'id_type' => mt_rand(0,2),
			        'id_type_no' => mt_rand(0,2),
			        'fname' => $firstName,
			        'mname' => $middleName,
			        'lname' => $lastName,
			        'contact' => $faker->e164PhoneNumber,
			        'dob' => $dob,
			        'sex' => $gender[array_rand($gender)],
			        'civil_status' => $civil[array_rand($civil)],
			        'religion' => $religion[array_rand($religion)],
			        'edu_attain' => $edu[array_rand($edu)],
			        'occupation' => '',
			        'monthly_income' => '',
			        'nationality_id' => 608,
			        'phic_status' => '',
			        'region' => 13,
			        'house_no' => '',
			        'street' => '',
			        'brgy' => $brgy,
			        'muncity' => 126306000,
			        'province' => 126300000,
			        'address' => '',
			        'tsekap_patient' => 1,
			        'source' => '',
			        'is_accepted' => 1
		        ]);
	        }
	    }
    }
}
