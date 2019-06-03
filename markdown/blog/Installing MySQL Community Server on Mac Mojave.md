# Installing MySQL Community Server on Mac Mojave

- Download the latest MySQL DMG from the [MySQL Community Server Downloads Page](https://dev.mysql.com/downloads/mysql/)
- Extract and install MySQL.
  - Make sure to set a root password during installation 
- Once installed, you'll need to setup a symlink to the `mysql bin` file so that you can access it via command line.
  - Open System Preferences. There should be a new MySQL icon at the bottom left.
  - Under the version number, there should be a link to the folder your MySQL instance was installed to
  - Run `ln -s /<path from above>/bin/mysql /usr/local/bin/mysql`

You should now be able to run `mysql` from the command line! Access it via `mysql -u root -p`

You'll notice that there are other `bin` files in the mysql directory - you might need to symlink any of those you use frequently too, such as `mysqladmin` or `mysqldump`
