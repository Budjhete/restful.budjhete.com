restful.budjhete.com
====================

Example web application that consume Budjhete RESTful api

This application has two main purposes. It first provides an example
application and guidelines to design RESTful client application and it is used
as a testing mechanism for the api. Also, this application serve as the main
documentation for the api.

The api is solely using the [JSON](http://json.org) data format.

## Accessing the api

### Using the api key

The api key is stored in Budj'hète preferences. It can be generated in the 
preference window in the RESTful tab.

Get all companies

```http
GET /companies
```

Get the preferences of a company

```http
GET /company/<name>
```

### Using a username and password

Username and password must match a registered user in the company database.

Routing will always follow this pattern

```http
<method> /company/<name>/<model>(/<id>(/<related>))
```

```<method>``` is either GET, PUT, POST or DELETE.
```<name>``` is the company name.
```<model>``` is either client, product or invoice (for now).
```<id>``` is a numerical key identifying the model. 
```<related>``` is used to fetch related model on a specific model.

## Client

List all clients

```http
GET /company/<name>/clients
```

List details of a client

```http
GET /company/<name>/client/<id>
```

Create a new client

```http
PUT /company/<name>/client
```

Update details of an existing client

```http
POST /company/<name>/client/<id>
```

Delete an existing client

```http
DELETE /company/<name>/client/<id>
```

Get invoices of a client

```http
GET /company/<name>/client/<id>/invoices
```

## Products

List all products

```http
GET /company/<name>/products
```

## Invoices

List all invoices

```http
GET /company/<name>/invoices
```

## Filtering

When fetching multiple models, it is possible to filter results using the HTTP
query.

Filter with the WHERE clause

```json
{
    "where": [
        ["column", "operator", "value"]       
    ],
    "order_by": "column",
    "order_by": ["column1", "column2"],
    "group_by": "column"
}
```

## Status

The api will respond using HTTP status code. A complete list of status code may
be found [here on Wikipedia](http://en.wikipedia.org/wiki/List_of_HTTP_status_codes).

Response will have a HTTP status and possibly a body and headers.

| Event | Status description | Status | Body | Headers |
| ----- | ------------------ | :----: | :--: | ------- |
| Fetched a model | Ok | 200 | ```{ field: value }``` | |
| Updated a model | Ok | 200 | ```null``` | |
| Successfully created a model | Created | 201 | ```id``` | |
| Successfully deleted a model | No Content | 204 | ```null``` | |
| Unauthenticated access | Unauthorized | 401 | ```null``` | WWW-Authenticate: Basic |
| Submitting invalid or missing data | Forbidden | 403 | ```{ field: [message] }``` | |
| Accessing an unexisting model | Not Found | 404 | ```null``` | |
| Accessing an unexisting/unimplemented endpoint | Not Implemented | 501 | ```null``` | |

