# XSS EVASION LAB

This lab is vulnerable to various XSS, business logic and client side validation. Do not install this into a production environment. This is for test only.

# INSTALLATION WITH DOCKER

```cd /root
git clone https://github.com/thecasual/xss_evasion_lab.git
cd /root/xss_evasion_lab
git pull origin master
docker rm apache -f
sudo docker run -d --name=apache -p 80:80 -v apache:/etc/ -v /root/xss_evasion_lab:/var/www/html php:apache
sudo iptables -I DOCKER-USER ! -i docker0 -o docker0 -p tcp --dport 80 -j ACCEPT
chown -R www-data:www-data /root/xss_evasion_lab/
docker exec -it apache chown -R www-data:www-data /var/www/html
```

# NOTES

If you are having answers getting a particular challenge completed, let me know and I can provide the answers. They are purposely removed from the source code.

This is primarly an XSS evasion lab. There are other exploitable items that I will not mention that are more in line with client side validation and business logic.
