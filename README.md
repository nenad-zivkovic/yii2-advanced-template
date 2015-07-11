yii2-advanced-template
======================

Yii2-advanced-template is based on yii2-app-advanced created by yii2 core developers.
There are several upgrades made to this template.

1. This template has additional features listed in the next section of this guide.
2. Application structure has been changed to be 'shared hosting friendly'.

Features
-------------------

- Signup with/without account activation
    - You can chose whether or not new users need to activate their account using email account activation system before they can log in. (see: common/config/params.php).
- Login using email/password or username/password combo.
    - You can chose how users will login into system. They can log in either by using their username|password combo or email|password. (see: common/config/params.php).
- Rbac tables are installed with other migrations when you run ```yii migrate``` command.
    - RbacController's init() action will insert 5 roles and 2 permissions in our rbac tables created by migration.
    - Roles can be easily assigned to users by administrators of the site (see: backend/user).
    - Nice example of how to use rbac in your code is given in this application. See: BackendController.
- Users with editor+ roles can create articles.
- Session data is stored in database out of box.
- System setting are stored in config/params.php file ( changes from v2 ).
- Theming is supported out of the box.
- Translation is supported out of the box.
- Administrators and The Creator can manage users ( changes from v2 ).
- Password strength validation and strength meter.
- All functionalities of default advanced template are included in this template.
- Code is heavily commented out.

Installation
-------------------
>I am assuming that you know how to: install and use Composer, and install additional packages/drivers that may be needed for you to run everything on your system. In case you are new to all of this, you can check my guides for installing default yii2 application templates, provided by yii2 developers, on Windows 8 and Ubuntu based Linux operating systems, posted on www.freetuts.org.

1. Create database that you are going to use for your application (you can use phpMyAdmin or any
other tool you like).

2. Now open up your console and ```cd``` to your web root directory, 
for example: ``` cd /var/www/sites/ ```

3. Run the Composer ```create-project``` command:

   ``` composer create-project nenad/yii2-advanced-template advanced ```

4. Once template is downloaded, you need to initialize it in one of two environments:
development (dev) or production (prod). Change your working directory to ```_protected``` 
and execute ```php init``` command.

   ```cd advanced/_protected/```

   ```php init ```

   Type __0__ for development, execute coomant, type __yes__ to confirm, and execute again.

5. Now you need to tell your application to use database that you have previously created.
Open up main-local.php config file in ```advanced/_protected/common/config/main-local.php``` 
and adjust your connection credentials.

6. Back to the console. It is time to run yii migrations that will create necessary tables in our database.
While you are inside ```_protected``` folder execute ```./yii migrate command```:

   ``` ./yii migrate ``` or if you are on Windows ``` yii migrate ```

7. Execute _rbac_ controller _init_ action that will populate our rbac tables with default roles and
permissions:

   ``` ./yii rbac/init ``` or if you are on Windows ``` yii rbac/init ```

You are done, you can start your application in your browser.

__*Tip__: if your application name is, for example, __advanced__, to see the frontend side of it you 
just have to visit this url in local host: ```localhost/advanced```. To see backend side, this is 
enough: ```localhost/advanced/backend```.

> Note: First user that signs up will get 'The Creator' (super admin) role. This is supposed to be you. This role have all possible super powers :) . Every other user that signs up after the first one will get 'member' role. Member is just normal authenticated user. 

Testing
-------------------

If you want to run tests you should create additional database that will be used to store 
your testing data. Usually testing database will have the same structure like the production one.
I am assuming that you have Codeception installed globally, and that you know how to use it.
Here is how you can set up everything easily:

1. Let's say that you have created database called ```advanced```. Go create the testing one called    ```advanced_tests```.

2. Inside your ```main-local.php``` config file change database you are going to use to ```advanced_tests```.

3. Open up your console and ```cd``` to the ```_protected``` folder of your application.

4. Run the migrations again: ``` ./yii migrate ``` or if you are on Windows ```yii migrate```

