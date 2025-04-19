# How to set up a public platform  
Based on the public platform list provided, most of them focus on static website that cannot host PHP and mysql.   
As a result, a public platform Railway is selected for this project.  
It provides a one-time trial quota to host PHP and MySQL while supporting a link to visit your website.  
## Web server uses the source code in Github  
1. Upload all the source code to GitHub  
2. Register a railway account    
3. Create a project in railway  
4. In the project, click "Create" top right corner and choose "Github repo" to select your GitHub projects
5. Click the "deploy" button to setup the web server
6. Once the deployment is finished, right-click the built Github server

## Generate a link for public users to visit website
1. Go to setting, you can generate a domain under Networking.
2. This domain will be the website link for sharing to public users.
3. Click "edit" button next to your domain and then change the port number  

## How to check the using port number
1. Right-click the Github server and go to deployments.
2. Select the latest record to choose "view logs" on the right hand side.
3. There will be the port number using. For example:
    ```
    Starting Container  
    [server:info] Server starting on port 8080
    ```
4.  Refer to previous session of step 3 to change the port number to your port number (e.g.8080).

## How to connect mysql database to Github server
1. Click "Create" top right corner and choose "Database" and then "MySQL".
2. Click "Deploy" to build the database.
3. Go to Github website and edit the PHP file (e.g. db_connect.php) that connects database
4. Change the variable names used to connect database as below:
    ```
    $DB_HOST = $_ENV["DB_HOST"];
    $DB_USER = $_ENV["DB_USER"];
    $DB_PASSWORD = $_ENV["DB_PASSWORD"];
    $DB_NAME = $_ENV["DB_NAME"];
    $DB_PORT = $_ENV["DB_PORT"];
    ```
5. Go back to the Railway website, right-click the mysql and go to Variables.
6. The following vairables will be used as mentioned as step 4.
    ```
    MYSQLHOST
    MYSQLUSER
    MYSQLPASSWORD
    MYSQLDATABASE
    MYSQLUSER
    ````
7. On the other hand, Right-click the Github server and also go to Variables.
8. Add each of the new variable listed in step 6.
9. The variable name is defined in step 4.
    For example:
    ```
    # in the PHP file
    $DB_HOST = $_ENV["MY_HOST"]
    ```
    ```
    # in mysql to add variables
    variable name: MY_HOST
    value: <copy_from_mysql_variable_MYSQLHOST>
    ```
10. Once Github server and mysql automatically redeploy, you can go to your website to test the database

## How to import complex database data using local mysql.exe
1. Download wampserver and required version of C++ during installation in your device
2. Open cmd and select the folder of mysql.exe  
   (e.g. c:\wamp64\bin\mysql\mysql9.x.x\bin)  
3. Type in the commend provided by railway mysql
4. For more mysql variables, expend the Railway Provided variables available for more values
    ```
    mysql -h <RAILWAY_TOP_PROXY_DOMAIN> -P <RAILWAY_TCP_PROXY_PORT> -u <MYSQLUSER> -p<MYSQLPASSWORD> railway
    ```
5. The database is connected as the message below:
    ```
    mysql: [Warning] Using a password on the command line interface can be insecure. 
    Welcome to the MySQL monitor.  Commands end with ; or \g. 
    Your MySQL connection id is 9 
    Server version: 9.2.0 MySQL Community Server - GPL   

    Copyright (c) 2000, 2024, Oracle and/or its affiliates. 

    Oracle is a registered trademark of Oracle Corporation and/or its 
    affiliates. Other names may be trademarks of their respective 
    owners. 

    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement. 

    mysql> 
    ```
6. Copy the SQL souce code from database file (e.g. blogging_platform.sql) and paste into cmd.
7. Press enter to finsih the process.
8. Go back to railway mysql website, there will be your tables and data under Data.

## How to run
Since the github server and mysql database have been deployed, they will online 24/7 automatically.  
However, they will keep using the quota even if nobody uses.  
Serverless functionality is used to allow server and database offline when no requests.  
The disadvantage is database needs a few seconds to make the database online.  
It is recommended to customize the website in the PHP file (e.g. db_connect.php) to remind public users.

1. Right-click mysql and Github server repectively.
2. Go to settings and enable serverless under Serverless.
3. They will be slept when nobody is staying on the webpage after seveal minutes.

# How to test in this webpage
Admin
```
username: admin
password: admin
```
User
```
username: 123
password: 123
```
