<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Dokumentasi

Backend ini saya buat dengan menggunakan framework Laravel 10

Dependencies yang di gunakan

-   [guzzlehttp/guzzle](https://github.com/guzzle/guzzle.git).
-   Amazon S3 Object storage [league/flysystem-aws-s3-v3](https://github.com/thephpleague/flysystem-aws-s3-v3.git).

## Run

-   update / install composer
    ```sh
    composer update
    ```
-   buat file .env dengan cara copy file .env.example



lalu masukkan access key AWS di atas

-   buat secret key laravel dengan menjalankan
    ```sh
    php artisan key:generate
    ```
-   buat tabel dan seeder dengan menjalankan
    ```sh
    php artisan migrate:fresh --seed
    ```
-   menjalankan program
    ```sh
    php artisan serve
    ```

## Endpoind dan Dokumentasi Postman

[Berikut endpoint dan dokumentasi request](https://documenter.getpostman.com/view/15005997/2s9Ykod1qq).
