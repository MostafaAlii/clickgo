<?php
namespace Database\Seeders;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;
class TeacherTableSeeder extends Seeder {
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('teachers')->delete();
        $teacher = Teacher::create([
            'name'          =>  'Mostafa Alii',
            'email'         =>  'teacher@app.com',
            'password'      =>  bcrypt('teacher@app.com'),
            'status'        =>  'active',
            'remember_token' => Str::random(10),
        ]);
        $teacher = Teacher::create([
            'name'          =>  'Mostafa',
            'email'         =>  'mmteacher@app.com',
            'password'      =>  bcrypt('123123'),
            'status'        =>  'active',
            'remember_token' => Str::random(10),
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
