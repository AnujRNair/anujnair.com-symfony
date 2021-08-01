# Using Docker Secrets with a VaultWarden / MySQL Setup

Recently, I migrated my VaultWarden instance to use a MySQL backend, but couldn't find a way to configure MySQL in my `docker-compose.yml` file using docker secrets. 

Without this in place, my VaultWarden password would have been in plaintext in my `docker-compose.yml` file, and checked into source control, which is far from ideal.

#### Background

[The VaultWarden wiki](https://github.com/dani-garcia/vaultwarden/wiki/Using-the-MariaDB-%28MySQL%29-Backend) gives us an example docker-compose service entry for using MySQL (via mariadb). In there, it lists the following:

```bash
 vaultwarden:
  image: "vaultwarden/server:latest"
  container_name: "vaultwarden"
  ...
  environment:
   - "DATABASE_URL=mysql://<vaultwarden_user>:<vaultwarden_pw>@mariadb/vaultwarden_db"
```

You can see that you would have to set your password in plain text here.

I wanted to use docker secrets to store my MySQL password so that I could continue to check my `docker-compose.yml` file into source control, however, I couldn't find a way to replace this variable in the `docker-compose.yml` that the VaultWarden image would accept.

Every time I tried and inspected the logs with `docker logs -f vaultwarden`, I would get errors which looked like the following:

```bash
[vaultwarden::util][WARN] Can't connect to database, retrying: DieselCon.
[CAUSE] InvalidConnectionUrl(
    "MySQL connection URLs must be in the form `mysql://[[user]:[password]@]host[:port][/database]`",
)
```

or

```bash
[vaultwarden::util][WARN] Can't connect to database, retrying: DieselCon.
[CAUSE] InvalidConnectionUrl(
    "MySQL connection URLs must be in the form `mysql://[[user]:[password]@]host[:port][/database]`",
)
```

Both of these errors suggested that the docker secret wasn't properly being used to create the `DATABASE_URL` connection string needed.

#### Solution

Instead, I found an undocumented feature of VaultWarden that could help me here.

[By looking at the script which VaultWarden initially runs](https://github.com/dani-garcia/vaultwarden/blob/main/docker/start.sh#L11-L15), I noticed there was a section where `.sh` files were read from a specific directory and run.

I created a new `scripts` directory, and exposed this to VaultWarden by adding it into the `volumes` section of the service, mapping it to `/etc/vaultwarden.d`:

```bash
volumes:
  - /path/to/config/vaultwarden:/data                      # where my persisted data already lives
  - /path/to/config/vaultwarden/scripts:/etc/vaultwarden.d # the new scripts directory, mapped to /etc/vaultwarden.d
```

I created a docker secret and exposed it to my service. I've done this using a file, but you could also do so using [`docker secret create`](https://docs.docker.com/engine/reference/commandline/secret_create/) as well:

```bash
secrets:
  vaultwarden_mysql_password:
    file: /path/to/secrets/vaultwarden/mysql_password

services:
  vaultwarden:
    container_name: vaultwarden
    image: vaultwarden/server:latest
    secrets:
      - vaultwarden_mysql_password
    ...
```

Finally, I created a bash script called `database_url.bash` in the `scripts` folder to construct the `DATABASE_URL` environment variable, and I removed the original `DATABASE_URL` from my `docker-compose` file:

```bash
export DATABASE_URL=mysql://vaultwarden-un:$(cat /run/secrets/vaultwarden_mysql_password)@host:port/db
```

Remember to replace `vaultwarden-un`, `host`, `port` and `db` with your own information

The final docker-compose file looked like this:

```bash
secrets:
  vaultwarden_mysql_password:
    file: /path/to/secrets/vaultwarden/mysql_password

services:
  mariadb:
    container_name: mariadb
    ...

  vaultwarden:
    container_name: vaultwarden
    image: vaultwarden/server:latest
    secrets:
      - vaultwarden_mysql_password
    volumes:
      - /path/to/config/vaultwarden:/data                      # where my persisted data already lives
      - /path/to/config/vaultwarden/scripts:/etc/vaultwarden.d # the new scripts directory, mapped to /etc/vaultwarden.d
    environment:
      - WEBSOCKET_ENABLED=true
    ...
```

Notice there is no `DATABASE_URL` listed under `environment` anymore - it will be set from the `database_url.bash` script instead.

After starting VaultWarden up and checking the logs, everything worked as expected! I could double confirm that everything looked good by going to the admin VaultWarden page (`/admin`), opening the `Read-Only Config` section, and confirming that the `Database URL` section was properly constructed with my MySQL password.

Now I can safely check in my `docker-compose.yml` and the new bash file I created, without checking in my password as well. 
