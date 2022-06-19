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

### Avalible Filters
| Operator | Expression | Description |
| ------ | ------ | ------ |
| eq | = | Equal to |
| ne | != | Not equal to |
| like | %like% | Like |
| gt | > | Greater than |
| gte | >= | Greater than or equal to |
| lt | < | Less than |
| lte | <= | Less than or equal to |

