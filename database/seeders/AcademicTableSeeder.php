<?php
namespace Database\Seeders;
use App\Models\Academic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;
class AcademicTableSeeder extends Seeder {
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('academics')->delete();
        $academic = Academic::create([
            'name'          =>  'Mostafa Alii',
            'email'         =>  'academic@app.com',
            'password'      =>  bcrypt('academic@app.com'),
            'status'        =>  'active',
            'remember_token' => Str::random(10),
        ]);
        $academic = Academic::create([
            'name'          =>  'Mostafa',
            'email'         =>  'academic@test.com',
            'password'      =>  bcrypt('123123'),
            'status'        =>  'active',
            'remember_token' => Str::random(10),
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
