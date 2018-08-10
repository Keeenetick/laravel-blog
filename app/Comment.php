<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function post(){
        return $this->hasOne(Post::class);
    }

    public function author(){
        return $this->hasOne(User::class);
    }

    public function allow(){    //Разрешить коммент
        $this->status = 1;
        $this->save();
    }

    public function disAllow(){    //Запретитькоммент
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus(){
        if($this->status = 0){
        return $this->allow();
        }
        return $this->disAllow();
    }

    public function remove(){
        $this->delete();
    }
}
