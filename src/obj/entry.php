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

      if (isset($this->photo) and isset($this->content['html']) and preg_match('!'.$this->photo.'!', $this->content['html']))
        unset($this->photo);
    }
  }

  function author() {
    if (is_array($this->author))
      return $this->author = new Author($this->get('author'));
    if (v::url($this->author))
      return $this->author = new Author(['url' => $this->get('author')]);

    return $this->author;
  }

  function url() {
    if(is_array($this->url)) return a::first($this->url);
    return $this->url;
  }

  function repost_of() {
    if (is_array($this->get('repost-of'))) {
      $repost = $this->get('repost-of');
      if (isset($repost['author']['name']) and isset($repost['url'])) {
        return new Obj([
          'url' => $repost['url'],
          'author' => $repost['author']['name'],
          'photo' => (isset($repost['author']['photo']) ? $repost['author']['photo'] : null)
        ]);
      }

      if (isset($repost['url']))
        $repost = $repost['url'];
    }

    if (v::url($this->get('repost-of')))
      return new Obj(['url'=> $this->get('repost-of'), 'author' => url::host($this->get('repost-of'))]);

    return false;
  }

  function content() {
    if ($repost = $this->get('repost-of') and isset($repost['content']))
      $this->content = $repost['content'];

    if (empty($this->content))
      return null;

    if (is_string($this->content))
      return $this->content;

    if (isset($this->content['html'])) {
      $html = $this->content['html'];
      $html = preg_replace('!<script.*?</script>!', '', $html);
      $html = preg_replace('!(on[a-z0-9]*?=[\'"].*?[\'"])!i', '', $html);
      return $html;
    }

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

  function name() {
    if ($repost = $this->get('repost-of') and isset($repost['name']))
      $this->name = $repost['name'];

      return (isset($this->name) and is_string($this->name)) ? preg_replace('!https?://([^\s]+)!', '', $this->name) : false;
  }

  function hasName() {
    if (!$this->name) return false;
    if (empty($this->content())) return true;
    if (is_string($this->content))
      $content = $this->content;
    elseif (isset($this->content['html']))
      $content = $this->content['html'];
    else $content = a::first($this->content);

    $content = preg_replace('!<(.*?)>!', '', $content);

    $a = substr($this->name(), 0, 30);
    $b = substr($content, 0, 30);

    $diff = levenshtein($a,$b)/strlen($a);

    return ($diff > 0.3);
  }

  function drawContext() {
    if($this->get('in-reply-to') and $entry = db::one('entry', 'id', ['url' => $this->get('in-reply-to')]))
      template('entry', ['entry' => new Entry($entry->id), 'inside' => true]);
  }

  public function __call($method, $arguments) {
    $method = str_replace('_', '-', $method);
    return isset($this->$method) ? $this->$method : null;
  }

  static function exists($id, $uid) {
    $id = explode('/', $id);
    foreach(dir::read(ENTRIES_DIR.DS.$id[0].DS.$id[1]) as $file) {
      if (substr($file, 0, 13) == $id[2]) {
        return true;
      }
    }
    if(db::one('entry', '*', ['url' => $uid])) return true;
    return false;
  }

  static function create($id, $entry) {
    if (!entry::exists($id, $entry['uid'])) {
      $content = yaml::encode($entry);

      f::write(ENTRIES_DIR . DS . $id . '.txt', $content);
      db::insert('entry', [
        'id' => $id,
        'url' => (isset($entry['url']) ? $entry['url'] : $entry['uid']),
        'status' => 'new'
      ]);
    }
  }
}
