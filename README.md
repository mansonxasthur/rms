# RMS (Restaurant Management System)

## Disclaimer:
- I build this project with a package of mine called [Dust]:(https://github.com/Cyberbugz/dust).
- The aim to help me to build a DDD architecture with some helpers based on experience with such architecture.
- I put mind handling redundant procedures I do in my work.
- There's a room for improvements, still a good start point.
- Feel free to ask about it.

<hr>

## Steps to get up and running:
- Prerequisite:
  - Docker installed on your machine.
  - Docker compose cli installed on your machine.
  - Port `8080` should not be busy.
- Change directory to root folder.
- Run `docker compose up -d --build`.
- Once images are built and started run `docker compose exec api bash`
- Inside the container run `composer install && php artisan migrate --seed`
- Seeding in the previous step just for the purpose of testing on Postman if needed.
- Application now running on `http://localhost:8080`.

<hr>

## Notes:
- You can run tests inside the `api` container by executing `php artisan test` command.
- Presumptions:
  - No authentication required for this challenge as per the provided document.
  - Inventory replenishment flow not required for this challenge.
  - Merchant email is a static email we got from the config.
- My approach for this challenge were based solely on requirements, in a real world scenario I'd
  handle the inventory structure differently in a way that gives us traceability, also I'd use fixed threshold
  instead of percentage but that's up to business requirements and planning team.
- Caching is applicable in some parts of this project, I assumed it's not required.

<hr>

## Action:
### Create order `POST` `/api/orders`

#### Request body:
```json
{
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```

#### Responses:
##### Order created `200`:
```json
{
    "data": {
        "id": 1,
        "status": "Pending",
        "products": [
            {
                "id": 1,
                "name": "Burger",
                "price": "100.5",
                "ingredients": [
                    {
                        "id": 1,
                        "name": "Beef",
                        "amount": 150
                    },
                    {
                        "id": 2,
                        "name": "Cheese",
                        "amount": 30
                    },
                    {
                        "id": 3,
                        "name": "Onion",
                        "amount": 20
                    }
                ],
                "quantity": 2
            }
        ],
        "sub_total": 201
    }
}
```
  
##### Invalid field value Or missing value `422`:
```json
{
    "message": "The products.0.quantity field must be at least 1.",
    "errors": {
        "products.0.quantity": [
            "The products.0.quantity field must be at least 1."
        ]
    }
}
```
```json
{
    "message": "The products.0.product_id field is required.",
    "errors": {
        "products.0.product_id": [
            "The products.0.product_id field is required."
        ]
    }
}
```

##### Insufficient ingredients `422`:
```json
{
    "message": "Insufficient ingredients to prepare the order!"
}
```


### List products `GET` `/api/products`
#### Responses:
##### Product list `200`:
```json
{
    "data": [
        {
            "id": 1,
            "name": "Burger",
            "price": 100.5,
            "ingredients": [
                {
                    "id": 1,
                    "name": "Beef",
                    "amount": 150
                },
                {
                    "id": 2,
                    "name": "Cheese",
                    "amount": 30
                },
                {
                    "id": 3,
                    "name": "Onion",
                    "amount": 20
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost:8080/api/products?page=1",
        "last": "http://localhost:8080/api/products?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8080/api/products?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8080/api/products",
        "per_page": 20,
        "to": 1,
        "total": 1
    }
}
```
