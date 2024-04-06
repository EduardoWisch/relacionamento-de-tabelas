<?php

use App\Models\{
    Course,
    Module,
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

Route::get('/one-to-many', function () {
    // $course = Course::create(['name' => 'Curso de Laravel']);

    $course = Course::with('modules.lessons')->first();

echo $course->name;
echo '<br>';
foreach ($course->modules as $module) {
    echo "Modulo {$module->name} <br>";
    foreach ($module->lessons as $lesson){
        echo "Aula {$lesson->name}";
    }
}
    $data = [
        'name' => 'Modulo x2'
    ];

    // $course->modules()->create($data);

    dd($course->modules);
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
