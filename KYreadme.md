Результатом курсовой работы должны быть снимки экрана или текст:


• Процесс установки и настройки ufw

vagrant@vim:~$ sudo apt install ufw
Reading package lists... Done
Building dependency tree
Reading state information... Done
ufw is already the newest version (0.36-6).
0 upgraded, 0 newly installed, 0 to remove and 0 not upgraded.
vagrant@vim:~$ sudo ufw status verbose
Status: inactive
vagrant@vim:~$ sudo ufw allow 443
Rule added
Rule added (v6)
vagrant@vim:~$ sudo ufw allow ssh
Rules updated
Rules updated (v6)
vagrant@vim:~$ sudo ufw allow in on lo to any
Rule added
Rule added (v6)
vagrant@vim:~$ sudo ufw enable
Command may disrupt existing ssh connections. Proceed with operation (y|n)? y
Firewall is active and enabled on system startup
vagrant@vim:~$ sudo ufw status verbose
Status: active
Logging: on (low)
Default: deny (incoming), allow (outgoing), disabled (routed)
New profiles: skip
To                         Action      From
--                         ------      ----
22/tcp                     ALLOW IN    Anywhere
443                        ALLOW IN    Anywhere
Anywhere on lo             ALLOW IN    Anywhere
22/tcp (v6)                ALLOW IN    Anywhere (v6)
443 (v6)                   ALLOW IN    Anywhere (v6)
Anywhere (v6) on lo        ALLOW IN    Anywhere (v6)

 
• Процесс установки и выпуска сертификата с помощью hashicorp vault

# Корневой сертификат
root@vim:~# vault secrets enable pki
Success! Enabled the pki secrets engine at: pki/
root@vim:~# vault secrets tune -max-lease-ttl=87600h pki
Success! Tuned the secrets engine at: pki/
root@vim:~# vault write -field=certificate pki/root/generate/internal \
> common_name="example.com" \
> ttl=87600h > CA_cert.crt

# Промежуточный сертификат
root@vim:~# vault secrets enable -path=pki_int pki
Success! Enabled the pki secrets engine at: pki_int/
root@vim:~# vault secrets tune -max-lease-ttl=43800h pki_int
Success! Tuned the secrets engine at: pki_int/
root@vim:~# vault write -format=json pki_int/intermediate/generate/internal \
> common_name="example.com Intermediate Authority" \
> | jq -r '.data.csr' > pki_intermediate.csr
root@vim:~# vault write -format=json pki/root/sign-intermediate csr=@pki_intermediate.csr \
> format=pem_bundle ttl="43800h" \
> | jq -r '.data.certificate' > intermediate.cert.pem

# Конечный сертификат с ключом 
root@vim:~# vault write pki_int/intermediate/set-signed certificate=@intermediate.cert.pem
Success! Data written to: pki_int/intermediate/set-signedrm
root@vim:~# vault write pki_int/roles/example-dot-com \
> allowed_domains="example.com" \
> allow_subdomains=true \
> max_ttl="720h"
Success! Data written to: pki_int/roles/example-dot-com
root@vim:~# vault write -format=json pki_int/issue/example-dot-com \
> common_name="test.example.com" \
> ttl="720h" > test.example.com.crt
root@vim:~# cat test.example.com.crt | jq -r .data.certificate > test.example.com.pem
root@vim:~# cat test.example.com.crt | jq -r .data.issuing_ca >> test.example.com.pem
root@vim:~# cat test.example.com.crt | jq -r .data.private_key > test.example.com.key
root@vim:~# ls -l
total 64
-rw-r--r-- 1 root root 1171 Jan 23 12:35 CA_cert.crt
-rw-r--r-- 1 root root  285 Jan 20 18:43 config.hcl
-rw-r--r-- 1 root root   36 Jan 20 19:28 encoded_root.txt
-rw-r--r-- 1 root root  166 Jan 20 19:27 init_output.txt
-rw-r--r-- 1 root root 1172 Jan 23 12:37 intermediate.cert.pem
drwxr-xr-x 2 root root 4096 Jan 20 18:48 log
-rw-r--r-- 1 root root  924 Jan 23 12:36 pki_intermediate.csr
-rw-r--r-- 1 root root   26 Jan 20 19:31 root_token.txt
-rw-r--r-- 1 root root 5749 Jan 23 12:38 test.example.com.crt
-rw-r--r-- 1 root root 1679 Jan 23 12:39 test.example.com.key
-rw-r--r-- 1 root root 3585 Jan 23 13:25 test.example.com.pem
drwxr-xr-x 3 root root 4096 Jan 20 18:43 vault
-rw-r--r-- 1 root root  903 Jan 20 18:52 vault_init.txt

 
• Процесс установки и настройки сервера nginx

