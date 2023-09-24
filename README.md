# Connex CXM

## Introduction

Library for working with Connex CXM dialler.

## Config

You may publish the config file as follows:

```bash
php artisan vendor:publish --tag=cxm-config
```

Add the following items to your .env file:

```dotenv
CXM_CLIENT_ID=
CXM_SECRET=
CXM_TOKEN=
CXM_ENDPOINT=https://apigateway-your-company-name-cxm.cnx1.cloud
```

The `CXM_CLIENT_ID` and `CXM_SECRET` are set up on the dialler web interface at:

> Admin > API > API Credentials > Create API Credentials

The `CXM_TOKEN` is set up on the dialler web interface at:

> Admin > API > API Clients > Create API Clients
 
The `CXM_ENDPOINT` value is the URL of the API endpoint, supplied by Connex. It will be similar in format to the example above.

## Usage

Here are how the basic functions of the library work:

```php
// Create a Contact DTO
$contact = Contact::make([
    // ...attributes
]);

// Create a DataList DTO
$dataList = DataList::make([
    'id' => '...' 
]);

// Load the contact into the dialler.
// $result reflects the Contact record created on the dialler and contains its ID
$result = Cxm::customerLoadSingle($contact, $dataList);
```

## Security Vulnerabilities

Please [e-mail security vulnerabilities directly to me](mailto:matt@mralston.co.uk).

## Licence

PDF is open-sourced software licenced under the [MIT license](LICENSE.md).
