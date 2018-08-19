<?php

namespace App;
use Carbon\Carbon;   //Пакет для работы с датой
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
class Post extends Model
{
    use Sluggable;
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    protected $fillable = ['title' , 'content', 'date','description'];
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function author(){
        return $this->belongsTo(User::class,'user_id');
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
        $this->fill($fields);
        $this->save();
    }

    public function remove(){
       $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if($this->image != null){
            Storage::delete('uploads/' . $this->image);
        }
    }
    public function uploadImage($image){
        if($image == null) {return;}
       $this->removeImage();
      
        $filename = str_random(10). '.' . $image->extension();
        $image->storeAs('uploads', $filename);
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
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic(){
        $this->status = Post::IS_PUBLIC;
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

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y',$value)->format('Y-m-d'); //Преобразование даты для корректного сохран. в бд
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }

    public function getCategoryTitle()
    {
       return($this->category != null)
            ? $this->category->title
            : "Нет Категории";
    }

    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty())
       ? implode(', ', $this->tags->pluck('title')->all())
        : "Нет тегов";
    }

    public function getCategoryID()
    {
        return  $this->category != null ? $this->category->id: null;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }
}
