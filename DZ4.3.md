## Обязательная задача 1
Мы выгрузили JSON, который получили через API запрос к нашему сервису:
```
    { "info" : "Sample JSON output from our service\t",
        "elements" :[
            { "name" : "first",
            "type" : "server",
            "ip" : 7175 
            }
            { "name" : "second",
            "type" : "proxy",
            "ip" : "71.78.22.43"
            }
        ]
    }
```
  Нужно найти и исправить все ошибки, которые допускает наш сервис

пропущены ковычки в последнем IP

## Обязательная задача 2
В прошлый рабочий день мы создавали скрипт, позволяющий опрашивать веб-сервисы и получать их IP. К уже реализованному функционалу нам нужно добавить возможность записи JSON и YAML файлов, описывающих наши сервисы. Формат записи JSON по одному сервису: `{ "имя сервиса" : "его IP"}`. Формат записи YAML по одному сервису: `- имя сервиса: его IP`. Если в момент исполнения скрипта меняется IP у сервиса - он должен так же поменяться в yml и json файле.

### Ваш скрипт:
```python
#!/usr/bin/env python3

import socket
import time
import datetime
import json
import yaml


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
  with open("/home/vagrant/hostip.json", 'w') as js1:
    webj = json.loads(json.dumps(web))
    js1.write(json.dumps(webj, indent=2))
  with open("/home/vagrant/hostip.yaml", 'w') as yam1:
    weby = yaml.load(yaml.dump(web))
    yam1.write(yaml.dump(webj, indent=2))
```

### Вывод скрипта при запуске при тестировании:
```
vagrant@vim:~$ python3 dos
2021-12-29 15:51:12 [ERROR] drive.google.com 0.0.0.0 IP изменён на 64.233.162.194
2021-12-29 15:51:12 [ERROR] mail.google.com 0.0.0.0 IP изменён на 142.251.1.17
2021-12-29 15:51:12 [ERROR] google.com 0.0.0.0 IP изменён на 209.85.233.100
dos:25: YAMLLoadWarning: calling yaml.load() without Loader=... is deprecated, as the default Loader is unsafe. Please read https://msg.pyyaml.org/load for full details.
  weby = yaml.load(yaml.dump(web))
2021-12-29 15:51:17         drive.google.com 64.233.162.194
2021-12-29 15:51:17         mail.google.com 142.251.1.17
2021-12-29 15:51:17 [ERROR] google.com 209.85.233.100 IP изменён на 209.85.233.113
2021-12-29 15:51:22         drive.google.com 64.233.162.194
2021-12-29 15:51:22         mail.google.com 142.251.1.17
2021-12-29 15:51:22         google.com 209.85.233.113
```

### json-файл(ы), который(е) записал ваш скрипт:
```json
vagrant@vim:~$ cat hostip.json
{
  "drive.google.com": "64.233.162.194",
  "mail.google.com": "142.251.1.17",
  "google.com": "209.85.233.113"
}
```

### yml-файл(ы), который(е) записал ваш скрипт:
```yaml
vagrant@vim:~$ cat hostip.yaml
drive.google.com: 64.233.162.194
google.com: 209.85.233.113
mail.google.com: 142.251.1.17
```
