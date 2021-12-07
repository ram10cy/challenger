<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Software

This sowtware builded with laravel 8.74.0 

## Case

Estate agnecies need to arrange their apointments according to their free time. This software provides estate agencies that when they need to leave office for meeting and when they ll be back to office again according to meeting time and meeting address. 

## Used API

- Postcodes.io
- Google Distance Matrix Api
- JWT Api Package for laravel

### Setup
- !Composer needs to be installed your computer!
- Copy folder to local folder
- run command: composer update
- create .env  file according to your DB settings (you can copy from env.example)
- run command for generate app key: php artisan key:generate
- run command migrate database: php artisan migrate 
- run command for generating jwt secret key: php artisan jwt:generate 
- run command to start app: php artisan serve
- you can send request 

## More Information

This software only api support, there is no web view. You can use postman to check/use api.
for any question yau can free to send  mail :ram10cy@gmail.com


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
