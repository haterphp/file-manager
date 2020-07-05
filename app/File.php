<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    public $timestamps = false;
    protected $fillable = ['url', 'name'];

    public function path()
    {
        $path = "root/";
        $paths = collect([]);
        $item = ChildrenDir::where(['child_file' => $this->id])->first();
        while($item->parent != 1){
            $item = ChildrenDir::where(['child_dir' => $item->parent])->first();
            $paths->push($item->child_dir);
        }
        $paths = $paths->reverse()->join('/');
        return $path.$paths;
    }

    public function getUrlAttribute(){
        return Storage::path($this->attributes['url']);
    }
}
