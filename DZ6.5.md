# Задача 1
В этом задании вы потренируетесь в:  
- установке elasticsearch  
- первоначальном конфигурировании elastcisearch  
- запуске elasticsearch в docker  

```
[elasticsearch@28313680af66 /]$ curl -ku elastic https://localhost:9200  
Enter host password for user 'elastic':  
{  
  "name" : "28313680af66",  
  "cluster_name" : "netology_test",  
  "cluster_uuid" : "SugFAWFdTGm0IkMRb8ovag",  
  "version" : {  
    "number" : "8.1.0",  
    "build_flavor" : "default",  
    "build_type" : "tar",  
    "build_hash" : "3700f7679f7d95e36da0b43762189bab189bc53a",  
    "build_date" : "2022-03-03T14:20:00.690422633Z",  
    "build_snapshot" : false,  
    "lucene_version" : "9.0.0",  
    "minimum_wire_compatibility_version" : "7.17.0",  
    "minimum_index_compatibility_version" : "7.0.0"  
  },  
  "tagline" : "You Know, for Search"  
}  
```

# Задача 2  

В этом задании вы научитесь:  
- создавать и удалять индексы  
- изучать состояние кластера  
- обосновывать причину деградации доступности данных  
## Ознакомтесь с документацией и добавьте в elasticsearch 3 индекса, в соответствии со таблицей:  

| Имя       | Количество реплик                | Количество шард |
| ------------- |:------------------:| -----:|
| ind-1     | 0    | 1 |
| ind-2     | 1 |   2 |
| ind-3     | 2 |   4 |

```
$ curl -ku elastic -X PUT "https://localhost:9200/ind-1" -H 'Content-Type: application/json' -d'  
	> {  
	>   "settings": {  
	>     "index": {  
	>       "number_of_shards": 1,  
	>       "number_of_replicas": 0  
	>     }  
	>   }  
	> }  
	> '  
	Enter host password for user 'elastic':  
	{"acknowledged":true,"shards_acknowledged":true,"index":"ind-1"}  
```
Повторяем ещё два раза меняя имя, количество реплик и шард.  


## Получите список индексов и их статусов, используя API и приведите в ответе на задание.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic  'https://localhost:9200/_cat/indices?v'
Enter host password for user 'elastic':
health status index uuid                   pri rep docs.count docs.deleted store.size pri.store.size
green  open   ind-1 6Q111iHRSY68GptbfqPPAQ   1   0          0            0       225b           225b
yellow open   ind-3 ON-qxK8rSAKrlDa3Mwzyww   4   2          0            0       900b           900b
yellow open   ind-2 w3dB9g25SHKQEqu-d9xCnQ   2   1          0            0       450b           450b
```

## Получите состояние кластера elasticsearch, используя API.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic  'https://localhost:9200/_cluster/health/ind-1?pretty'
Enter host password for user 'elastic':
{
  "cluster_name" : "netology_test",
  "status" : "green",
  "timed_out" : false,
  "number_of_nodes" : 1,
  "number_of_data_nodes" : 1,
  "active_primary_shards" : 1,
  "active_shards" : 1,
  "relocating_shards" : 0,
  "initializing_shards" : 0,
  "unassigned_shards" : 0,
  "delayed_unassigned_shards" : 0,
  "number_of_pending_tasks" : 0,
  "number_of_in_flight_fetch" : 0,
  "task_max_waiting_in_queue_millis" : 0,
  "active_shards_percent_as_number" : 100.0
}
```

## Как вы думаете, почему часть индексов и кластер находится в состоянии yellow?
- Реплик нет, следовательно реплицировать не куда, по этому два индекса жёлтые.


## Удалите все индексы.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic  -X DELETE https://localhost:9200/ind-1
Enter host password for user 'elastic':
{"acknowledged":true}
```
И так три раза меняя имя


# Задача 3  
В данном задании вы научитесь:  
- создавать бэкапы данных  
- восстанавливать индексы из бэкапов  

## Используя API зарегистрируйте данную директорию как snapshot repository c именем netology_backup.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic -X PUT "https://127.0.0.1:9200/_snapshot/netology_backup?pretty" -H 'Content-Type: application/json' -d'
> {
>   "type": "fs",
>   "settings": {
>     "location": "/elasticsearch-8.1.0/snapshots"
>   }
> }
> '
Enter host password for user 'elastic':
{
  "acknowledged" : true
```

