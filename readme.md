# Hyve Mobile PHP Candidate Project


### Docker

### Usage

the subscription endpoint is found at `/api/subscription?msisdn=xxxxxx&service_id=y`

`GET`: returns the subscription details for the msisdn and service combination
`GET`: without either the msisdn or the service returns a list of subscriptions paginated for the other options 
`POST`: creates a subscription if it doesn't exist, returns the subscription as per above if it does exist for the msisdn and service combination. if a new subscription is added it will charge it at the same time. (hook: "subscription.created")
`PUT`: if the subscription exists for the combination this is the charge endpoint. it will set the active and charged fields accordingly (event: "subscription.charge")
`DELETE`: this is the cancel endpoint, this sets the active flag to 0 and canceled_at field to the current datetime. (event: "subscription.cancel").


The record is created and then a `subscription.created` event is fired afterwards to stop race conditions. 
charge and cancel are events that execute the actions on them for each respectively.

(since php isn't by reference - unless & is used - it would involve every event fetching the current state of the record for their own since you can't guarantee that they get the latest).  

system logs api: `/api/logs` and `api/logs/x` for details on a specific log

---------------------- 

## logic considerations

ive purposefully built this up around the "app" and not the implementation around the subscription part. Stuff that should be included but keeping it simple since this a demo system:

- excluding RBAC / user controls / access
- Subscriptions are simple and not follow on to each other


## The why and how

### Hooks
Im having fun with this project. Some pretty cool tech using the Events package. I've added some "hooks" (event listeners) for:

- Logs
- Errors (errors call logs in any-case)
- page
  - start
  - end

The page end can use the profiler to maybe call something else if the page took too long to load or something (emit to the logger?). This is definitely not part of the task spec but you cant give me a new toy without me taking it for a spin to see what is possible! 


# Structure

Webserver points to the public folder. that way there's very little chance that even with a configuration issue that any sensitive info gets bled out like configs n stuff. it also makes sure that all "access" goes through all "checks" in place in the app.

### Folder Structure

I'm pretty sure my "models" should be called "services" and my /app/tables/ (eloquent models) should be my models but im not totally comfortable using eloquent models as first class in my app yet.. (todo...?) 
 
- `App`: App implementation code
  - `Domain`: business logic grouped into noun
      - `Events`: ive placed the events in the domain folders cause most of the time they would be specific to that domain action as far as i can figure out
      - `Repositories`: data fetcher stuff
      - `Models`: a model of the record, any additional methods the model needs on it etc - these are probably supposed to be called services. 
      - `Schemas`: if you want to return the object to json then hand it to the schema to return an array.
  - `Middleware`: any application middleware or middleware that can be used in multiple places
  - `Pages`: the "endpoints" and any "output" stuff
      - `Controllers`: slims normal controllers
      - if this was outputting templates n stuff i would include them in here somewhere (UI layer)
      - `Middleware`: any page group specific middleware (like `/user[/user_id]` that then adds gets the user with that id and adds it to `getAttribute` on the request)
  - `Responders`: in a normal project there would probably be about 3 different types of responders, json, media, template. i like keeping them "seperated" and since they can vary based on implementation details they dont totally belong in the system folder.

- `System`: basically a vendors' folder of stuff that i want to reuse (like a framework on top of the framework) 

### Files in App
- `Application.php`: main glue for all the parts, returns a slim app instance
- `Config.php`: the default config with constraints. Include this file in version control. this loads `config.php` (environment stuff that changes the default like db creds n stuff) file in the root of the project - DONT INCLUDE the root config.,php file in version control!
- `Container.php`: PHP-DI containers
    - Slim bootstrap stuff
    - `Errors`: friendly error messages
    - `Events`: the container for the event system
    - `Messages`: at any point in the life cycle messages can be added that get sent with the payload
    - `Log`: needed a way to log to an event (grrr i hate complicated but this seems to work, "if it's stupid but it works, is it still stupid?")
- `DB.php`: eloquent is... "interesting"
- `Events.php`: this should probably be renamed to "Listeners". using phpleage events is new to me so getting it working any way i can
- `Middleware.php`: include all app middleware ain its order etc
- `Routes.php`: calls the App\Pages\Controllers\... for the various routes. this uses slims routing

# Convenience stuff included

### Collections

You can turn any iterable into a collection. 

any method or property called against a collection will first check if its on the collection, if not it will cycle through each item in the collection and return a new collection / array of the results. 


`$collection->first();` returns the first object / row

`$collection->last();`  returns the last object / row

`$collection->id;` returns a collection of object id's, can sue the ->toArray() method to turn it into an array of ids

`$collection->method();` returns a collection of object results of the method


### Schemas

Take any object and turn it into an array for outputting the json. 

`$collection->toSchema(new ObjectSchema());`

`$collection->toSchema(new ObjectSchema($param1,$param2));`

`$collection->toSchema(function($item){ return array("id"=>$item->id) });`

`$collection->toSchema(function($item,$param1,$param2){ return array("id"=>$item->id) },"param-1","param-2");`

`$object->toSchema(...)`


You can parse parameters to the schema as well so if you want to for instance add a "selected" key then you can pass the id to the schema and do a `"selected"=>$item->id == $selected`

I got tired of trying to keep track of all my return json_encode(array(...)) stuff in the past. this seemed like a good idea (all the existing schema type stuff seems sooo heavy to me), now i can't live without it.



### Stuff that counts against me here

- Automated tests (it bugs me a lot but i just haven't been able to ace it)
- Huge docstrings (all impressive code is like half the page of comments)
- Haven't found a way to have decent typing for "Collections" yet (like say typescript ObjectType[] ). 
- There's a great chance im using terms wrong, like repositories/models/services, ive looked at other frameworks and opinionated pieces and tried to stick with what makes sense to me. 


--------------

# Confession

I did copy in the validators and some system stuff (collection / pagination) and /update/... - but they are definitely my work and not just copy-pasta from the web.  

I copied some of the docker related things