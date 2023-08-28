<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Enums\TodoStatus;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run(){

        $count = 4;
        $todos = ["Clean House", "Fold Clothes", "Complete Project", "Walk Dog"];

        for($i = 0; $i < $count; $i++){
            $todo = new Todo();
            $todo->title = $todos[$i];
            $todo->status = TodoStatus::Pending;
            if($i > 2){
                $todo->status = TodoStatus::Done;
            }
            $todo->save();
            echo "\n$i / $count : $todo->title | ".$todo->status->value;
        }

    }
}
