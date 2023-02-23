## Deployemnt
* Copy `.env.dist` to `.env`
* Set your environment with `.env`
* (optional) Put your ssl certificates to `nginx/{env}/certs` directory, naming - `cert.pem` and `key.pem` (e.g. to `nginx/dev/certs/cert.pem` and `nginx/dev/certs/key.pem`)
* Run `docker-compose up -d --build`
* Run `docker exec -ti nettetest-mysql bash`
* Run `mysql -uroot -proot`
* Run `create database nette;`
* Run `use nette`
* Run `source /docker-entrypoint-initdb.d/structure.mysql.sql`
* Run `source /docker-entrypoint-initdb.d/countryCityMigration.sql`
* Run `source /docker-entrypoint-initdb.d/demoUsersMigration.sql`

## Configuration
##### PHP
* Create custom config file in `php/{env}/config` (e.g `75-custom-config.php`) to add own configuration to container
##### Nginx
* You can create custom configuration files in `nginx/{env}/config`. All `*.conf` files from this directory will be imported.
##### MySQL
* You can create custom configuration files in `mysql/config`. All `*.cnf` files from this directory will be imported.

##### Generating trusted ssl certificate for local environment
* `mkcert` tool allow generating trusted localhost ssl certificates
* Install `mkcert`:
`````
wget https://github.com/FiloSottile/mkcert/releases/download/v1.1.2/mkcert-v1.1.2-linux-amd64
mv mkcert-v1.1.2-linux-amd64 mkcert
chmod +x mkcert
cp mkcert /usr/local/bin/
mkcert -install
`````
* Generate certificates:
`````
mkcert "your.domain.local" localhost 127.0.0.1 ::1
`````
