## online-visitor

#In Laravel 5, 6, 7 


Install via composer
```bash
composer require sraban/online-visitor
```

set the database configuration in laravel project & migrate using  cli
```bash
php artisan migrate
```

Go to `http://myapp/input/console`

Web Console Looks like this:

![Web Console Screen](https://imagesk.github.io/1/employe-console.png)


In Ubuntu shell

```sh
php artisan ov
```


If command not availalbe in list or any problem, Add the namespace in Console/Kernel.php in Laravel

```sh
protected $commands = [
        'Sraban\OnlineVisitor\Commands\OnlineVisitorCommand'
    ];
```

Terrminal Looks like this:

![Shell Screen](https://imagesk.github.io/1/employee-cli.png)