5. Run rbac/init again: ``` ./yii rbac/init ``` or if you are on Windows ```yii rbac/init```

6. Now you can tell your application to use your ```advanced``` database again instead of ```advanced_tests```.
Adjust your ```main-local.php``` config file again.

7. Now you are ready to tell Codeception to use ```advanced_tests``` database.
   
   Inside: ``` _protected/tests/codeception/config/config.php ``` file tell your ```db``` to use 
   ```advanced_tests``` database.

8. Start your php server inside the root of your application: ``` php -S localhost:8080 ``` 
(if the name of your application is advanced, then root is ```advanced``` folder) 

9. To run tests written for frontend side of your application 
   ```cd``` to ```_protected/tests/codeception/frontend``` , run ```codecept build``` and then run your tests.

10. Take similar steps like in step 9 for backend and common tests.

Directory structure
-------------------

```
_protected
    backend
        assets/              contains backend assets definition
        config/              contains backend configurations
        controllers/         contains Web controller classes
        helpers/             contains helper classes
        models/              contains backend-specific model classes
        runtime/             contains files generated during runtime
        views/               contains view files for the Web application
    common
        config/              contains shared configurations
        mail/                contains view files for e-mails
        models/              contains model classes used in both backend and frontend
        rbac/                contains role based access control classes
    console
        config/              contains console configurations
        controllers/         contains console controllers (commands)
        migrations/          contains database migrations
        models/              contains console-specific model classes
        runtime/             contains files generated during runtime
    environments             contains environment-based overrides
    frontend
        assets/              contains frontend assets definition
        config/              contains frontend configurations
        controllers/         contains Web controller classes
        models/              contains frontend-specific model classes
        runtime/             contains files generated during runtime
        views/               contains view files for the Web application
        widgets/             contains frontend widgets

assets                   contains application assets generated during runtime
backend                  contains the entry script and Web resources for backend side of application
themes                   contains frontend themes
uploads                  contains various files that can be used by both frontend and backend applications

```
Version 2.2.0 changes
-------------------
1) Adds `uploads` folder to the application root that can be shared by both frontend and backend applications.  
2) `@uploads` alias has been added, so you can use it in your code ( will target your_app_name/uploads folder )  
3) Additional translations are included. Thanks to MeFuMo and hior  
4) Alert widget call is added to backend main.php layout   
5) Minor fixes  

Version 2.1.0 changes
-------------------
1) option to CRUD articles ( posts ) has been added  
2) translation support has been included and Serbian translation has been added  
3) themes has been improved  
4) new roles, permissions and rules are added  
5) other code refactoring has been done  

Version 2.0 changes
-------------------

1) settings are stored in config/params.php configuration file to reduce database load  
2) account update is merged with user management and user management is more powerful now  
3) User model has been separated on UserIdentity and User (for easier understanding and use)  
4) 4 beautiful bootstrap responsive themes are included out of the box  
5) comment style is changed according to yii2 official style  
6) tests has been rewritten according to the changes that has been made  
7) a lot of other polishing has been done 

Password strength guide
-----------------------

Since 1.1.1 version has been released, password strength extension has been included as a core part of improved templates. Usage is very simple:

In our signup, user create/update and password reset forms password strength meter is always displayed when users are entering their password. This will give them visual representation of their password strength.  
But this is not all. As The Creator you have option in your settings "Force Strong Password" that you can use. If you turn it on, users will be forced to use strong passwords according to preset you chose. For example if you use normal preset, users will be forced to use at least 8 characters long password, with at least one upper-case and one lower-case letter, plus at least one digit.  

> Since version 2 settings are stored in config/params.php file!  

Choosing presets:

By default normal preset is used for signup and user create/update forms. For password reset we are using 'reset' preset if you want to customize which presets is used, see SignupForm model, User model and ResetPasswordForm model. You will see rules declared for using strong passwords. Presets are located in ```vendor/nenad/yii2-password-strength/presets.php```. You can chose some other preset declared in presets.php, or create new ones.

[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)
