# Задача 1
https://hub.docker.com/repository/docker/sergeykosmovsky/ngix1.1


# Задача 2
## Посмотрите на сценарий ниже и ответьте на вопрос: "Подходит ли в этом сценарии использование Docker контейнеров или лучше подойдет виртуальная машина, физическая машина? Может быть возможны разные варианты?"

## Детально опишите и обоснуйте свой выбор.

--

Сценарий:

 -Высоконагруженное монолитное java веб-приложение;

Физический сервер, прямой доступ к ресурсам не будет усложнять архитектуру, и будет давать максимальную производительность.
 
 -Nodejs веб-приложение;

Веб приложение, которое как и остальные легко обходятся докером.
 
 -Мобильное приложение c версиями для Android и iOS;

Из-за отсутвия графики виртуалка, ибо на Андроид и Айос с приложением по другому не повзаимодействовать.
 
 -Шина данных на базе Apache Kafka;

Для простой передачи данных можно и Докер использовать. Если она копит данные и отправляет, то надёжнее виртуалку, а то данные схлопнутся вместе с контйнером
 
 -Elasticsearch кластер для реализации логирования продуктивного веб-приложения - три ноды elasticsearch, два logstash и две ноды kibana;

Звучит массивно, БД как никак, думаю, вполне хватит виртуализации, вычислять тут особо нечего, сугубо работа с БД.
 
 -Мониторинг-стек на базе Prometheus и Grafana;

Как сами приложения могут и в докере работать, Логировать мониторинг можно в другое место.
 
 -MongoDB, как основное хранилище данных для java-приложения;

Опять виртуалка, докер и хранение данных вещь спорная. 

 -Gitlab сервер для реализации CI/CD процессов и приватный (закрытый) Docker Registry.

Виртуалка. Сплошное хранение информации и масштабирование, Или хранилка :)


# Задача 3
скрин вложил к решению


