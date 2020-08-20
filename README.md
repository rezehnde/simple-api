# Simple API

A Simple API project using just [Symfony Framework 5](https://symfony.com/what-is-symfony).

## :star: Requisites

Write a simple API (using at least Symfony 4.2) for showing a list of users, and creating a new user.

The goal is to be able to swap out the data source for users without having to touch any of the code that uses the data source and returns the response and also provide documentation for consuming the API.

### Important Rules
+ Is not possible to insert or update an user with an invalid e-mail
+ Is not possible to insert or update an user with an e-mail that belongs to other user
+ Is not possible to insert or update an user with any empty field

## :muscle: Try it out 

### Show All Users
GET to https://simple-api.rezehnde.com/

### Create User
POST to https://simple-api.rezehnde.com/user/create with body:
```
{
  email = 'rezehnde@gmail.com',
  first_name = 'Marcos',
  last_name = 'Rezende'
}
```

### Read User
GET to https://simple-api.rezehnde.com/user/1
where 1 is the user id

### Update User
POST to https://simple-api.rezehnde.com/user/update/1 with body:
```
{
  email = 'rezehnde@gmail.com',
  first_name = 'Marcos',
  last_name = 'Rezende'
}
```

### Delete User
POST to https://simple-api.rezehnde.com/user/delete/1
where 1 is the user id

## :house: Running locally (with Docker)
```
mkdir simple-api
cd simple-api
git init
git remote add origin https://github.com/rezehnde/simple-api.git
git pull origin master
docker-compose up -d --build simpleapi
docker-compose run --rm composer update
docker-compose run --rm php bin/console doctrine:database:create
docker-compose run --rm php bin/console doctrine:migrations:migrate
docker-compose run --rm php bin/console doctrine:fixtures:load
```

## :triangular_ruler: Built with 

* [Symfony 5](https://symfony.com/what-is-symfony) - PHP Framework
* [Composer](https://getcomposer.org/) - A Dependency Manager for PHP

## :trophy: Authors 

* **Marcos Rezende** - *Initial work* - [Software Engineer](https://github.com/rezehnde)

### :mag: Backlog 

* [ ] Implement an authentication method to handle the API
* [ ] Include Unit Tests and Functional Testes
* [ ] CI/CD using Git on a Shared Host with Jenkins
