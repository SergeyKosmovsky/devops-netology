##Задача 1  
#Используя docker поднимите инстанс MySQL (версию 8). Данные БД сохраните в volume. 
C:\Users\Sergey>docker pull mysql:8.0  
C:\Users\Sergey>docker volume create vol_mysql  
C:\Users\Sergey>docker run --rm --name mysql -e MYSQL_ROOT_PASSWORD=mysql -ti -p 3306:3306 -v vl-mysql:/etc/mysql/ mysql:8.0  
#Изучите бэкап БД и восстановитесь из него.  
C:\Users\Sergey>mysql -uroot -pmysql < test_dump.sql test_db  
#Найдите команду для выдачи статуса БД и приведите в ответе из ее вывода версию сервера БД.  

mysql> \s

mysql  Ver 8.0.28 for Linux on x86_64 (MySQL Community Server - GPL)  
Connection id:          24  
Current database:       test_db  
Current user:           root@localhost  
SSL:                    Not in use  
Current pager:          stdout  
Using outfile:          ''  
Using delimiter:        ;  
Server version:         8.0.28 MySQL Community Server - GPL  
Protocol version:       10  
Connection:             Localhost via UNIX socket  
Server characterset:    utf8mb4  
Db     characterset:    utf8mb4  
Client characterset:    latin1  
Conn.  characterset:    latin1  
UNIX socket:            /var/run/mysqld/mysqld.sock  
Binary data as:         Hexadecimal  
Uptime:                 43 min 54 sec  

Threads: 2  Questions: 137  Slow queries: 0  Opens: 250  Flush tables: 3  Open tables: 168  Queries per second avg: 0.052  

#Подключитесь к восстановленной БД и получите список таблиц из этой БД.  
mysql> show tables;  
+-------------------+  
| Tables_in_test_db |  
+-------------------+  
| orders            |  
+-------------------+  
1 row in set (0.00 sec)  
Приведите в ответе количество записей с price > 300.  
+----------+  
| count(*) |  
+----------+  
|        1 |  
+----------+  
1 row in set (0.00 sec)  

##Задача 2  
#Создайте пользователя test в БД c паролем test-pass, используя:  
- плагин авторизации mysql_native_password  
- срок истечения пароля - 180 дней  
- количество попыток авторизации - 3  
- максимальное количество запросов в час - 100  
- аттрибуты пользователя:  
-- Фамилия "Pretty"  
-- Имя "James"  
- Предоставьте привелегии пользователю test на операции SELECT базы test_db.  
#Используя таблицу INFORMATION_SCHEMA.USER_ATTRIBUTES получите данные по пользователю test и приведите в ответе к задаче.  

mysql> CREATE USER 'test'@'localhost' IDENTIFIED BY 'test-pass';  
Query OK, 0 rows affected (0.12 sec)  

mysql> ALTER USER 'test'@'localhost' ATTRIBUTE '{"fname":"James", "lname":"Pretty"}';  
Query OK, 0 rows affected (0.14 sec)  

mysql> ALTER USER 'test'@'localhost'  
    -> IDENTIFIED BY 'test-pass'  
    -> WITH  
    -> MAX_QUERIES_PER_HOUR 100  
    -> PASSWORD EXPIRE INTERVAL 180 DAY  
    -> FAILED_LOGIN_ATTEMPTS 3 PASSWORD_LOCK_TIME 2;  
Query OK, 0 rows affected (0.30 sec)  

mysql> GRANT Select ON test_db.orders TO 'test'@'localhost';  
Query OK, 0 rows affected, 1 warning (0.10 sec)  
  
mysql> SELECT * FROM INFORMATION_SCHEMA.USER_ATTRIBUTES WHERE USER='test';  
+------+-----------+---------------------------------------+  
| USER | HOST      | ATTRIBUTE                             |  
+------+-----------+---------------------------------------+  
| test | localhost | {"fname": "James", "lname": "Pretty"} |  
+------+-----------+---------------------------------------+  
1 row in set (0.01 sec)  

##Задача 3  
#Установите профилирование SET profiling = 1. Изучите вывод профилирования команд SHOW PROFILES;.  
#Исследуйте, какой engine используется в таблице БД test_db и приведите в ответе.  
#Измените engine и приведите время выполнения и запрос на изменения из профайлера в ответе:  
- на MyISAM  
- на InnoDB  

mysql> SELECT TABLE_NAME,ENGINE,ROW_FORMAT,TABLE_ROWS,DATA_LENGTH,INDEX_LENGTH FROM information_schema.TABLES WHERE table_name = 'orders' and  TABLE_SCHEMA = 'test_db' ORDER BY ENGINE asc;  
+------------+--------+------------+------------+-------------+--------------+  
| TABLE_NAME | ENGINE | ROW_FORMAT | TABLE_ROWS | DATA_LENGTH | INDEX_LENGTH |  
+------------+--------+------------+------------+-------------+--------------+  
| orders     | InnoDB | Dynamic    |          5 |       16384 |            0 |  
+------------+--------+------------+------------+-------------+--------------+  
1 row in set (0.00 sec)  

mysql> ALTER TABLE orders ENGINE = MyISAM;  
Query OK, 5 rows affected (1.36 sec)  
Records: 5  Duplicates: 0  Warnings: 0  

mysql> ALTER TABLE orders ENGINE = InnoDB;  
Query OK, 5 rows affected (1.45 sec)  
Records: 5  Duplicates: 0  Warnings: 0  

mysql> show profiles;  
+----------+------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+  
| Query_ID | Duration   | Query
                                                                                               |  
+----------+------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+  
|        1 | 0.00124175 | SELECT TABLE_NAME,ENGINE,ROW_FORMAT,TABLE_ROWS,DATA_LENGTH,INDEX_LENGTH FROM information_schema.TABLES WHERE table_name = 'orders' and  TABLE_SCHEMA = 'test_db' ORDER BY ENGINE asc |  
|        2 | 1.35479850 | ALTER TABLE orders ENGINE = MyISAM
                                                                                               |  
|        3 | 1.44725775 | ALTER TABLE orders ENGINE = InnoDB
                                                                                               |  
+----------+------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+  
3 rows in set, 1 warning (0.00 sec)  


##Задача 4  
#Изучите файл my.cnf в директории /etc/mysql.  
#Измените его согласно ТЗ (движок InnoDB):  
- Скорость IO важнее сохранности данных  
- Нужна компрессия таблиц для экономии места на диске  
- Размер буффера с незакомиченными транзакциями 1 Мб  
- Буффер кеширования 30% от ОЗУ  
- Размер файла логов операций 100 Мб  
- Приведите в ответе измененный файл my.cnf.  
  
[mysqld]  
pid-file        = /var/run/mysqld/mysqld.pid  
socket          = /var/run/mysqld/mysqld.sock  
datadir         = /var/lib/mysql  
secure-file-priv= NULL  

Скорость IO, 0 - скорость  
innodb_flush_log_at_trx_commit = 0  

Сжатие, Barracuda – сжатие файла  
innodb_file_format = Barracuda  
 
Буфер  
innodb_log_buffer_size	 = 1M  

Кэш  
key_buffer_size = 64М  

Размер лога  
max_binlog_size = 100M  






