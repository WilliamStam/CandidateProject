# Hyve Mobile PHP Candidate Project


## Docker

`docker-compose up` should start the services. 
The mysql initialization takes a while tho

## Usage

#### Subscription

the subscription endpoint is found at `/api/subscription?msisdn=xxxxxx&service_id=y`

`GET`: returns the subscription details for the msisdn and service combination
`GET`: without either the msisdn or the service returns a list of subscriptions paginated for the other options. each "option" key can be specified in the query string 
`POST`: creates a subscription if it doesn't exist, returns the subscription as per above if it does exist for the msisdn and service combination. if a new subscription is added it will charge it at the same time. (hook: "subscription.created")
`PUT`: if the subscription exists for the combination this is the charge endpoint. it will set the active and charged fields accordingly (event: "subscription.charge")
`DELETE`: this is the cancel endpoint, this sets the active flag to 0 and canceled_at field to the current datetime. (event: "subscription.cancel").


#### System Logs
The record is created and then a `subscription.created` event is fired afterwards to stop race conditions. 
charge and cancel are events that execute the actions on them for each respectively.

(since php isn't by reference - unless & is used - it would involve every event fetching the current state of the record for their own since you can't guarantee that they get the latest).  

system logs api: `/api/logs` and `api/logs/x` for details on a specific log

### List Options

most of the lists (logs / subscriptions) have an options key. this is the options passed in to the backend. 

```
"options": {
    "search": null,
    "level": null,
    "page": 1,
    "paginate": 3,
    "order": {
      "id": "desc"
    }
  },
```

These would just be query string params. The order option can be specified as a column|order or a CSV of columns and direction `?order=log|desc,id|desc` 

Passing in a number to `?paginate=x` will paginate to that amount and add in a pagination key with next / previous / info etc. Use `?page=y` to cycle through the pages.