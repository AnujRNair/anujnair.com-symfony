# Updated to Ubuntu 16.04 and Passenger for Ruby stopped working

After updating to Ubuntu 16.04, one of my sites stopped loading, and I was greeted with a generic Passenger error message.

These are the steps I took to solve the issue.

#### Site setup

- Apache2
- Ruby on Rails backend
- Passenger to run Ruby on Apache

#### Debugging the Error

After seeing a generic error message on the screen, I viewed the contents of my apache error log file to understand more details about what was happening:
```
$ tail -f /var/log/apache2/error.log
```

Hitting `CMD + K` cleared the screen for me, and refreshing my browser generated a new error log for me to see.

I started seeing lots of errors, at the top one which read:
```
Warning: compilation didn't succeed. To learn why, read this file:
/tmp/passenger_native_support-1mjrifn.log
```

This log didn't exist.

Underneath were some 404 errors from not being able to download some tar files:
```
Could not download https://github.com/phusion/passenger/releases/download/release-6.0.2/rubyext-ruby-2.4.0-x86_64-linux.tar.gz: The requested URL returned error: 404 Not Found
```

and eventually we got to a line which starts to lead us somewhere:
```
Error: The application encountered the following error: libmysqlclient.so.18: cannot open shared object file: No such file or directory
``` 

Looks like mysql2 wasn't installed?

#### The solution

After navigating into my ruby site directory on my server, I ran the following commands:
```bash
$ bundle exec gem uninstall mysql2
$ bundle install
```

This uninstalled `mysql2` from my site, and then re installed all of my gems, including mysql2 again.

After resetting my server using `sudo apachectl restart`, my site was back up and running!
