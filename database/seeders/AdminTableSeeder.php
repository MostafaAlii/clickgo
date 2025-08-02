<?php
namespace Database\Seeders;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Schema};
use Illuminate\Support\Str;
class AdminTableSeeder extends Seeder {
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('admins')->delete();
        $admin = Admin::create([
            'name'          =>  'Mostafa Alii',
            'email'         =>  'admin@app.com',
            'password'      =>  bcrypt('123123'),
            'type'          =>  'admin',
            'status'        =>  'active',
            'remember_token' => Str::random(10),
        ]);
        $admin = Admin::create([
            'name'          =>  'Mostafa',
            'email'         =>  'mm@app.com',
            'password'      =>  bcrypt('123123'),
            'status'        =>  'inactive',
            'type'          =>  'supervisor',
            'remember_token' => Str::random(10),
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
