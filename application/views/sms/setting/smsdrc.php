[gammu]
device = {value}:
connection = at115200

[smsd]
service = sql
PIN = 1234
logfile = smsdlog
logformat = textall
debuglevel = 1
phoneid = MyPhone1
commtimeout = 30
sendtimeout = 30

deliveryreport = sms
deliveryreportdelay = 100

# Database backends congfiguration
user = {username}
password = {password}
pc = {hostname}
database = {database}

# DBI configuration
driver = native_mysql
