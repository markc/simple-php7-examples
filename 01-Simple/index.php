<?php declare(strict_types=1);

// index.php 20150101 - 20201228
// Copyright (C) 2015-2021 Mark Constable <markc@renta.net> (AGPL-3.0)

echo new class {

    private array $in = [
        'm' => 'home',
    ];

    private $out = [
        'doc' => 'SPE::01',
        'nav' => '',
        'head' => 'Simple',
        'main' => '<p>Error: missing page!</p>',
        'foot' => 'Copyright (C) 2015-2021 Mark Constable (AGPL-3.0)',
    ];

    private $nav = [
        ['Home', 'home'],
        ['About', 'about'],
        ['Contact', 'contact']
    ];

    public function __construct()
    {
        $m = $_REQUEST['m'] ?? ($_REQUEST['p'] ?? '');
        
        $m = filter_var(trim($m, '/'), FILTER_SANITIZE_URL);

        if (empty($m)) {
            $m = $this->in['m'];
        }
        
        if (method_exists($this, $m)) {
            $this->out['main'] = $this->{$m}();
        }

        foreach ($this->out as $k => $v) {
            $this->out[$k] = method_exists($this, $k) ? $this->{$k}() : $v;
        }
    }

    public function __toString(): string
    {
        return $this->html();
    }

    private function nav(): string
    {
        return '
      <nav>' . join('', array_map(function ($n) {
            $url = (!isset($_REQUEST['p'])) ? "?m=$n[1]" : "$n[1]";
            return '
        <a href="' . $url . '">' . $n[0] . '</a>';
        }, $this->nav)) . '
      </nav>';
    }

    private function head(): string
    {
        return '
    <header>
      <h1>' . $this->out['head'] . '</h1>' . $this->out['nav'] . '
    </header>';
    }

    private function main(): string
    {
        return '
    <main>' . $this->out['main'] . '
    </main>';
    }

    private function foot(): string
    {
        return '
    <footer>
      <p><em><small>' . $this->out['foot'] . '</small></em></p>
    </footer>';
    }

    private function html(): string
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

    private function home(): string
    {
        return '<h2>Home Page</h2><p>Lorem ipsum home.</p>';
    }

    private function about(): string
    {
        return '<h2>About Page</h2><p>Lorem ipsum about.</p>';
    }

    private function contact(): string
    {
        return '<h2>Contact Page</h2><p>Lorem ipsum contact.</p>';
    }
};
