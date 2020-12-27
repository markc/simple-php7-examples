<?php
// index.php 20150101 - 20201227
// Copyright (C) 2015-2021 Mark Constable <markc@renta.net> (AGPL-3.0)

echo new class
{
    protected
    $email = 'markc@renta.net',
    $in = [
        'm'     => 'home',  // Method [home|about|contact]
    ],
    $out = [
        'doc'   => 'SPE::02',
        'css'   => '',
        'nav'   => '',
        'head'  => 'Styled',
        'main'  => 'Error: missing page!',
        'foot'  => 'Copyright (C) 2021 Mark Constable (AGPL-3.0)',
    ],
    $nav = [
        ['Home', '?m=home'],
        ['About', '?m=about'],
        ['Contact', '?m=contact'],
    ];
    
    public function __construct()
    {
        foreach ($this->in as $k => $v)
            $this->in[$k] = isset($_REQUEST[$k])
                ? htmlentities(trim($_REQUEST[$k])) : $v;

        if (method_exists($this, $this->in['m']))
            $this->out['main'] = $this->{$this->in['m']}();

        foreach ($this->out as $k => $v)
            $this->out[$k] = method_exists($this, $k) ? $this->$k() : $v;
    }

    public function __toString() : string
    {
        return $this->html();
    }

    private function css() : string
    {
        return '
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">';
    }

    private function nav() : string
    {
        $m = '?m='.$this->in['m'];
        
        $a = join('', array_map(function ($n) use ($m) {
            $c = $m === $n[1] ? ' active" aria-current="page"' : '"';
            return '
            <li class="nav-item">
              <a class="nav-link' . $c . ' href="' . $n[1] . '">' . $n[0] . '</a>
            </li>';
        }, $this->nav));
        
        return '
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <div class="container">
        <a class="navbar-brand" href="#">' . $this->out['head'] . '</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">' . $a . '
          </ul>
        </div>
      </div>
    </nav>';      
    }

    private function head() : string
    {
        return $this->out['nav'];
    }

    private function main() : string
    {
        return '
    <main class="container">' . $this->out['main'] . '
    </main>';
    }

    private function foot() : string
    {
        return '
    <footer class="container text-center p-4">
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
    <title>' . $doc . '</title>' . $css . '
  </head>
  <body>' . $head . $main . $foot . '
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  </body>
</html>
';
    }

    private function home() : string
    {
        $this->nav = array_merge($this->nav, [['Project Page', 'https://github.com/markc/spe/tree/master/02-Styled']]);
            
        return '
      <div class="bg-light p-5 rounded">
        <h1>Home Page</h1>
        <p class="lead">
This is an ultra simple single-file PHP8 plus Bootstrap 5 (beta) framework and template system example.
Comments and pull requests are most welcome via the Issue Tracker link.
        </p>
        <a class="btn btn-lg btn-primary" href="https://github.com/markc/spe/issues" role="button">Issue Tracker &raquo;</a>
      </div>';
    }

    private function about() : string
    {
        return '
      <div class="bg-light p-5 rounded">
        <h1>About Page</h1>
        <p class="lead">
This is an example of a simple PHP7 and PHP8 "framework" to provide the core
structure for further experimental development with both the framework
design and some of the new features of PHP7 and PHP8.
        </p>
      </div>';
    }

    private function contact() : string
    {
        return '
      <div class="bg-light p-5 rounded">
      <h1>Contact Page</h1>
      <form class="mx-auto" style="width: 400px;" method="post" onsubmit="return mailform(this);">
        <div class="mb-3">
          <label for="subject" class="form-label">Subject</label>
          <input type="text" class="form-control" id="subject">
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>      
      <script>
function mailform(form) {
    location.href = "mailto:' . $this->email . '"
        + "?subject=" + encodeURIComponent(form.subject.value)
        + "&body=" + encodeURIComponent(form.message.value);
    form.subject.value = "";
    form.message.value = "";
    alert("Thank you for your message. We will get back to you as soon as possible.");
    return false;
}
      </script>
      </div>';
    }
};
