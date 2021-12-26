# Домашнее задание к занятию "4.2. Использование Python для решения типовых DevOps задач"

## Обязательная задача 1

Есть скрипт:
```python
#!/usr/bin/env python3
a = 1
b = '2'
c = a + b
```

### Вопросы:
| Вопрос  | Ответ |
| ------------- | ------------- |
| Какое значение будет присвоено переменной `c`?  | никакое, TypeError: unsupported operand type(s) for +: 'int' and 'str'  |
| Как получить для переменной `c` значение 12?  | c = str(a) + b  |
| Как получить для переменной `c` значение 3?  | c = a + int (b)  |

## Обязательная задача 2
Мы устроились на работу в компанию, где раньше уже был DevOps Engineer. Он написал скрипт, позволяющий узнать, какие файлы модифицированы в репозитории, относительно локальных изменений. Этим скриптом недовольно начальство, потому что в его выводе есть не все изменённые файлы, а также непонятен полный путь к директории, где они находятся. Как можно доработать скрипт ниже, чтобы он исполнял требования вашего руководителя?

```python
#!/usr/bin/env python3

import os

bash_command = ["cd ~/netology/sysadm-homeworks", "git status"]
result_os = os.popen(' && '.join(bash_command)).read()
is_change = False
for result in result_os.split('\n'):
    if result.find('modified') != -1:
        prepare_result = result.replace('\tmodified:   ', '')
        print(prepare_result)
        break
```
1) break выходит из тела цикла сразу же при первом прогоне, так что он тут лишний
2) это конечно не мешает, но есть булевая переменная, которая ничего не делает is_change, по идее её можно просто убрать

### Ваш скрипт:
```python
#!/usr/bin/env python3
import os
bash_command = ["cd ~/devops-netology", "git status"]
result_os = os.popen(' && '.join(bash_command)).read()
for result in result_os.split('\n'):
    if result.find('modified') != -1:
        prepare_result = result.replace('\tmodified:   ', '')
        print(prepare_result)
```

### Вывод скрипта при запуске при тестировании:
```
vagrant@vim:~/devops-netology$ git status
On branch main
Your branch is ahead of 'origin/main' by 1 commit.
  (use "git push" to publish your local commits)

Changes not staged for commit:
  (use "git add <file>..." to update what will be committed)
  (use "git restore <file>..." to discard changes in working directory)
        modified:   stest1
        modified:   stest2
        modified:   test1

no changes added to commit (use "git add" and/or "git commit -a")
vagrant@vim:~$ python3 pythscript
stest1
stest2
test1
```

## Обязательная задача 3
1. Доработать скрипт выше так, чтобы он мог проверять не только локальный репозиторий в текущей директории, а также умел воспринимать путь к репозиторию, который мы передаём как входной параметр. Мы точно знаем, что начальство коварное и будет проверять работу этого скрипта в директориях, которые не являются локальными репозиториями.

### Ваш скрипт:
```python
#!/usr/bin/env python3
import os
import sys
p = sys.argv[1]
bash_command = ["cd "+p, "git status"]
result_os = os.popen(' && '.join(bash_command)).read()
for result in result_os.split('\n'):
    if result.find('modified') != -1:
        prepare_result = result.replace('\tmodified:   ', '')
        print(p+prepare_result)    
```
добавил переменную p для параметра пути
### Вывод скрипта при запуске при тестировании:
```
vagrant@vim:~$ python3 pythscript /home/vagrant/devops-netology/
/home/vagrant/devops-netology/stest1
/home/vagrant/devops-netology/stest2
/home/vagrant/devops-netology/test1
```

## Обязательная задача 4
1. Наша команда разрабатывает несколько веб-сервисов, доступных по http. Мы точно знаем, что на их стенде нет никакой балансировки, кластеризации, за DNS прячется конкретный IP сервера, где установлен сервис. Проблема в том, что отдел, занимающийся нашей инфраструктурой очень часто меняет нам сервера, поэтому IP меняются примерно раз в неделю, при этом сервисы сохраняют за собой DNS имена. Это бы совсем никого не беспокоило, если бы несколько раз сервера не уезжали в такой сегмент сети нашей компании, который недоступен для разработчиков. Мы хотим написать скрипт, который опрашивает веб-сервисы, получает их IP, выводит информацию в стандартный вывод в виде: <URL сервиса> - <его IP>. Также, должна быть реализована возможность проверки текущего IP сервиса c его IP из предыдущей проверки. Если проверка будет провалена - оповестить об этом в стандартный вывод сообщением: [ERROR] <URL сервиса> IP mismatch: <старый IP> <Новый IP>. Будем считать, что наша разработка реализовала сервисы: `drive.google.com`, `mail.google.com`, `google.com`.

### Ваш скрипт:
```python
#!/usr/bin/env python3

import socket
import time
import datetime

web = {'drive.google.com':'0.0.0.0', 'mail.google.com':'0.0.0.0', 'google.com':'0.0.0.0'}

while 1==1 :
  time.sleep(5)
  for host in web:
    ip = socket.gethostbyname(host)
    if ip != web[host]:
      print(str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))+' [ERROR] '+str(host)+' '+web[host]+' IP изменён на '+ip)
      web[host]=ip
    else:
      print(str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))+'         '+str(host)+' '+ip)
```


### Вывод скрипта при запуске при тестировании:
```
2021-12-26 08:33:02 [ERROR] drive.google.com 0.0.0.0 IP изменён на 64.233.162.194
2021-12-26 08:33:02 [ERROR] mail.google.com 0.0.0.0 IP изменён на 142.251.1.17
2021-12-26 08:33:02 [ERROR] google.com 0.0.0.0 IP изменён на 209.85.233.139
2021-12-26 08:33:07         drive.google.com 64.233.162.194
2021-12-26 08:33:07         mail.google.com 142.251.1.17
2021-12-26 08:33:07 [ERROR] google.com 209.85.233.139 IP изменён на 209.85.233.138
2021-12-26 08:33:12         drive.google.com 64.233.162.194
2021-12-26 08:33:12         mail.google.com 142.251.1.17
2021-12-26 08:33:12         google.com 209.85.233.138
2021-12-26 08:33:17         drive.google.com 64.233.162.194
2021-12-26 08:33:17         mail.google.com 142.251.1.17
2021-12-26 08:33:17         google.com 209.85.233.138
2021-12-26 08:33:22         drive.google.com 64.233.162.194
2021-12-26 08:33:22         mail.google.com 142.251.1.17
2021-12-26 08:33:22         google.com 209.85.233.138
```

