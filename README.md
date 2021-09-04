# Spacetrip
An trip Agency that organizes trips to space (on development)

This project is for training purpose only. Don't use real infos here ! 

The website is available here : https://spacetrip-app.herokuapp.com/
# Requirements

- Symfony > 5.0.0
- PHP > 7.2
- Apache server
- MySQL or PostGRESQL

# How to run

1) Clone the project on your local repository

`$ git clone https://github.com/AlexandreRavichandran/Spacetrip.git`

2) go to .env file, remove the # and change the DATABASE_URL depending on your database software

3) Create the database with either Symfony CLI (if you have it installed ) or basic php console commands.

`symfony console doctrine:database:create`

or

`php bin/console doctrine:database:create`

4) Make all migrations to the database to create and update all fields on the database

`symfony console doctrine:migrations:migrate`

or 

`php bin/console doctrine:migrations:migrate`

5) (OPTIONNAL) Create fixtures to add some fake data so that you can explore all features of the website

`symfony console doctrine:fixtures:load`

or

`php bin/console doctrine:fixtures:load`
# Features

You can see here all features that Spacetrip provides : 

- Make a trip reservation on an existing trip
- Make payment via Paypal
- Create your own trip
- Make a feedback
- See documentations about destinations and spacecrafts provided by Spacetrip
- An admin part to manage all new databases entries or updates, and charts to easily have an view of customers feedbacks


# Origin of the project
This project was the first one which has been made with the symfony framework. I tried to use everything I learned into a project, like CRUD, API requests, Form managing while having a good code writing and of course a good security of the website. I wanted to create a E-commerce website, because I think that practicing a E-commerce style of website allows to cover all skills expected for a Back-end developer. 

I never saw a website about space trips on Github. This motivated me to create a fake agency website that organize trips to space.Most of the features used in this project are also normally used for E-commerce website, except some features (like creating a trip) which are used specifically for this project.

In this project, I had particulary made attention on good-working of every features instead of the website's design (altough I know that website's design is as important as goodworking of features)