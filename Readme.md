# PHP Ioc

PHP inversion of control (IoC) is Lightweight dependency injection container 

To get started, clone the repository and install the required dependencies using the following commands:

```sh
git clone https://github.com/soulaimaneyahya/php-ioc.git
```

```composer
composer install
```

PHP Ioc's current features are :

* [x] classpath scanning
* [x] autowiring beans

Check out our demo project for an example of how to use PHP Ioc:

```php
$app = MultiChatServiceProvider::getInstance();
$app->scanDirectory(__DIR__ . '/app/Services/');
```

---

Need helps? Reach me out

> Email: soulaimaneyh07@gmail.com

> Linkedin: soulaimane-yahya

All the best :beer:
