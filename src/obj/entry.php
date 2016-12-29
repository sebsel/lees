<?php

namespace Sebsel\Lees;

use Obj;
use Yaml;
use Dir, Db;
use A, F, V;
use Url;
use Error;

class Entry extends Obj {

  public $author;
  public $path;

  function __construct($arg) {
    if (is_array($arg)) {
      parent::__construct($arg);

    } elseif (is_string($arg)) {
      $arg = explode('/', $arg);
      $this->year = $arg[0];
      $this->day = $arg[1];
      $this->filename = $arg[2];
      $this->path = ENTRIES_DIR.DS.$this->year.DS.$this->day;

      foreach(dir::read($this->path) as $file) {
        if (substr($file, 0, 13) == substr($this->filename, 0, 13)) {
          break;
        }
      }

      $this->filename = $file;

      if (!f::exists($this->path.DS.$this->filename)) {
        throw new Error('File not found, '.$this->path.DS.$this->filename);
      }

      $data = yaml::read($this->path.DS.$this->filename);
      $data = array_change_key_case($data, CASE_LOWER);

      $this->status = substr($this->filename, 14, -4);
      $id = substr($this->filename, 0, 13);

      $this->id = $this->year.'/'.$this->day.'/'.$id;

      parent::__construct($data);
    }
  }

  function author() {
    if (is_array($this->author))
      return $this->author = new Author($this->get('author'));
    if (v::url($this->author))
      return $this->author = new Author(['url' => $this->get('author')]);

    return $this->author;
  }

  function content() {
    if (empty($this->content))
      return null;

    if (is_string($this->content))
      return $this->content;

    if (isset($this->content['text']))
      return $this->content['text'];

    return a::first($this->content);
  }

  function toggleRead($status = 'read') {
    $name = substr($this->filename,0,13);

    if ($this->status != $status) {
      $this->status = $status;
      $newname = $name.'-'.$status;
    } else {
      $this->status = null;
      $newname = $name;
    }

    f::move($this->path.DS.$this->filename, $this->path.DS.$newname.".txt");

    db::update('entry', [
      'status' => $this->status ? $this->status : 'new'
    ], ['id' => $this->id]);
  }

  function hasName() {
    if (!$this->name()) return false;

    $a = substr($this->name(), 0, 30);
    $b = substr($this->content(), 0, 30);

    $diff = levenshtein($a,$b)/strlen($a);

    return ($diff > 0.2);
  }

  public function __call($method, $arguments) {
    $method = str_replace('_', '-', $method);
    return isset($this->$method) ? $this->$method : null;
  }

  static function exists($id) {
    $id = explode('/', $id);
    foreach(dir::read(ENTRIES_DIR.DS.$id[0].DS.$id[1]) as $file) {
      if (substr($file, 0, 13) == $id[2]) {
        return true;
      }
    }
    return false;
  }

  static function create($id, $content) {
    if (!entry::exists($id)) {
      f::write(ENTRIES_DIR . DS . $id . '.txt', $content);
      db::insert('entry', [
        'id' => $id,
        'status' => 'new'
      ]);
    }
  }
}
