# Espresso Machine Assessment
For the company SoConnect I did an assessment for making espresso via an API
# Install
1. Download repo
```
$ git clone https://github.com/GerardProgrammeert/soconnect.git
```
2. Run docker-compose
```
$ docker-compose up -d
```

# API Endpoints
This section describes all endpoints
## POST /api/espresso-machine/config

### description
creates a new espresso machine1

### parameters

With the parameters you can set the capacity and current level. 
If no parameters are provided, defaults will be used. 

| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|water_container_level|no|float|Set current level for the water container(liters)|
|beans_container_level|no|integer|Set current level for the beans container(# spoons)|
|water_container_capacity|no|float|Set max capacity water container(liters)|
|beans_container_capacity|no|integer|Set max capacity bean container(# spoons)|

## POST /api/espresso-machine/{id}/config

### description
reconfig an existing espresso machine

### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|water_container_level|no|float|Set current level for the water container(liters)|
|beans_container_level|no|integer|Set current level for the beans container(# spoons)|
|water_container_capacity|no|float|Set max capacity water container(liters)|
|beans_container_capacity|no|integer|Set max capacity bean container(# spoons)|
|id|required|integer|id of espresso machine|

## POST /api/espresso-machine/{id}/add-water
### description
Fill an espresso machine with water
### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|id|required|integer|id of espresso machine|
|water|required|float|add liters of water to the machine|
## POST /api/espresso-machine/{id}/add-beans
### description
Fill an espresso machine with beans
### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|id|required|integer|id of espresso machine|
|beans|required|integer|add number of spoon with beans to the machine|

## GET /api/espresso-machine/

### description
Get all espresso machines

## GET /api/espresso-machine/{id}/one
### description
Get an espresso from an espresso machine
### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|id|required|integer|id of espresso machine|
## GET /api/espresso-machine/{id}/double
### description
Get a double espresso from an espresso machine
### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|id|required|integer|id of espresso machine|

## GET /api/espresso-machine/{id}/status
### description
Get status of an espresso machine
### parameters
| Name  | Required | Type |Description |
| ------------- | ------------- | ------------- |------------- |
|id|required|integer|id of espresso machine|

# Assumptions
1. Since it is a Restfull API, I didn't use sessions or cookies to persist the status. Instead I created models to save the data.
2. Since in the real world espresso machines also has to be fill manually, I added end-point to allow the user to fill the containers. However it is possible to setup listeners and events on the level of the containers to automate the fill.

