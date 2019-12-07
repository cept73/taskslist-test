# beejee-test


USED:
--------------------------------------------------------------------

THEME: https://startbootstrap.com/themes/sb-admin-2/

LIBRARIES:
    * vlucas/dotenv
    * simplon/mysql
    * jessengers/blade


INSTALLATION
----------------------------

1) Create in database table with any name, for example 'tasks_list'
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

2) Create .env file in the root directory, with secret content:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=myPassword123
DB_NAME=test
DB_TABLE=tasks_list
```

3) Check config/ files, tune if needed

4) Set write permissions for web-server to cache folder
For simplicity you may use:
```
chmod 777 cache
```

