<?php

namespace Sebsel\Lees;

use Obj;
use Yaml;
use A, F;
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
      $this->path = CONTENT_DIR.DS.$this->year.DS.$this->day;

      if (substr($this->filename,-4) != '.txt') $this->filename .= '.txt';
      if (f::exists($this->path.DS.'-'.$this->filename)) $this->filename = '-'.$this->filename;
      elseif (!f::exists($this->path.DS.$this->filename)) {
        throw new Error('File not found, '.$this->path.DS.$this->filename);
      }

      $data = yaml::read($this->path.DS.$this->filename);
      $data = array_change_key_case($data, CASE_LOWER);

      $this->read = false;

      $id = substr($this->filename, 0, -4);
      if (substr($id,0,1) == '-') {
        $this->read = true;
        $id = substr($id,1);
      }

      $this->id = $this->year.'/'.$this->day.'/'.$id;

      parent::__construct($data);
    }
  }

  function author() {
    if (is_array($this->author))
      return $this->author = new Author($this->get('author'));
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

  function toggleRead() {
    if (substr($this->filename,0,1) == '-') $newname = substr($this->filename,1);
    else $newname = '-'.$this->filename;

    f::move($this->path.DS.$this->filename, $this->path.DS.$newname);
  }
}

class Author extends Obj {
  function __toString() {
    if (isset($this->name))
      return $this->name();

    if (isset($this->url))
      return url::host($this->url());
  }
}
