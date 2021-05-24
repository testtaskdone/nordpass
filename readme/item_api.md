# Item API

## List
### Request
GET /item
### Response example
```json
[
    {
        "id": 2,
        "data": "very secure new item data",
        "created_at": {
            "date": "2021-05-23 14:06:29.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2021-05-23 14:06:29.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    },
]
```

## Create
Post /item
```
data="some data"
```

## Update
Put /item
```
id=1&data="some data"
```

## Delete
DELETE /item/{id}
