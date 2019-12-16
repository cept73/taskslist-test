# beejee-test


About:
---------------------------

Simple Tasks list site on pure PHP.
For authorization: admin 123 (admin may edit old tasks and set completed flag)
Unauthorized users may add tasks too
In list we might see automatic flag "edited by admin" too.

Requirements:
---------------------------

* PHP 7
* MySQL
* Composer
* jQuery
* Theme: https://startbootstrap.com/themes/sb-admin-2/

Libraries:
---------------------------

* vlucas/dotenv
* simplon/mysql
* jessengers/blade

INSTALLATION
----------------------------

1️⃣ Create table in database with any name, for example 'tasks_list'
```
    CREATE TABLE `tasks_list` (
        `id` MEDIUMINT NOT NULL AUTO_INCREMENT,
        `task` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
        `text` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `completed` BOOL default false,
        `admin_edit` BOOL default false,
        PRIMARY KEY (`id`),
        CONSTRAINT `tasks_list_UN` UNIQUE KEY (`task`,`text`,`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
```

2️⃣ Create .env file in the root directory with secret content:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=myPassword123
DB_NAME=test
DB_TABLE=tasks_list
```

3️⃣ Check config/ files, tune if needed

4️⃣ Set write permissions for web-server to cache folder
For simplicity you may use:
```
chmod 777 cache
```

