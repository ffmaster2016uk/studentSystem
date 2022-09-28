## Student System setup

- Make sure you have docker and docker-compose installed on your local environment
- There is a docker-compose.yml file at the root level of this repo, you can adjust the settings in there if you'd like to use different ports etc
  - With more time I could have made more of the settings loading from the .env file.
  - images, config files are located in the infrastructure folder at root level, in the respective folder for nginx, php and mysql
- From the root level of this repo, where the docker-compose.yml file is, run the following command:
```
sudo docker-compose up -d --build --force-recreate
```
- Depending on your local setup, you might need to grant more permission to the storage folder in laravel, but as this is just a local setup, please run command below (on production environment, I would recommend looking into checking the users groups for docker user and server user, making sure they are in the same group and have enough permission to write to this folder)  :
```
sudo chmod 777 -R storage
```
- From the root level of the repo, there is the laravel .env file, if you changed any settings in the docker-compose.yml file, make sure to update accordingly here, for example, if you changed the db username and password
  - The local domain name for the up have been set to http://students-system.local:8099, using port 8099 to reduce changes of conflicting ports, make sure you add a host entry on your local to point students-system.local to 127.0.0.1
- Next run the following command to be able to execute commands in the php container:
```
sudo docker exec -it ss_php bash
```
- Once inside the php container, run the following commands
  - Composer to install the dependencies
  - Migration to create the db tables
  - Seeding a default user into the system
    - Username: william.gu@blueyonder.co.uk
    - Password: password
```
composer install
php artisan migrate
php artisan db:seed
```

- Once the system is ready, from the php container, run the tests:
```
php artisan test
```

### System usage guide
I would recommend using Postman to test the system: https://www.postman.com/downloads/

To use the API end points, please first log in with the seeded user, if I had more time, I would have provided an interface to register an user (laravel auth did created one, but I've not tested it to make sure it work)

```
Send post request to: http://students-system.local:8099/api/login
```
With the following params:

- email: william.gu@blueyonder.co.uk
- password: password

Expected response similar to:

```
{
    "status": "success",
    "token": "4|LNMJcX9XA1QCSnuYotuR3I80qLwTZrrRoRlYNMtt"
}
```
Please take note of the token to use for subsequent requests

To create a new student record:
```
Send put request to: http://students-system.local:8099/api/students/store
```
The validation rules for this request are:
```php
[
    'Name' => 'required|legal_characters',
    'Surname' => 'required|legal_characters',
    'IdentificationNo' => 'required|legal_characters',
    'Country' => 'nullable|legal_characters',
    'DateOfBirth' => 'nullable|date_format:Y-m-d',
    'RegisteredOn' => 'nullable|date_format:Y-m-d',
];
```
To update an existing student record:
```
Send patch request to: http://students-system.local:8099/api/students/update
```
The validation rules for this request are:
```php
[
    'Id' => 'required|integer',
    'Name' => 'required|legal_characters',
    'Surname' => 'required|legal_characters',
    'IdentificationNo' => 'required|legal_characters|unique:students,IdentificationNo',
    'Country' => 'nullable|legal_characters',
    'DateOfBirth' => 'nullable|date_format:Y-m-d',
    'RegisteredOn' => 'nullable|date_format:Y-m-d',
];
```
To view/retrieve all students:
```
Send get request to: http://students-system.local:8099/api/students/
```
To view/retrieve a particular student (replace id number after students/ with the id of the student you wish to retrieve:
```
Send get request to: http://students-system.local:8099/api/students/1
```
To search for a student record:
```
Send post request to: http://students-system.local:8099/api/students/search
```
The validation rules for this request are:
```php
[
    'searchField' => 'required|legal_characters',
    'searchValue' => 'required|legal_characters',
];
```

### Thoughts, notes and possible improvements on the task

- As I'm not a huge fan of using database to imply business requirements, I've not created any restrictions on fields that can't be null, or have to be unique, or foreign key constraints, as I think db is a storage media, and the system itself should be the one that enforces business logic
- I implemented the system as close as possible to my understanding of the requirement, as I was not sure how strict we are with these requirements. I would recommend the following:
  - It would be easier with laravel if the Id field on the student table is 'id', less customization needed
  - Requirement didn't specify the IdentificationNo to be unique, but I added the validation rule in, as I think it would make sense
  - There wasn't a clear specification on how the search should work, I've implemented a very simple search, where you have to specify the field to search on, and the value to search for, with more time, I could extend this search to use elastic search, so we can return results based on relevance, implement fuzzy search, spell correction, and full name search etc
  - In term of auditing, I've implemented recording of created_at, created_by, updated_at, updated_by for each record, as well as an activity log that records what data was set for create/update, what the previous data was, and what data have been changed. It could be implemented in different ways if there are more detailed specifications on how this should work, or what is required
  - On authorization, if I had more time, I could look into implementing a refresh token as well as the access token, interface to create user etc
  - I've only done feature tests for the core functionality of the system, if I had more time, I would do break the testing down more into unit level, and cover the user section
- The docker environment setup could be improved with more variables loaded from the .env file
   
