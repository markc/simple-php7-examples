<?php
// index.php 20150101 - 20201227
// Copyright (C) 2015-2021 Mark Constable <markc@renta.net> (AGPL-3.0)

echo new class
{
    private
    $in = [
        'm'     => 'home',      // Method action
    ],
    $out = [
        'doc'   => 'SPE::01',
        'nav'  => '',
        'head'  => 'Simple',
        'main'  => '<p>Error: missing page!</p>',
        'foot'  => 'Copyright (C) 2015-2021 Mark Constable (AGPL-3.0)',
    ],
    $nav = [
        ['Home', '?m=home'],
        ['About', '?m=about'],
        ['Contact', '?m=contact'],
    ];

    public function __construct()
    {
        $this->in['m'] = $_REQUEST['m'] ?? $this->in['m'];
        
        if (method_exists($this, $this->in['m']))
            $this->out['main'] = $this->{$this->in['m']}();
            
        foreach ($this->out as $k => $v)
            $this->out[$k] = method_exists($this, $k) ? $this->$k() : $v;
    }

    public function __toString() : string
    {
        return $this->html();
    }

    private function nav() : string
    {
        return '
      <nav>' . join('', array_map(function ($n) {
            return '
        <a href="' . $n[1] . '">' . $n[0] . '</a>';
        }, $this->nav)) . '
      </nav>';
    }

    private function head() : string
    {
        return '
    <header>
      <h1>' . $this->out['head'] . '</h1>' . $this->out['nav'] . '
    </header>';
    }

    private function main() : string
    {
        return '
    <main>' . $this->out['main'] . '
    </main>';
    }

    private function foot() : string
    {
        return '
    <footer>
      <p><em><small>' . $this->out['foot'] . '</small></em></p>
    </footer>';
    }

    private function html() : string
    {
        extract($this->out, EXTR_SKIP);
        
        return '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . $doc . '</title>
  </head>
  <body>' . $head . $main . $foot . '
  </body>
</html>
';
    }

    private function home() { return '<h2>Home Page</h2><p>Lorem ipsum home.</p>'; }
    private function about() { return '<h2>About Page</h2><p>Lorem ipsum about.</p>'; }
    private function contact() { return '<h2>Contact Page</h2><p>Lorem ipsum contact.</p>'; }
};
