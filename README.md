

## About Project

Simple theses manager in Laravel


## Project requirements 

- Create a new Laravel installation that will be in the Tasks directory. The application should have the ability of registration of new users (users register themselves). There are three types (roles) of users admin, teacher and student. Only the admin user can change user roles, i.e. assign them to someone the role of teacher or student.
- It is necessary to make a new migration for the tasks table in which users with the teacher role will add final and graduate theses. It is necessary to keep the title of the paper, the title of the paper in English, work assignment and type of study (undergraduate and graduate).
- Provide the teacher with a page for adding papers in English and Croatian. Laravel has the possibility of multilingual pages. NOTE: it is possible to only change language when you are logged in as user (all users, not only teacher). In lang folder, are added translations for whole project, although like I said functional only on /home page. 
- Show the user with the student role a list of papers to which he can apply.
- On the application page, the teacher user can see the students who applied for his papers and accept one of the students.

## Instructions

- Admin user is created through:  ```php artisan db:seed ```
- Admin login info; email: admin@example.com, password: root

## First look

Welcome page -
<p align="center"><img src="https://i.imgur.com/BRa8jV8.png"  style="padding-bottom:30px"></p>

Admin dashboard - 
<p align="center"><img src="https://i.imgur.com/Zj0gED5.png" style="padding-bottom:30px;"></p>

Student dashboard EN - 
<p align="center"><img src="https://i.imgur.com/MHuRuJ9.png" style="padding-bottom:30px;"></p>

Student dashboard HR - 
<p align="center"><img src="https://i.imgur.com/OYUkmGc.png" style="padding-bottom:30px;"></p>

Professor dashboard (add thesis) -
<p align="center"><img src="https://i.imgur.com/gVl0nDV.png " style="padding-bottom:30px;"></p>

Professor dashboard (edit) -
<p align="center"><img src="https://i.imgur.com/SH5zwzh.png " style="padding-bottom:30px;"></p>

## Related

This project is an upgrade to 'Simple project Manager' that I made: 
[Simple Project Manager](https://github.com/Gubinator/Laravel-Simple-Project-Manager)

