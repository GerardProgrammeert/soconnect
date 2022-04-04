
## Install
1. Download repository
2. Setup a database and config .env to connect to db
3. Run migration
4. Create new coffee machine's via /api/espresso-machine/config


## Assumptions
1. Since it is a Restfull API, I didn't use sessions or cookies to persist the status. Instead I created models to save the data. 
2. Since in the real world espresso machines also has to be fill manually, I added end-point to allow the user to fill the containers. However it is possible to setup listeners and events on the level of the containers to automate the fill.
4. 
