Summary
=======

I created a backup of my entire Xonotic server config, including all pk3 files, [on Github](https://github.com/ballerburg9005/xonotic.us.to).

It includes complete instructions how you can set up this server by yourself. 
 
My server also displays those instructions on http://xonotic.us.to .
<p><br>

Instructions
============

Prepare the system. Install packages, setup xonotic user, use zsh:
```
apt install unzip zsh curl wget screen lighttpd vim php php-cgi php-mysql git rsync

# Oh my Zsh
sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# --- > put your id_rsa.pub into /root/.ssh/authorized_keys
# also add your root account's id_rsa.pub, if you want to run cronjob backups that preserve ownership

# keeps things all in one place 
rm -r /var/www/html
ln -s /home/xonotic/www /var/www/html
 
useradd -u 9005 -m xonotic -G root,audio -g www-data -s `which zsh`

su xonotic

unset ZSH
sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```
<br>

Download the latest Xonotic autobuild ZIP and unzip:
```
cd /home/xonotic
wget https://beta.xonotic.org/autobuild/Xonotic-`date +%Y%m%d  --date="3 days ago"`.zip
unzip Xonotic-`date +%Y%m%d  --date="3 days ago"`.zip
rm Xonotic-`date +%Y%m%d  --date="3 days ago"`.zip
```
<br>

Download the maps and server config from my github repository:
```
git clone --depth=1 https://github.com/ballerburg9005/xonotic.us.to
 
rsync -ra xonotic.us.to/ ./
chmod 700 /home/xonotic/.xonotic/data/data
rm -rf xonotic.us.to
```
<br>

**Edit /home/xonotic/.xonotic/data/server.cfg!**:
```
export MYSERVER="example.com" # (or ip address like 15.26.37.48) THIS NEEDS TO BE CORRECT
sed "s/xonotic\.us\.to/$MYSERVER/g" -i /home/xonotic/.xonotic/data/server.cfg

# you can control the server if connected inside Xonotic as a player with this password
echo '//rcon_password "SuperSecretPassword6666666"' >  /home/xonotic/.xonotic/data/secret.cfg # remove //
chmod 700 /home/xonotic/.xonotic/data/secret.cfg
```

Seriously ... you have to edit the server.cfg by hand:
```
vim /home/xonotic/.xonotic/data/server.cfg
```
<br>

And the final steps:
```
exit # to root shell

chown root:root /home/xonotic/xonotic.service
ln /home/xonotic/xonotic.service /etc/systemd/system/xonotic.service

lighty-enable-mod fastcgi fastcgi-php dir-listing rewrite

systemctl daemon-reload
systemctl enable xonotic lighttpd 
systemctl start lighttpd xonotic
```
<br><p>

Useful commands
===============
If you download new maps, then this gives you an updated list (paste it in server.cfg):
```
MAPUPDATE=;IFS=$'\n'; echo -n "\n\ng_maplist \""; for i in /home/xonotic/.xonotic/data/*.pk3; do if unzip -l "$i" | grep -q '\.bsp$'; then echo -n "$(unzip -l "$i" | grep '[^/]*\.bsp$' -osa | sed "s/\.bsp$//g" | tr '\n' ' ')"; fi; done; echo "\"\n\n"
```
<br>

Backup command for cronjob (on remote machine):
```
rsync --exclude 'Xonotic' -ra root@xonotic.us.to:/home/xonotic/ /mnt/1/BACKUP/XONOTIC_SERVER/
```
<br>

SSH directly into interactive Xonotic server console (from remote machine):
```
XONOTICSERVER=; ssh -t root@xonotic.us.to "su xonotic -c 'screen -r xonotic-server'"
```
<br><p> 

Troubleshooting
===============
Disable firewall or selinux:
```
iptables -I INPUT -j ACCEPT
echo 0 > /selinux/enforce
```
<br><p> 

Github
======
If you are curious: To mirror my server files on Github, I simply created a new account there, added an 
access token to it and then on my main account I granted that user access 
to an empty repo. Then I logged in as xonotic on my server and edited the
.git/config to include the user:accesstoken@ before the URL and changed it to my empty repo. I update the repo with a cronjob for the user xonotic: 

```
GIT=;cd /home/xonotic/; git config --global http.postBuffer 1048576000; git pull; git submodule update --remote --recursive; git add -u; git add  * .*; git commit -m "."; git push
```
<br>

Note that the command needs to be tested first. Uploading the home directory to Github ordinarily is to be considered insecure, for good reason that do not apply to my case.
<p><br>
