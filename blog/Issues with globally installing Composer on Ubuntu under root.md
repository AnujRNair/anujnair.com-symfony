# Issues with globally installing Composer on Ubuntu under root

Whilst trying to install composer globally, using my root account, I was running into a strange problem when running the command `curl -sS https://getcomposer.org/installer | php`

At first, I was only seeing the following:

```
root@machine:/home/websites$ curl -sS https://getcomposer.org/installer | php
#!/usr/bin/env php
All settings correct for using Composer
root@machine:/home/websites$
```

PHP was hiding any errors I was meant to see, as I had set my system to use production settings. I turned on PHP settings by finding my php.ini file (`php --ini)`) and then setting `display_errors = On`

Running `curl -sS https://getcomposer.org/installer | php` then gave me the following errors:

```
root@machine:/home/websites$ curl -sS https://getcomposer.org/installer | php
#!/usr/bin/env php
All settings correct for using Composer

Warning: is_dir(): open_basedir restriction in effect. File(/etc/pki/tls/certs) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/etc/ssl/certs) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/etc/ssl) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/usr/local/share/certs) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/usr/ssl/certs) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/opt/local/share/curl) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/usr/local/share/curl) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/usr/share/ssl/certs) is not within the allowed path(s): (/home/websites) in - on line 834

Warning: is_dir(): open_basedir restriction in effect. File(/etc/ssl) is not within the allowed path(s): (/home/websites) in - on line 834

Fatal error: Uncaught exception 'RuntimeException' with message 'Unable to write bundled cacert.pem to: /root/.composer/cacert.pem' in -:394
Stack trace:
#0 -(112): installComposer(false, false, 'composer.phar', false, false, false)
#1 -(14): process(Array)
#2 {main}
  thrown in - on line 394
```

Looking at the [PHP manual for open_basedir](http://php.net/open_basedir), we can see that a php.ini setting called `open_basedir` limits the files which can be accessed by PHP to this specific folder.

In my php.ini file, I had `open_basedir` set to the directory `/home/websites`, which you can see in the error messages above.

#### The solution

Commenting out the line `open_basedir = /home/websites/`, saving, and rerunning the command: `curl -sS https://getcomposer.org/installer | php` allowed it to pass and fully function as expected.

I then reenabled the php setting for security purposes, and changed it to `open_basedir = /home/websites/:/usr/local/bin/` so that composer can continue to run when needed!
