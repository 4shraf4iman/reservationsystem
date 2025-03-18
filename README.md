
Requires Installation of:
-Php and Composer
-Laragon or Xammp
-Nodejs

#Setup Code: 

1- git clone into laragon WWW file or XAMMP file in local desktop file using CMD:
"git clone https://github.com/4shraf4iman/reservationsystem.git"
2-"cd reservationsystem"
3- copy .env.example and rename to .env
4- open .env file then you can see database name, username and password
5- now open xampp or laragon, create new database with name from .env , username root with password none

#SETUP LARAVEL

1 - now go back to CMD in 'reservationsystem' file and run "composer intall" command
2 - if any token required, use this composer config --global --auth github-oauth.github.com [token], and then run 'composer install'
3 - after install package complete, run "php artisan migrate:fresh --seed" to seed database
4 - run 'php artisan serve' and it shoudl work.

#SETUP VueJS
1- Now navigate to root folder for 'kidosys front end' inside reservationsystem folder
2- Now copy file named '.end-example' and paste in same place and renamed to '.env'
3- Now open CMD and run command 'npm install' inside 'kidosys front end' folder
4- After success install, run 'npm run dev'.

KEEP NOTE U NEED TO RUN 2 CMD WHICH IS FOR LARAVEL AND VUEJS

PAGE AVAILBALE IN THIS PROJECT:

Booking Page - http://localhost:5173/reserve
Login Page for Admin - http://localhost:5173/
Admin Reservation Page - http://localhost:5173/admin

Credential to Login:
email : superadmin@kiddosys.com
password : admin


