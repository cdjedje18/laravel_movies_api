# Movie API

This is a simple API for reading movie information from a small demo database.
In this API, the client will be able to perform the following actions:
* List movies;
* Register new movies
* List actors
* Register new actors
* Relate actors and their films


This API was based on the following data model:
![image info](https://mozapi.000webhostapp.com/online/movie_data_mode.png)

## Configuration

Clone the project
> git clone <project>

Copy the contents of the env.example file to your env file

Install all dependencies
> composer install

Create the tables by running the migration command
> php artisan migrate

Execute the sql script at your database
> location /public/movie_db.sql


## How to Use

### Movies
List Movies
```sh
GET api/movies
```


Select desired fields
```sh
GET api/movies?fields=id,name,year
```

Include actors information
```sh
GET api/movies?fields=name,actors[*]
```

Filter movies with runtime above 90 minutes
```sh
GET api/movies?filter=runtime:gt:90
```

### Available Filters
| Operator | Expression | Description |
| ------ | ------ | ------ |
| eq | = | Equal to |
| ne | != | Not equal to |
| like | %like% | Like |
| gt | > | Greater than |
| gte | >= | Greater than or equal to |
| lt | < | Less than |
| lte | <= | Less than or equal to |


Create Movie

```sh
POST api/movies
```
Payload
```json
{
    "name": "Blacklight",
    "year": 2022,
    "runtime": 105,
    "releasedate": "2022-02-25",
    "storyline": "Travis Block is a government operative coming to terms with his shadowy past. When he discovers a plot targeting U.S. citizens, Block finds himself in the crosshairs of the FBI director he once helped protect."
}
```

Update movie
For the update action, we have differents options to do it, one is using a PUT request passing the object id at the url and the attributes we want to update at the paylod. Another way is to make a POST request, ant passing the object id at the payload as one of the attributes.

```sh
PUT api/movies/1QW6ZLB2KMO
```

Payload
```json
{
    "year": 2022,
    "runtime": 105,
    "releasedate": "2022-02-25",
}
```

```sh
POST api/movies
```

Payload
```json
{
    "id": "1QW6ZLB2KMO",
    "name": "Blacklight",
    "year": 2022,
    "runtime": 105,
    "releasedate": "2022-02-25",
    "storyline": "Travis Block is a government operative coming to terms with his shadowy past. When he discovers a plot targeting U.S. citizens, Block finds himself in the crosshairs of the FBI director he once helped protect."
}
```


Delete movie

```sh
DELETE api/movies/1QW6ZLB2KMO
```

### Actors
List Actors
```sh
GET api/actors
```

Select desired fields
```sh
GET api/actors?fields=id,name
```

Filter actors with name
```sh
GET api/actors?filter=name:like:Daddario
```

Create actor
```sh
POST api/actors
```

Payload
```json
{
    "name": "John Cena",
    "birthname": "John Cena",
    "birthdate": "1977-04-23",
    "birthplace": "West Newbury, Massachusetts, United States"
}
```

Update Actor
```sh
PUT api/actors/W529Mv1CF48
```

Payload
```json
{
    "birthname": "John Cena",
    "birthdate": "1977-04-23"
}
```

```sh
POST api/actors
```

Payload
```json
{
    "id": "W529Mv1CF48",
    "name": "John Cena",
    "birthname": "John Cena",
    "birthdate": "1977-04-23",
    "birthplace": "West Newbury, Massachusetts, United States"
}
```

Delete actors
```sh
DELETE api/actors/W529Mv1CF48
```