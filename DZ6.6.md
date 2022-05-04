# Задача 1  

Перед выполнением задания ознакомьтесь с документацией по администрированию MongoDB.  
Пользователь (разработчик) написал в канал поддержки, что у него уже 3 минуты происходит CRUD операция в MongoDB и её нужно прервать.  
Вы как инженер поддержки решили произвести данную операцию:  
- напишите список операций, которые вы будете производить для остановки запроса пользователя  
- предложите вариант решения проблемы с долгими (зависающими) запросами в MongoDB  

Через команду db.currentOp() определяем текущую операцию и завершаем её db.killOp()  
Чтобы решить проблемы с долгими запросами можно испольщовать лимит на выполнение операции или построить/перестроить связанный индекс.  



# Задача 2

Перед выполнением задания познакомьтесь с документацией по Redis latency troobleshooting.  

Вы запустили инстанс Redis для использования совместно с сервисом, который использует механизм TTL. Причем отношение количества записанных key-value значений к количеству истёкших значений есть величина постоянная и увеличивается пропорционально количеству реплик сервиса.  

При масштабировании сервиса до N реплик вы увидели, что:  
- сначала рост отношения записанных значений к истекшим  
- Redis блокирует операции записи  
Как вы думаете, в чем может быть проблема?  


Судя по документации, блокировка записи может быть вызвана процессом сброса буфера записи на диск:

Latency due to AOF and disk I/O

"Another source of latency is due to the Append Only File support on Redis. The AOF basically uses two system calls to accomplish its work. One is write(2) that is used in order to write data to the append only file, and the other one is fdatasync(2) that is used in order to flush the kernel file buffer on disk in order to ensure the durability level specified by the user."

Возможо блокировка записи может быть вызвана большим количеством удаляемых ключей - операция записи блокируется до завершения процесса удаления ключей:

"if the database has many many keys expiring in the same second, and these make up at least 25% of the current population of keys with an expire set, Redis can block in order to get the percentage of keys already expired below 25%.

This approach is needed in order to avoid using too much memory for keys that are already expired, and usually is absolutely harmless since it's strange that a big number of keys are going to expire in the same exact second, but it is not impossible that the user used EXPIREAT extensively with the same Unix time.

In short: be aware that many keys expiring at the same moment can be a source of latency."


# Задача 3

Вы подняли базу данных MySQL для использования в гис-системе. При росте количества записей, в таблицах базы, пользователи начали жаловаться на ошибки вида:  

```
InterfaceError: (InterfaceError) 2013: Lost connection to MySQL server during query u'SELECT..... '
```

Как вы думаете, почему это начало происходить и как локализовать проблему?

Какие пути решения данной проблемы вы можете предложить?


Если верить документации, виновато огромное количество строк. Для решения нужно увеличить параметр net_read_timeout.  



# Задача 4

Перед выполнением задания ознакомтесь со статьей Common PostgreSQL errors из блога Percona.

Вы решили перевести гис-систему из задачи 3 на PostgreSQL, так как прочитали в документации, что эта СУБД работает с большим объемом данных лучше, чем MySQL.  

После запуска пользователи начали жаловаться, что СУБД время от времени становится недоступной. В dmesg вы видите, что:  

```
postmaster invoked oom-killer
```

Как вы думаете, что происходит?

Как бы вы решили данную проблему?



Процесс СУБД был прекращен из-за превышения имеющегося количества памяти процессом ядра OOM-killer. В статье приводится развернутое объяснение проблемы и предложены методы решения, например, модифицировать параметр процесса СУБД oom_score_adj

"If you really want your process not to be killed by OOM-Killer, then there is another kernel parameter oom_score_adj. You can add a big negative value to that to reduce the chance your process gets killed."

Для оптимизации использования памяти Postgres предлагается настроить следующие параметры файла конфигурации postgresql.conf

Для уменьшения размера потребляемой СУБД ОЗУ, в порядке приоритета, нужно уменьшить значения параметра effective_cache_size, затем shared_buffers. Типичные значения параметров work_mem и maintenance_work_mem, по-видимому, пренебрежимо малы по сравнению с типичным размером ОЗУ сервера.