# Скачиваем, у меня уже скачан
vagrant@vim:~$ sudo apt install nginx
Reading package lists... Done
Building dependency tree
Reading state information... Done
nginx is already the newest version (1.18.0-0ubuntu1.2).
0 upgraded, 0 newly installed, 0 to remove and 109 not upgraded.

# Создаю отдельную папку и кладу туда pem и key
root@vim:~# mkdir /etc/ssl/vault_certificates_for_nginx
root@vim:~# cp test.example.com.pem /etc/ssl/vault_certificates_for_nginx/
root@vim:~# cp test.example.com.key /etc/ssl/vault_certificates_for_nginx/

# Вношу данные в файл настройки ngix и рестартую приложение
vagrant@vim:~$ sudo vim /etc/nginx/nginx.conf  
…
server {
    listen              443 ssl;
    server_name         test.example.com;
    ssl_certificate     /vault_certificates_for_nginx/test.example.com.pem;
    ssl_certificate_key /vault_certificates_for_nginx/test.example.com.key;
    ssl_protocols       TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers         HIGH:!aNULL:!MD5;
}
…
vagrant@vim:~$ sudo /etc/init.d/nginx restart
Restarting nginx (via systemctl): nginx.service.

• Страница сервера nginx в браузере хоста не содержит предупреждений
вложил файл рядом с ссылкой

• Скрипт генерации нового сертификата работает (сертификат сервера ngnix должен быть "зеленым")

root@vim:~# cat cert-script.sh
#!/bin/bash
PATH=/etc:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin

vault write -format=json pki_int/issue/example-dot-com common_name="test.example.com" ttl="720h" > test.example.com.crt

cat test.example.com.crt | jq -r .data.certificate > test.example.com.pem
cat test.example.com.crt | jq -r .data.issuing_ca >> test.example.com.pem
cat test.example.com.crt | jq -r .data.private_key > test.example.com.key
cp test.example.com.pem /etc/ssl/vault_certificates_for_nginx/
cp test.example.com.key /etc/ssl/vault_certificates_for_nginx/
/etc/init.d/nginx restart
root@vim:~# ./cert-script.sh
Restarting nginx (via systemctl): nginx.service.
root@vim:~# ls -l
total 68
...
-rw-r--r-- 1 root root 5745 Jan 23 14:39 test.example.com.crt
-rw-r--r-- 1 root root 1675 Jan 23 14:39 test.example.com.key
-rw-r--r-- 1 root root 2413 Jan 23 14:39 test.example.com.pem
...
root@vim:~# ls -l /etc/ssl/vault_certificates_for_nginx/
total 8
-rw-r--r-- 1 root root 1675 Jan 23 14:39 test.example.com.key
-rw-r--r-- 1 root root 2413 Jan 23 14:39 test.example.com.pem

По времени видно, что изменилдось всё в течении минуты.

• Crontab работает (выберите число и время так, чтобы показать что crontab запускается и делает что надо)

root@vim:~# crontab -l
5 15 * * * /root/cert-script.sh
root@vim:~# date
Sun 23 Jan 2022 03:04:33 PM UTC
root@vim:~# ls -l
total 60
...
-rw-r--r-- 1 root root 5745 Jan 23 14:39 test.example.com.crt
-rw-r--r-- 1 root root 1675 Jan 23 14:39 test.example.com.key
-rw-r--r-- 1 root root 2413 Jan 23 14:39 test.example.com.pem
...
root@vim:~# date
Sun 23 Jan 2022 03:05:11 PM UTC
root@vim:~# ls -l
total 44
...
-rw-r--r-- 1 root root    0 Jan 23 15:05 test.example.com.crt
-rw-r--r-- 1 root root    0 Jan 23 15:05 test.example.com.key
-rw-r--r-- 1 root root    0 Jan 23 15:05 test.example.com.pem
...
По времени можно отследить

