## spe/01-Simple

**2021-01-01** -- _Copyright (C) 2015-2021 Mark Constable (AGPL-3.0)_

This is the first and simplest project example that demonstrates the
essential core structure for this project series.

To take advantage of clean URLs with nginx you would need to add
something like this to the server {} section for your vhost.

    rewrite ^/spe/01-Simple(.+)$ /spe/01-Simple/index.php?p=$1 last;
