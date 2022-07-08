# Задача 1
## Дайте письменые ответы на следующие вопросы:

- В чём отличие режимов работы сервисов в Docker Swarm кластере: replication и global?

Для реплицированного сервиса указывается, сколько идентичных задач нужно запустить. Например, развернуть сервис HTTP с тремя репликами, каждая из которых обслуживает один и тот же контент.
Глобальный сервис — это сервис, который запускает одну задачу на каждой ноде. Предварительно заданного количества задач нет. Каждый раз, когда вы добавляется нода в swarm, оркестратор создает задачу, а планировщик назначает задачу новой ноде.

- Какой алгоритм выбора лидера используется в Docker Swarm кластере?

В кластере могут одновременно работать несколько управляющих нод, которые могут в любой момент заменить вышедшего из строя лидера. Алгоритм поддержания распределенного консенсуса — Raft.

- Что такое Overlay Network?

Overlay Network создает распределенную сеть между несколькими узлами демона Docker. Эта сеть находится поверх (перекрывает) сети, специфичные для хоста, позволяя контейнерам, подключенным к ней (включая контейнеры службы swarm), безопасно обмениваться данными при включенном шифровании. Docker прозрачно обрабатывает маршрутизацию каждого пакета от и к правильному хосту демона Docker и правильному контейнеру назначения.



# Задача 2
## Создать ваш первый Docker Swarm кластер в Яндекс.Облаке

Для получения зачета, вам необходимо предоставить скриншот из терминала (консоли), с выводом команды:

docker node ls

```
[centos@node01 ~]$ sudo docker node ls
ID                            HOSTNAME             STATUS    AVAILABILITY   MANAGER STATUS   ENGINE VERSION
zxpxmss2w3pvzy00hey0cionq *   node01.netology.yc   Ready     Active         Leader           20.10.17
iofpws6twp8d9e2lgst6hr5d0     node02.netology.yc   Ready     Active         Reachable        20.10.17
n4e1i5x9c5cpvglh16nh7vxp6     node03.netology.yc   Ready     Active         Reachable        20.10.17
zi7ta0a5qmzgp2uepudmdt2xj     node04.netology.yc   Ready     Active                          20.10.17
ozise67cb6ffylrz7bjet9uoh     node05.netology.yc   Ready     Active                          20.10.17
q92jsj6wmq57jm1id4vzexuxh     node06.netology.yc   Ready     Active                          20.10.17
```

# Задача 3
## Создать ваш первый, готовый к боевой эксплуатации кластер мониторинга, состоящий из стека микросервисов.

Для получения зачета, вам необходимо предоставить скриншот из терминала (консоли), с выводом команды:

docker service ls

```
[centos@node01 ~]$ sudo docker service ls
ID             NAME                                MODE         REPLICAS   IMAGE                                          PORTS
08pjvq1i1f93   swarm_monitoring_alertmanager       replicated   1/1        stefanprodan/swarmprom-alertmanager:v0.14.0
33u044bnx4m0   swarm_monitoring_caddy              replicated   1/1        stefanprodan/caddy:latest                      *:3000->3000/tcp, *:9090->9090/tcp, *:9093-9094->9093-9094/tcp
ulnpywgrrekq   swarm_monitoring_cadvisor           global       6/6        google/cadvisor:latest
w1limyihqi1x   swarm_monitoring_dockerd-exporter   global       6/6        stefanprodan/caddy:latest
a45uhje8p2bi   swarm_monitoring_grafana            replicated   1/1        stefanprodan/swarmprom-grafana:5.3.4
ig4u1a4iepzg   swarm_monitoring_node-exporter      global       6/6        stefanprodan/swarmprom-node-exporter:v0.16.0
hzws91n8mu9r   swarm_monitoring_prometheus         replicated   1/1        stefanprodan/swarmprom-prometheus:v2.5.0
vlbgdqnywura   swarm_monitoring_unsee              replicated   1/1        cloudflare/unsee:v0.8.0
```