## Приведите в ответе запрос API и результат вызова API для создания репозитория.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic 'https://localhost:9200/_snapshot/netology_backup?pretty'
Enter host password for user 'elastic':
{
  "netology_backup" : {
    "type" : "fs",
    "settings" : {
      "location" : "/elasticsearch-8.1.0/snapshots"
    }
  }
}
```

## Создайте индекс test с 0 реплик и 1 шардом и приведите в ответе список индексов.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic -X PUT https://localhost:9200/test -H 'Content-Type: application/json' -d'
> { "settings":
>   { "number_of_replicas": 0,
>     "number_of_shards": 1 }
>
>   }
> '
Enter host password for user 'elastic':
{"acknowledged":true,"shards_acknowledged":true,"index":"test"}

[elasticsearch@28313680af66 /]$ curl -ku elastic 'https://localhost:9200/_cat/indices?v'
Enter host password for user 'elastic':
health status index uuid                   pri rep docs.count docs.deleted store.size pri.store.size
green  open   test  M1euoE_gT4yLW6ZczPbovg   1   0          0            0       225b           225b
```


## Создайте snapshot состояния кластера elasticsearch.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic -X PUT https://localhost:9200/_snapshot/netology_backup/ela
sticsearch?wait_for_completion=true
Enter host password for user 'elastic':
{"snapshot":{"snapshot":"elasticsearch","uuid":"N3Zt4FP9QZSyQGqGrGjz9Q","repository":"netology_backup","version_id":8010099,"version":"8.1.0","indices":[".geoip_databases",".security-7","test"],"data_streams":[],"include_global_state":true,"state":"SUCCESS","start_time":"2022-03-18T18:30:00.738Z","start_time_in_millis":1647628200738,"end_time":"2022-03-18T18:30:04.341Z","end_time_in_millis":1647628204341,"duration_in_millis":3603,"failures":[],"shards":{"total":3,"failed":0,"successful":3},"feature_states":[{"feature_name":"geoip","indices":[".geoip_databases"]},{"feature_name":"security","indices":[".security-7"]}]}}
```

## Приведите в ответе список файлов в директории со snapshotами.
```
[elasticsearch@28313680af66 /]$ ls -la /elasticsearch-8.1.0/snapshots/
total 48
drwxr-xr-x 1 elasticsearch elasticsearch  4096 Mar 18 18:30 .
drwxr-xr-x 1 elasticsearch elasticsearch  4096 Mar 16 18:33 ..
-rw-r--r-- 1 elasticsearch elasticsearch  1098 Mar 18 18:30 index-0
-rw-r--r-- 1 elasticsearch elasticsearch     8 Mar 18 18:30 index.latest
drwxr-xr-x 5 elasticsearch elasticsearch  4096 Mar 18 18:30 indices
-rw-r--r-- 1 elasticsearch elasticsearch 18399 Mar 18 18:30 meta-N3Zt4FP9QZSyQGqGrGjz9Q.dat
-rw-r--r-- 1 elasticsearch elasticsearch   390 Mar 18 18:30 snap-N3Zt4FP9QZSyQGqGrGjz9Q.dat
```

## Удалите индекс test и создайте индекс test-2. Приведите в ответе список индексов.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic -X DELETE 'https://localhost:9200/test?pretty'
Enter host password for user 'elastic':
{
  "acknowledged" : true
}
[elasticsearch@28313680af66 /]$ curl -ku elastic -X PUT https://localhost:9200/test-2 -H 'Content-Type: appl
ication/json' -d' { "settings": { "number_of_replicas": 0, "number_of_shards": 1 } } '
Enter host password for user 'elastic':
{"acknowledged":true,"shards_acknowledged":true,"index":"test-2"}[elasticsearch@28313680af66 /]$
[elasticsearch@28313680af66 /]$ curl -ku elastic -X GET 'https://localhost:9200/_cat/indices?v'
Enter host password for user 'elastic':
health status index  uuid                   pri rep docs.count docs.deleted store.size pri.store.size
green  open   test-2 LWP7VDq4QRiiQ0i2VztjTg   1   0          0            0       225b           225b
```

## Восстановите состояние кластера elasticsearch из snapshot, созданного ранее.
```
[elasticsearch@28313680af66 /]$ curl -ku elastic -X POST 'https://localhost:9200/_snapshot/netology_backup/e
lasticsearch/_restore?wait_for_completion=true'
Enter host password for user 'elastic':
{"snapshot":{"snapshot":"elasticsearch","indices":["test"],"shards":{"total":1,"failed":0,"successful":1}}}
```

## Приведите в ответе запрос к API восстановления и итоговый список индексов.
```
elasticsearch@28313680af66 /]$ curl -ku elastic -X GET 'https://localhost:9200/_cat/indices?v'
Enter host password for user 'elastic':
health status index  uuid                   pri rep docs.count docs.deleted store.size pri.store.size
green  open   test-2 LWP7VDq4QRiiQ0i2VztjTg   1   0          0            0       225b           225b
green  open   test   b4AZAHezSPCQJ38BvA90Uw   1   0          0            0       225b           225b
```




