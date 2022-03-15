Задача 1
Используя docker поднимите инстанс PostgreSQL (версию 13). Данные БД сохраните в volume.
Подключитесь к БД PostgreSQL используя psql.
C:\Users\Sergey>docker pull postgres:13
C:\Users\Sergey>volume create vl-postgres
C:\Users\Sergey>docker run --rm --name postgres -e POSTGRES_PASSWORD=postgres -ti -p 5432:5432 -v vl-postgres:/var/lib/postgresql/data postgres:13
C:\Users\Sergey\Temp>docker exec -it pg-docker bash
root@663f335ed583:/# psql -h localhost -p 5432 -U postgres -W
Password:
psql (13.6 (Debian 13.6-1.pgdg110+1))
Воспользуйтесь командой \? для вывода подсказки по имеющимся в psql управляющим командам.
Найдите и приведите управляющие команды для:
вывода списка БД
postgres=# \lподключения к БД
вывода списка таблиц
postgres=# \dtS
вывода описания содержимого таблиц
postgres=# \dS+ pg_index
выхода из psql
postgres=# \q

Задача 2
Используя psql создайте БД test_database.
Изучите бэкап БД.
Восстановите бэкап БД в test_database.
postgres=# CREATE DATABASE test_database;
CREATE DATABASE
postgres=# exit
root@663f335ed583:/var/run/postgresql# psql -U postgres -f ./test_dump.sql test_database
Подключитесь к восстановленной БД и проведите операцию ANALYZE для сбора статистики по таблице.
test_database=# ANALYZE VERBOSE public.orders;
INFO:  analyzing "public.orders"
INFO:  "orders": scanned 1 of 1 pages, containing 8 live rows and 0 dead rows; 8 rows in sample, 8 estimated total rows
ANALYZE

Используя таблицу pg_stats, найдите столбец таблицы orders с наибольшим средним значением размера элементов в байтах.
test_database=# select avg_width from pg_stats where tablename='orders';
 avg_width 
-----------
         4
        16
         4
(3 rows)

Задача 3
Архитектор и администратор БД выяснили, что ваша таблица orders разрослась до невиданных размеров и поиск по ней занимает долгое время. Вам, как успешному выпускнику курсов DevOps в нетологии предложили провести разбиение таблицы на 2 (шардировать на orders_1 - price>499 и orders_2 - price<=499).

Предложите SQL-транзакцию для проведения данной операции.
test_database=# alter table orders rename to orders_copy;
ALTER TABLE
test_database=# create table orders (id integer, title varchar(80), price integer) partition by range(price);
CREATE TABLE
test_database=# create table orders_1 partition of orders for values from (0) to (499);
CREATE TABLE
test_database=# create table orders_2 partition of orders for values from (499) to (1000000);
CREATE TABLE
test_database=# insert into orders (id, title, price) select * from orders_copy;
INSERT 0 8
test_database=# \dt
                  List of relations
 Schema |    Name     |       Type        |  Owner
--------+-------------+-------------------+----------
 public | orders      | partitioned table | postgres
 public | orders_1    | table             | postgres
 public | orders_2    | table             | postgres
 public | orders_copy | table             | postgres
(4 rows)
Можно ли было изначально исключить "ручное" разбиение при проектировании таблицы orders?
Да, создавать её изначально секционированной.


Задача 4
Используя утилиту pg_dump создайте бекап БД test_database.
root@663f335ed583:/var/run/postgresql# pg_dump -U postgres -d test_database >test_database_dump.sql
root@663f335ed583:/var/run/postgresql# ls
test_database_dump.sql  test_dump.sql
Как бы вы доработали бэкап-файл, чтобы добавить уникальность значения столбца title для таблиц test_database?
За уникальность данных отвечают ключи, так что необходимо обратиться к ним. 
REATE INDEX ON orders ((lower(title)));





