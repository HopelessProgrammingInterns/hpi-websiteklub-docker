# Low Level Architecture

The first station for every request is a nginx reverse proxy (config is found in `nginx-main-config/default`).

Every service that is running on the server (except for the nginx proxy) is listed in `docker-compose.yml`, which is a definition file for [Docker Compose](https://docs.docker.com/compose/compose-file/). Most share a common mysql service running in one docker container (`maindb`).

Each of those is accessible via a port, starting from 8000 and incrementing. The nginx proxy forwards proper paths to those ports, e.g. `/klub/zeitung` to `localhost:8002`.

# Architecture from User Site

TODO Auth

Central point for visitors is the `frame` service, that displays the navigation top bar containing links to other services (e.g. FSR or club sites) or embeds them directly (e.g. etherpad or 120/180).

All static pages are served from `frame/static` and selected via `frame/index.php`.

# This Repo's Contents

* `frame`: The PHP Code for the landing page and all static pages.
* `nginx-main-conf`: nginx config file that is symlinked to `/etc/nginx/sites-enabled`. Home of all forwards from user visible links to ports of services.
* `wordpress`: Slightly modified docker image for wordpress (allows to modify path at which the wordpress installation is run).
* `docker-compose.yml`: Definitions of all services running on the server.
* `docker-common.env`: Helper file for the .yml, containing env var definitions shared by services.

# How To ...

### Add Another Wordpress Site

1. Create a copy of a Wordpress site in `docker-common.yml`
2. Adjust service name, `WORDPRESS_PATH ` and `WORDPRESS_DB_NAME`
3. Assign max of all port numbers plus one to port
4. In `nginx-main-conf/default`, add a `proxy_pass` with the set path and port.
6. Update docker-compose (`docker-compose up -d {your service's name}`)
7. Reload nginx (`nginx -s reload`)

### Add a Link To The Navbar or Club List

1. Open `docker-common.yml`.
2. Locate the `frame` service and add a new LINK or CLUB item. Avoid duplicate numbers. Items are ordered in ascending order of their number suffix.
3. Restart the `frame` service (`docker-compose up -d frame`)

### Add a New Static Page

1. Create a php/html page in `frame/static/`.
2. Add another `case '/your-path'` in `frame/index.php` that includes your file.

### Static Resources

All static resources can be placed in the `frame/static-files` folder, from which they wil be directly accessible.

### Create a database backup

TODO

# When deploying...

* Absolutely make sure the **passwords in `docker-common.env` are changed** on the server
* Adjust the path in `nginx-main-conf/default` to point to the location of the `docker-compose.yml` script
* Setup a nginx that imports the `nginx-main-conf/default` script

# Services

- [ ] QuoteDB
- [ ] Studix
- [ ] LVDB
- [x] 120
- [x] 180
- [ ] Etherpad
- [ ] Sport Reservierungen
- [x] Filmklub Blog (Last Update: Jul 2014)
- [x] Zeitungsklub Blog (neu)
- [x] Verlinkung Connect Klub Blog
- [ ] Jabber
- [x] Geocaching
- [ ] Auth: Basic Auth via HPI Kerberos
- [x] FSR kriegt übersichtsseite, navbar
- [x] Verlinkung Redmine

# TODO
* forbid access to all files in `frame/*/*` except for navbar
