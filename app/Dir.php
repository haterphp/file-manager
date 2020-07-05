<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dir extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public function children_dirs()
    {
        return $this->belongsToMany(Dir::class, ChildrenDir::class, 'parent', 'child_dir');
    }

    public function children_files()
    {
        return $this->belongsToMany(File::class, ChildrenDir::class, 'parent', 'child_file');
    }

    public function path()
    {
        $path = "root/";
        $paths = collect([]);
        $item = ChildrenDir::where(['child_dir' => $this->id])->first();
        while($item->parent != 1){
            $item = ChildrenDir::where(['child_dir' => $item->parent])->first();
            $paths->push($item->child_dir);
        }
        $paths = $paths->reverse()->join('/');
        return $path.$paths;
    }

    public function find_path($id){
        $path = "root/";
        $paths = collect([]);
        $item = ChildrenDir::where(['child_dir' => $id])->first();
        if($item){
          while($item->parent != 1){
              $item = ChildrenDir::where(['child_dir' => $item->parent])->first();
              $paths->push($item->child_dir);
          }
        }

        $path .= $paths->reverse()->join('/') . '/';
        if($id != 1) $path .= $id;

        return $path;
    }
}
