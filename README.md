# qrapil

### Requirements

  * PHP 7.0
  * Mysql
  * [Composer](https://getcomposer.org/) - Dependency manager
  
# Install 

Run the following commands in a terminal, where you want to clone Nanoe:
    
```sh
$ git clone git@github.com:Mledev/qrapil.git
$ composer install
```

## Database Install and Configuration

To install the database, run: 
```sh
$ bin/console doctrine:database:create
$ bin/console doctrine:migrations:migrate
```

Whenever you modify the mapping files, run this after your modifications:
```sh
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
```

# API

##	POST /api/login

### Input

* Email string 300
* Password hashed sha512 string 

```json
{
	"Email": "1234567890123456789012345678901234567890",
	"Password": "1234567890123456789012345678901234567890"
}
```

### Return

* Token string 40

```json
{
	"token": "1234567890123456789012345678901234567890"
}
```

##	POST /api/refreshToken

### Input:

* token

```json
{
	"token": "1234567890123456789012345678901234567890"
}
```

### Return:

* token

```json
{
	"token": "1234567890123456789012345678901234567890"
}
```

##	GET /api/getLocation (salle prévue)

### Input

* token string 40

```json
{
	"token": "1234567890123456789012345678901234567890"
}
```


### Return

* date string ISO 8601
* location string 40

```json
{
	"date": "",
	"location": "Salle 7"
}
```





