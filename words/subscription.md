[<- Back](../readme.md)
# Subscription

in its current form this app is very basic. 

Normally i would consider using one of the following options:

- if the subscription endpoint is hit to charge it will then look at the last active subscription, if its active then append the subscription (a maximum amount of times perhaps) and if the previous subscription is expired then continue as if its a new subscription. 

- when the subscription endpoint is hit it requires a "period" the subscription is available for. so like 2022-07 or a "from" and a "to". this would be my preferred option. if there's already a subscription for the period then just return the error and the subscription thats saved. this will at least let the endpoint be hit multiple times with the same data and it wont change anything on the server side since it already exists etc. 

