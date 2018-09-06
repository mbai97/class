# zilla
#Install MySQL

#Install the MySQL server by using the Ubuntu package manager:
'''''''
  sudo apt-get update
  sudo apt-get install mysql-server
''''''
The installer installs MySQL and all dependencies.
Add a database
''''''
CREATE DATABASE 'zilla';
CREATE USER 'zilla'@'localhost' identified by 'Passwd123';
GRANT ALL PRIVILEGES ON zilla.* TO 'zilla'@'localhost';
''''''
clonning
''''''''
 git clone https://github.com/mbai97/zilla.git
 cd zilla
 http://your-ip-address/zilla
 '''''''
 follow the wizard
