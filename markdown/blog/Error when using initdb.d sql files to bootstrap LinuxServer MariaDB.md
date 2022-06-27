# Error when using initdb.d sql files to bootstrap LinuxServer MariaDB

I kept running into a frustrating error when trying to bootstrap `linuxserver/mariadb` with some SQL files. 
I wanted to create users and databases automatically on first run. 

I kept receiving the error:

```bash
[Warning] Access denied for user 'root'@'localhost' (using password: NO)
```

even though I knew a root password was being set. Here's how I solved that:

#### Background

`linuxserver/mariadb` allows you to add `.sql` files into your `config/initdb.d` folder,
which will be run when MariaDB is first set up. I wanted to utilize this to automatically create
some users and databases which my other services would use.

I created some basic SQL files to do so, and added them into the directory. Each file looked like
the following:
```sql
CREATE DATABASE db1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'user1'@'%' IDENTIFIED BY 'some-password';

GRANT ALTER, CREATE, DELETE, DROP, INDEX, INSERT, SELECT, UPDATE ON `db1`.* TO 'user1'@'%';
FLUSH PRIVILEGES;
```

I configured my docker compose file as follows:
```bash
secrets:
  mariadb_root_password:
    file: /path/to/root/password

services:
  mariadb:
    container_name: mariadb
    image: linuxserver/mariadb:10.5.16
    ports:
      - 3306:3306
    volumes:
      - /path/to/mariadb:/config
    secrets:
      - mariadb_root_password
    environment:
      FILE__MYSQL_ROOT_PASSWORD: /run/secrets/mariadb_root_password
```

I added my files into `/path/to/mariadb/initdb.d`, and spun up my container via `docker compose up mariadb`. 

I was granted with some output. Here are the relevant sections:
```bash
...
mariadb  | [env-init] MYSQL_ROOT_PASSWORD set from FILE__MYSQL_ROOT_PASSWORD
...
mariadb  | [cont-init.d] 10-adduser: exited 0.
mariadb  | [cont-init.d] 30-config: executing...
mariadb  | [cont-init.d] 30-config: exited 0.
mariadb  | [cont-init.d] 40-initialise-db: executing...
mariadb  | Setting Up Initial Databases
...
mariadb  | 2022-06-27 13:18:48 0 [Note] mysqld: ready for connections.
mariadb  | Version: '10.5.16-MariaDB-log'  socket: '/run/mysqld/mysqld.sock'  port: 3306  MariaDB Server
mariadb  | 2022-06-27 13:18:49 5 [Warning] Access denied for user 'root'@'localhost' (using password: NO)
mariadb  | 2022-06-27 13:18:50 6 [Warning] Access denied for user 'root'@'localhost' (using password: NO)
```

- The `Access denied` message was being printed every second or so, until the container was stopped. 
- I could see the root password was being set, but for some reason, mysql was trying to log in as the root user 
_without_ a password.

#### Solution

After a lot of digging, I realized that the issue was the `FLUSH PRIVILEGES` statement in my custom SQL scripts.
Removing these lines allowed the container to successfully complete it's setup.

**But why?**

If we take a look at the [initialize-db](https://github.com/linuxserver/docker-mariadb/blob/master/root/etc/cont-init.d/40-initialise-db) script
from `linuxserver/mariadb`, we can see that the script creates a `tempSqlFile` file, and appends a lot of SQL to it:
- Creation of system tables
- Your custom SQL from `initdb.d`
- SQL to add a password to the MySQL root user.

The script is run as the mysql root user, but importantly, this user *doesn't have a password set on initial run*. Even after a password is 
set it the DB for the root user, it doesn't take effect until either `FLUSH PRIVILEGES` is called, or MySQL is restarted.

The script then continues on, and [pools MySQL](https://github.com/linuxserver/docker-mariadb/blob/master/root/etc/cont-init.d/40-initialise-db#L4-L13) to see when everything has completed,
using the root user without a password.

Since my scripts were calling `FLUSH PRIVILEGES`, the root password **was** taking effect, and this status check was failing.
Removing the `FLUSH PRIVILEGES` line allowed the status check to run without the need for the root user's password.

Even though we removed the `FLUSH PRIVILEGES` line, my users were still being correctly created - the container restarts MySQL
after initialization to make sure everything is set up properly.

Finally, my bootstrapping was working!
