CREATE USER 'health'@'%' IDENTIFIED BY 'healthpass';
GRANT USAGE ON *.* TO 'health'@'%';
FLUSH PRIVILEGES;
