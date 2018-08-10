<?php

namespace App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
class Post extends Model
{
    use Sluggable;
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    protected $fillable = ['title' , 'content'];
    public function category(){
        return $this->hasOne(Category::class);
    }

    public function author(){
        return $this->hasOne(User::class);
    }

    public function tags(){
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields){
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();
        return $post;
    }

    public function edit($fields){
        $post->fill($fields);
        $post->save();
    }

    public function remove(){
        Storage::delete('uploads/' . $this->image);
        $this->delete();
    }

    public function uploadImage($image){
        if($image == null) {return;}
        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10). '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function setCategory($id){    //Установка категории
        if($id == null) {return;}
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids){          //Установка тегов
        if($ids == null) {return;}
        $this->tags()->sync($ids);
    }

    public function setDraft(){      //Черновик для поста
        $this->staus = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic(){
        $this->staus = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value){  //Переключатель
        if($value == null){
          return  $this->setDraft();
        }
        else
            return  $this->setPublic();
        }
    
    public function setFeatured(){      //Черновик для поста
        $this->is_featured = 1;
        $this->save();
        }
    
    public function setStandart(){
        $this->is_featured = 0;
        $this->save();
        }

    public function toggleFeatured($value){  //Переключатель
            if($value == null){
              return  $this->setStandart();
            }
            else
                return  $this->setFeatured();
            }
    public function getImage(){
        if($this->image == null){
            return '/image/no-image.png';
        }
        return '/uploads/' . $this->image;
    }
}
