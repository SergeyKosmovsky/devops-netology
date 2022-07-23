# Задание
# Преподаватель: Булат Замилов, Олег Букатчук, Руслан Жданов  
# Дипломный практикум в YandexCloud  

Цели:
1) Зарегистрировать доменное имя (любое на ваш выбор в любой доменной зоне).  
2) Подготовить инфраструктуру с помощью Terraform на базе облачного провайдера YandexCloud.  
3) Настроить внешний Reverse Proxy на основе Nginx и LetsEncrypt.
4) Настроить кластер MySQL.
5) Установить WordPress.
6) Развернуть Gitlab CE и Gitlab Runner.
7) Настроить CI/CD для автоматического развёртывания приложения.
8) Настроить мониторинг инфраструктуры с помощью стека: Prometheus, Alert Manager и Grafana.

## Предисловие
Для удобства проверки поместил все переменные в два файлика: `variables.tf` и `inventory` для terraform и ansible соответтвенно.  
Их стоит заполнить перед разворотом инфраструктуры. Так же сначала сгенерируем новый ssh ключ обновим открытую часть в файлике `meta.txt`, так же в нём замените имя пользователя, если меняли в файлах с переменными.


## Этапы выполнения:

### 1. Регистрация доменного имени

Мой зарегестрированный домен `kosmovskiy.ru`. Панель управления ниже
![1-DNS](https://user-images.githubusercontent.com/93204208/180609860-97817fcb-b733-4c68-b5ce-97a7c0e22744.PNG)  


### 2. Создание инфраструктуры

Не стал усложнять себе жизнь и пользовался stage пространством.  
![2-s3](https://user-images.githubusercontent.com/93204208/180609896-211c77db-4589-4d72-a97d-89ab9860c191.PNG)  

Запустим на этом этапе отработку terraform для создание наших ВМ и всей структуры в целом  
![image](https://user-images.githubusercontent.com/93204208/180610439-92cc9c4f-f7ea-4851-bd08-8736dfdbffb4.png)  
Результат  
![image](https://user-images.githubusercontent.com/93204208/180613375-d1e53bbf-7b9a-4ee2-a1c2-e29dc58af2bf.png)  


### 3. Установка Nginx и LetsEncrypt

Теперь переходим к ansible. В работе используются тестовые сертификаты. При необходимости работы с боевыми удалить параметры  `--test-cert` в файле по пути `..\ansible\roles\install_nginx\tasks\main.yml`    
Запускаем `ansible-playbook -i inventory nginx.yml`   
  
Проверяем все сайты:  
![3-kosmvoskiy](https://user-images.githubusercontent.com/93204208/180609909-c0114f67-5a9b-4a3a-9ae2-be792e38da76.PNG)  

![3-gitlab](https://user-images.githubusercontent.com/93204208/180609915-8410c0f9-3525-4164-8c42-648775a70dfe.PNG)  

![3-prometheus](https://user-images.githubusercontent.com/93204208/180609918-35aa7de8-66ad-451c-a68f-f10d78020c66.PNG)  

![3-grafana](https://user-images.githubusercontent.com/93204208/180609940-baebb99d-c8ff-4c95-ac9a-03ba8ebee833.PNG)  

![3-alertmanagers](https://user-images.githubusercontent.com/93204208/180609948-4e2f40da-c954-4de6-bd44-82c3565994db.PNG)  


### 4. Установка кластера MySQL

Запускаем `ansible-playbook -i inventory MySQL.yml`  

Проверяем результаты, выполняя запрос на каждом сервере:  
- на master  
```
mysql> show master status\G
*************************** 1. row ***************************
             File: mysql-bin.000002
         Position: 157
     Binlog_Do_DB: wordpress
 Binlog_Ignore_DB:
Executed_Gtid_Set:
```
- на slave
```
mysql> show slave status\G
*************************** 1. row ***************************
               Slave_IO_State: Waiting for source to send event
                  Master_Host: db01.kosmovskiy.ru
                  Master_User: repuser
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000002
          Read_Master_Log_Pos: 157
               Relay_Log_File: relay-bin.000005
                Relay_Log_Pos: 373
        Relay_Master_Log_File: mysql-bin.000002
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
              Replicate_Do_DB:
          Replicate_Ignore_DB:
           Replicate_Do_Table:
       Replicate_Ignore_Table:
      Replicate_Wild_Do_Table:
  Replicate_Wild_Ignore_Table:
                   Last_Errno: 0
                   Last_Error:
                 Skip_Counter: 0
          Exec_Master_Log_Pos: 157
              Relay_Log_Space: 746
              Until_Condition: None
               Until_Log_File:
                Until_Log_Pos: 0
           Master_SSL_Allowed: No
           Master_SSL_CA_File:
           Master_SSL_CA_Path:
              Master_SSL_Cert:
            Master_SSL_Cipher:
               Master_SSL_Key:
        Seconds_Behind_Master: 0
Master_SSL_Verify_Server_Cert: No
                Last_IO_Errno: 0
                Last_IO_Error:
               Last_SQL_Errno: 0
               Last_SQL_Error:
  Replicate_Ignore_Server_Ids:
             Master_Server_Id: 1
                  Master_UUID: bf8e50d1-0505-11ed-b43e-d00d1d8bc5b4
             Master_Info_File: mysql.slave_master_info
                    SQL_Delay: 0
          SQL_Remaining_Delay: NULL
      Slave_SQL_Running_State: Replica has read all relay log; waiting for more updates
           Master_Retry_Count: 86400
                  Master_Bind:
      Last_IO_Error_Timestamp:
     Last_SQL_Error_Timestamp:
               Master_SSL_Crl:
           Master_SSL_Crlpath:
           Retrieved_Gtid_Set:
            Executed_Gtid_Set:
                Auto_Position: 0
         Replicate_Rewrite_DB:
                 Channel_Name:
           Master_TLS_Version:
       Master_public_key_path:
        Get_master_public_key: 0
            Network_Namespace:
1 row in set, 1 warning (0.01 sec)
```

### 5. Установка WordPress

Запускаем `ansible-playbook -i inventory Wordpress.yml`  

Переходим по ссылке https://kosmovskiy.ru/  
![5-login](https://user-images.githubusercontent.com/93204208/180612160-bd6ad88b-e8ab-4172-95b0-7d8a57b573b4.PNG)  

Пользователь wordpress, пароль wordpress. Подверждаем и из строки удаляем лишнее до https://kosmovskiy.ru/ и попадаем на   
![5-WordPress](https://user-images.githubusercontent.com/93204208/180612284-7930b7ee-f2b3-45ed-a020-d49b6d487479.PNG)  


### 6. Установка Gitlab CE и Gitlab Runner

Здесь уже будет больше ручных действий.  
Запускаем `ansible]$ ansible-playbook -i inventory Gitlab.yml`  
Имеется примечание от разработчика: таска **TASK [install_GitLab : Reconfigure GitLab (first run).]** может зависать, так что следует перезапустить плэйбук, пока не проскочит.  
  
Заходим на сервер гитлаб по ssh: `ssh -o "ProxyCommand ssh serkos@kosmovskiy.ru nc %h %p" serkos@gitlab.kosmovskiy.ru`  
И меняем пароль пользователя root с помощью:  `gitlab-rake "gitlab:password:reset[root]"`  

```
[serkos@localhost ansible]$ ssh -o "ProxyCommand ssh serkos@kosmovskiy.ru nc %h %p" serkos@gitlab.kosmovskiy.ru
Welcome to Ubuntu 20.04.3 LTS (GNU/Linux 5.4.0-42-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage
Failed to connect to https://changelogs.ubuntu.com/meta-release-lts. Check your Internet connection or proxy settings

Last login: Sat Jul 23 13:38:49 2022 from 192.168.10.36
serkos@gitlab:~$ gitlab-rake "gitlab:password:reset[root]"
chpst: fatal: unable to switch to directory: /opt/gitlab/etc/gitlab-rails/env: access denied
serkos@gitlab:~$ sudo gitlab-rake "gitlab:password:reset[root]"
Enter password:
Confirm password:
Password successfully updated for user with username root.
```

Далее логинимся на https://gitlab.kosmovskiy.ru/ (логин root, пароль вы меняли выше) и копируем Registration token
![6-token](https://user-images.githubusercontent.com/93204208/180612613-265333d9-e3d0-4dd3-8560-ec85b8fbe202.PNG)  

Этот Registration token используем в файлике `inventory` переменна *token*  
Запускаем `ansible]$ ansible-playbook -i inventory Gitlab.yml`  

Проверяем доступность раннера  
![6-online-runner](https://user-images.githubusercontent.com/93204208/180612930-18f3051a-8513-4f8f-a4c3-41af3a2f4552.PNG)  

Создаём проект  
![6-Wordpress project](https://user-images.githubusercontent.com/93204208/180612952-486e8d66-9236-4ec5-9919-107580b0d1ee.PNG)  

Подсовываем закрытую часть ключа, которую генерировали в начале. Остальные параметры копируем со скриншота. Это пригодится для взаимодействия с сервером **app**  
![6-ssh_key](https://user-images.githubusercontent.com/93204208/180613011-33fee4b4-eb60-4aec-bb5f-539560795793.PNG)  

Следуем в работу с CI/CD  
![image](https://user-images.githubusercontent.com/93204208/180614341-893e0037-4f06-4864-8552-609e98d6eff7.png)  

Используем следующий скрипт для деплоя файлов на сервер **app** при использовании тэгов  

```
before_script:
  - eval $(ssh-agent -s)
  - echo "$ssh_key" | tr -d '\r' | ssh-add -

stages:         
  - deploy

deploy-job:      
  stage: deploy
  script:
    - echo "Обнаружены измененияв репорзитории..."
    - if [ "$CI_COMMIT_TAG" = "" ] ; then echo "Вносить изменения только с тэгами, отказ";
    - else echo "Обнаружен тэг, синхронизируем...";
      ssh -o StrictHostKeyChecking=no serkos@app.kosmovskiy.ru sudo chmod -R 777 /var/www/wordpress/;
      rsync -vz -e "ssh -o StrictHostKeyChecking=no" ./* serkos@app.kosmovskiy.ru:/var/www/wordpress/;
      fi
    - echo "Завершение"
```
![image](https://user-images.githubusercontent.com/93204208/180614373-9bf03dc2-b9b0-4a3e-8ff8-5c6a5b4d745e.png)   

И проверяем работу скрипта, создадим несколько файлов и присвоим тэг:  
![image](https://user-images.githubusercontent.com/93204208/180614554-dde899a5-3569-4bdc-bfe9-dfecfabecb1a.png)  

Удостоверимся, что файлы дошли на самом сервере:  
![image](https://user-images.githubusercontent.com/93204208/180614633-ff95293d-d2c8-4936-a96f-09f95ce8b287.png)  

### 7. Установка Prometheus, Alert Manager, Node Exporter и Grafana

Запускаем `ansible]$ ansible-playbook -i inventory monitoring.yml`  
Следуем по пути https://prometheus.kosmovskiy.ru/ 
![7-prometheus](https://user-images.githubusercontent.com/93204208/180615134-67a44a55-a3dd-4775-9be6-e9f4d6f22640.PNG)  

Теперь следуем https://grafana.kosmovskiy.ru/ Логин: admin пароль: admin  
Здесь необходимо указать источник данных и дашборд
![image](https://user-images.githubusercontent.com/93204208/180615272-ce54774a-9091-45ad-bb7c-aeb7e26e73b9.png)  

Жмём по *DATA SOURCES* и указываем http://localhost:9090    
![7-grafana-http](https://user-images.githubusercontent.com/93204208/180615337-8b27bd9a-dd51-49fd-9722-26c53078fb75.PNG)  

Чуть выше выбираем пункт *dashboards* и импортируем *Prometheus 2.0*. Возвращаемся в пункт *настройки* и сохраняем изменения  
![7-add data and dashboards](https://user-images.githubusercontent.com/93204208/180615407-b53e3b8f-ea63-48f3-a182-c0bd115301db.PNG)  

Слева переходм во вкладку *Dashboards* и кликаем по единственным данным  
![7-grafana-click-dashboards](https://user-images.githubusercontent.com/93204208/180615517-309fcc31-b0ac-4ce5-afec-02237e03c368.PNG)  

Лицезреем красоту:  
![7-grafana](https://user-images.githubusercontent.com/93204208/180615556-2a16aab2-870f-4133-bbe4-5843e810642f.PNG)

При желании можем добавлять панельки на любой вкус и цвет:
![7-add-panel](https://user-images.githubusercontent.com/93204208/180615565-3ee2c783-409c-4ae8-b875-242a4110440b.PNG)

И последнее https://alertmanager.kosmovskiy.ru/


