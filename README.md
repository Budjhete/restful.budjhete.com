restful.budjhete.com
====================

Example web application that consume Budjhete RESTful api

This application has two main purposes. It first provides an example
application and guidelines to design RESTful client application and it is used
as a testing mechanism for the api. Also, this application serve as the main
documentation for the api.

The api is solely using the [JSON](http://json.org) data format.

## Authentication

There are two distinct authentification enabled on the api. You can authenticate
either with a registered user in the database or using an api key found in the
program configuration.

Using an api key, you may access a list of registered companies and details on
each of these companies.

Using a registered user, you have access to clients, products, invoices and so
on, just like you would do if you would login to your business.

Basic and Digest HTTP authentication is used for both of these authentications.

At first, if not authenticated, the api will respond you with a ```401``` status
code and will provide the appropriate header ```WWW-Authenticate```

```
WWW-Authenticate: Basic ...
```

Any request done to the api must provide the ```Authorization``` header. It may
either be Basic or Digest.

In case of a Basic authentication, 

```
Authorization: Basic base64(username + ':' + password)
Authorization: Basic base64(api_key)
```

In case of a Digest authentication

```
Authorization: Basic base64(username + ':' + md5(password))
Authorization: Basic base64(md5(api_key))
```

These headers must be systematically providen in order to access the api.

## Accessing the api

### Using the api key

The api key is stored in Budj'hète preferences. It can be generated in the 
preference window in the RESTful tab.

Get all companies

GET /companies

Get the preferences of a company

GET /company/<name>

### Using a username and password

Username and password must match a registered user in the company database.

Routing will always follow this pattern

<method> /company/<name>/<model>(/<id>(/<related>))

<method> is either GET, PUT, POST or DELETE.
<name> is the company name.
<model> is either client, product or invoice (for now).
<id> is a numerical key identifying the model. 
<related> is used to fetch related model on a specific model.

## Client

List all clients

```GET /company/<name>/clients```

List details of a client

```GET /company/<name>/client/<id>```

Create a new client

```PUT /company/<name>/client```

Update details of an existing client

```POST /company/<name>/client/<id>```

Delete an existing client

```DELETE /company/<name>/client/<id>```

Get invoices of a client

```GET /company/<name>/client/<id>/invoices```

## Products

List all products

```GET /company/<name>/products```

## Invoices

## Status

The api will respond using HTTP status code. A complete list of status code may
be found [here on Wikipedia](http://en.wikipedia.org/wiki/List_of_HTTP_status_codes).

Response will have a HTTP status and possibly a body and headers.

| Event | Status description | Status | Body | Headers |
| ----- | ------------------ | ------ | ---- | ------- |
| Fetched a model | Ok | 200 | <data> | |
| Updated a model | Ok | 200 | null | |
| Successfully created a model | Created | 201 | <id> | |
| Successfully deleted a model | No Content | 204 | null | |
| Unauthenticated access | Unauthorized | 401 | null | WWW-Authenticate: Basic |
| Submitting invalid or missing data | Forbidden | 403 | { <field>: <list of error message> } | |
| Accessing an unexisting model | Not Found | 404 | null | |
| Accessing an unexisting/unimplemented endpoint | Not Implemented | 501 | null | |

