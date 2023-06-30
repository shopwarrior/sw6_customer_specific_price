# GSCustomerSpecificPrice
  
## Table of Contents  
1. [Tickets](#tickets)
2. [Description](#description)
3. [Installation / Requirements](#installation-requirements)
4. [Usage](#usage)
5. [Author](#author)
  
## Tickets  
N/A
  
[top](#table-of-contents)  
  
## Description  
Implement CLI command which fetch data from 3rd part service and import it as separate customer specific price for each product
Product should have a single price with the following values:

➢ Net Value

➢ Gross Value

➢ List Net Value

➢ List Gross Value

 - Customer-specific prices should be the highest priority when calculating line item prices and displayed on the detail page. This means that when a customer views a product or adds it to their cart, the customer-specific
   price should be used if it exists.
 - After each order, decrease the customer-specific price for each product by 1%. The minimum threshold for the price should be defined in the plugin configuration. (Hint: this process should not affect the checkout finish
   process performance)
  
[top](#table-of-contents)  
  
## Installation / Requirements  
```
cd [shopware_root]
composer require gs/customer-specific-price
bin/console plugin:refresh
bin/console plugin:install --activate GSCustomerSpecificPrice
bin/console cache:clear
bin/console database:migrate GSCustomerSpecificPrice --all
```
  
[top](#table-of-contents)

## Usage
```
bin/console gs:import-customer-command SWDEMO10000
bin/console gs:import-customer-command SWDEMO10000 SWDEMO10007
```

[top](#table-of-contents)
  
## Author  
GAMSEAL
