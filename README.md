# Spacetrip
An trip Agency that organizes trips to space (on development)
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

- [Make a trip reservation on an existing trip](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/standard_trip.md) 
- [Make payment via Paypal](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/payment.md)
- [Create your own trip](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/reserved_trip.md)
- [Make a feedback](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/feedback.md)
- [See documentations about destinations and spacecrafts provided by Spacetrip](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/documentation.md)
- [An admin part to manage all new databases entries or updates, and charts to easily have an view of customers feedbacks](http://github.com/AlexandreRavichandran/Spacetrip/blob/master/docs/admin.md)
