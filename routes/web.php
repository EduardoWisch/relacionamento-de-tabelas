<?php

use App\Models\{
    Course,
    Module,
    Permission,
    User,
    Preference,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/many-to-many-pivot', function() {
    $user = User::with('permissions')->find(1);
    $user->permissions()->sync([
        1 => ['active' => true],
        3 => ['active' => false],
    ]);

    $user->refresh();

    echo "<b>{$user->name}</b> <br>";
    foreach($user->permissions as $permission){
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
});

Route::get('/many-to-many', function() {
    $user = User::with('permissions')->find(1);
    
    // $permission = Permission::find(1);
    // $user->permissions()->save($permission); // SALVA APENAS UM DE CADA VEZ
    // $user->permissions()->saveMany([ 
    //     Permission::find(1),
    //     Permission::find(3),
    //     Permission::find(4),
    // ]); SALVA VÁRIOS DE UMA VEZ
    // $user->permissions()->sync(3); // SALVA APENAS OS PASSADOS E DELETA OS OUTROS
    // $user->permissions()->attach([1,3, 4]); // SALVA VÁRIOS DE UMA VEZ
    //$user->permissions()->detach([1,3]); // DELETA OS QUE FORAM PASSADOS

    $user->refresh();

    dd($user->permissions);
});

Route::get('/one-to-many', function () {
    // $course = Course::create(['name' => 'Curso de Laravel']);

    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "Modulo {$module->name} <br>";
        foreach ($module->lessons as $lesson){
            echo "... Aula {$lesson->name} <br>";
        }
    }
    // $module = Module::first();
    // $data = [
    //     'name' => 'aula de laravel',
    //     'video' => 'video de laravel',
    // ];

    // $module->lessons()->create($data);

    dd($course);
});

Route::get('/one-to-one', function () {
    $user = User::with('preference')->find(1);

    $data = [
        'background_color' => '#000',
    ];

    if($user->preference){
        $user->preference->update($data);
    }else{
        // $user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();
    var_dump($user->preference);

    $user->preference->delete();
    $user->refresh();

    dd($user->preference);
});

Route::get('/', function () {
    return view('welcome');
});

