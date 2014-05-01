Keepict
=======
Version : 1.0 
UQAC - H2014
=======
### Settings
1. Import `database.sql` into phpMyAdmin

2. Change __dbname__ in `Keepict/config/autoload/global.php` :
    ```php
    <?php

    return array(
        'db' => array(
            'driver'         => 'Pdo',
            'dsn'            => 'mysql:dbname=__xxxxxxx__;host=localhost',
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ),
        ),
        ...
    );
    ```

3. Replace `xxxx` by your MySQL connexion informations in `Keepict/config/autoload/local.php` :
    ```php
    <?php
    return array(
        'db' => array(
            'username' => 'xxxx',
            'password' => 'xxxx',
        ),
    );
    ```
    
4. Configure the informations of your email account provider in `Keepict/module/Keepict/src/Keepict/Classes/Mailer.php` :
    ```php
    <?php
    ...
    class Mailer
    {
        private $NAME = 'xxxxx';
        private $HOST = 'xxxxx';
        private $PORT = xxxxx;
        private $USERNAME = 'xxxxx';
        private $PASSWORD = 'xxxxx';
        ...
    }
    ```
=======