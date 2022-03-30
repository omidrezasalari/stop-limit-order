# Stop Limit Order
## The process of implementing the Stop Limit Order project is as follows:
- First, when the user registers the stop order limit, the stop price
It is registered in the relevant cache based on the type of order
- Then when the price is in the queue for a moment through the command
- The price is received instantly and according to the rules related to the queues of buying and selling stop limit orders
Orders are processed sequentially and added to the trading cycle

#### To get the instant price, set up the Laravel server and send a request to :

```sh
localhost.test/make-instant-price
```

#### To get the instant price and call the stop-order orders that are ready to be executed by calling:

```sh
php artisan check:insert {instantPrice}
```
#### To process the stop limit order process and send it to the queue:

```sh
php artisan check:insert {instantPrice}
```
###### The process of implementing the Stop Limit Order project is as follows:
- Receive the content that users have registered in the purchase and sale order caches
- Compare the prices of stop orders registered by users with the current price based on the rules of buying and selling
- Send fetched orders to your respective queues
- Reset cache sales orders
- Send the order ID to the queue to update the information in the database

#### This command is for receiving and displaying messages sent to sales queues:

```sh
php artisan message:received
```

#### For creating test orders to perform the testing process
```sh
php artisan fake:order
```
#### Order execution of orders after sending the instant price request.
```sh
php artisan get:price
php artisan message:received
```

#### Postman request documentation
```sh
https://documenter.getpostman.com/view/6454018/TVRd9X39
```

Verify the deployment by navigating to your server address in
your preferred browser.

```sh
127.0.0.1:8000
```
