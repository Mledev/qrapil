# qrapil

### Requirements

  * PHP 7.0
  * Mysql
  * [Composer](https://getcomposer.org/) - Dependency manager
  
# Install 

Run the following commands in a terminal, where you want to clone qrapil:
    
```sh
$ git clone https://github.com/Mledev/qrapil.git
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
	"Token": "1234567890123456789012345678901234567890"
}
```

##	POST /api/refreshToken

### Input:

* token

```json
{
	"Token": "1234567890123456789012345678901234567890"
}
```

### Return:

* token

```json
{
	"Token": "1234567890123456789012345678901234567890"
}
```

##	GET /api/getLocation (salle prévue)

### Input

* token string 40

```json
{
	"Token": "1234567890123456789012345678901234567890"
}
```


### Return

* date string ISO 8601
* location string 40

```json
{
	"Date": "",
	"Location": "Salle 7"
}
```

<!--##	POST /api/checkIn

### Input

* QRCodeData string 40
* date string ISO 8601
* beaconCollection \[int\]
* Token string 40

```json
{
	"QRCodeData" : "OK",
	"date": "",
	"beaconCollection":
		[
			12,
			44, 
			128
		]
	},
	"Token": "1234567890123456789012345678901234567890"
}
```

### Return

* String status
	* HTTP status 200 "OK"
	* HTTP status 404 "KO"

200
```json
{
	"Response" : "OK"
}
```

404
```json
{
	"Response" : "KO"
}
```


# Web

## GET /

Select a location. Posts to /getQRCode

## POST /getQRCode

### input

* location string

Displays a QRCode relevent to the location and refreshes every 30 seconds.-->




