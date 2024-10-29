=== Appy Pie Connect for WooCommerce ===

Contributors: hancock11
Donate link: #
Requires at least: 4.8
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Short Description: This plugin provides awesome functionality to your WordPress site.

#### WP REST API V3 #####
== Description == 
This plugin is created for handling WooCommerce related REST API So, to use this plugin you need to install and activate [WC REST API](url:#).

= Features of WP Form Connector =

* No configuration needed
* Easy to use and lightweight plugin
* Developer friendly & easy to customize
* WC Custom endpoints REST API

Support : [https://www.appypie.com/contact-us](https://www.appypie.com/contact-us)

== Installation ==

1. Download and extract plugin files to a wp-content/plugin directory
2. Activate the plugin through the WordPress admin interface
3. You are all done!


= API autorization =
* Pass header on authentication, username, password, mandatory,


#########  Customer Listing API ######### 

1. Create customer API,(http://example.com/wp-json/wc/v3/customer/create/)
2. Listing of customer API,(http://example.com/wp-json/wc/v3/customer/list/)
3. Pagination integrated API,(http://example.com/wp-json/wc/v3/customer/list?per_page=10paged=1)
4. Update customer API,(http://example.com/wp-json/wc/v3/customer/update)
5. Customer Filter API,(http://example.com/wp-json/wc/v3/customer/list?search="Search by username,email_id")
6. Search Customer by date and time,(http://example.com/wp-json/wc/v3/customer/list?date=2020-07-02 10:59:10)
7. View customer by customer id,(http://example.com/wp-json/wc/v3/customer/detail/2)

######### Data Format For Creating Customer #########

{
	"email":"john.doe@example.com",
    "first_name":"John",
    "last_name":"Doe",
    "username":"john",
    "password":"Feb@2020k",
    "billing":[
        {
        "first_name":"John",
        "last_name":"Doe",
        "company": "",
        "address_1":"969 Market",
        "address_2":"",
        "city":"San Francisco",
        "state":"CA",
        "postcode":"94103",
        "country":"US",
        "email":"john.doe@example.com",
        "phone":"(555) 555-5555"
      }
    ],
    "shipping": [
        {
        "first_name":"John",
        "last_name": "Doe",
        "company": "",
        "address_1":"969 Market",
        "address_2":"",
        "city":"San Francisco",
        "state":"CA",
        "postcode":"94103",
        "country":"US"
      }
    ]
}

######### Data Format For Updating Customer #########
{
	"email":"john.doe@example.com",
    "first_name":"John",
    "last_name":"Doe",
    "user_id":11,
    "billing":[
        {
        "first_name":"John",
        "last_name":"Doe",
        "company": "",
        "address_1":"969 Market",
        "address_2":"",
        "city":"San Francisco",
        "state":"CA",
        "postcode":"94103",
        "country":"US",
        "email":"john.doe@example.com",
        "phone":"(555) 555-5555"
      }
    ],
    "shipping": [
        {
        "first_name":"John",
        "last_name": "Doe",
        "company": "",
        "address_1":"969 Market",
        "address_2":"",
        "city":"San Francisco",
        "state":"CA",
        "postcode":"94103",
        "country":"US"
      }
    ]
}

######### Product Listing API ######### 

1. Create Product API,(http://example.com/wp-json/wc/v3/product/create/)
2. Product Listing API,(http://example.com/wp-json/wc/v3/product/list/)
3. Pagination Integrated API,(http://example.com/wp-json/wc/v3/product/list/?per_page=10&paged=1)
4. Product Filter By Date and Time API,(http://example.com/wp-json/wc/v3/product/list?date=2020-07-02 10:59:10)
5. View Product Detail By Product id,(http://example.com/wp-json/wc/v3/product/list/63)
6. Search Product By Product id,(http://example.com/wp-json/wc/v3/product/list/?product_id=63)
7. Delete Product API,(http://example.com/wp-json/wc/v3/product/delete/129)
8. Update Product API,(http://example.com/wp-json/wc/v3/product/update)
9. Product Filter By Title (http://example.com/wp-json/wc/v3/product/list/?search='Search by product title')

######### Product update Listing API ######### 

1. Create Product API,(http://example.com/wp-json/wc/v3/product/create/)
2. Product Listing API,(http://example.com/wp-json/wc/v3/product/updatelist/)
3. Pagination Integrated API,(http://example.com/wp-json/wc/v3/product/updatelist/?per_page=10&paged=1)
4. Product Filter By Date and Time API,(http://example.com/wp-json/wc/v3/product/updatelist?date=2020-07-02 10:59:10)
5. View Product Detail By Product id,(http://example.com/wp-json/wc/v3/product/updatelist/63)
6. Search Product By Product id,(http://example.com/wp-json/wc/v3/product/updatelist/?product_id=63)
7. Delete Product API,(http://example.com/wp-json/wc/v3/product/delete/129)
8. Update Product API,(http://example.com/wp-json/wc/v3/product/update)
9. Product Filter By Title (http://example.com/wp-json/wc/v3/product/updatelist/?search='Search by product title')

######### Data Format For Creating Product #########

{
  "name":"Product title",
  "type":"simple",
  "description":"Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.",
  "short_description":"Testing",
  "status":"publish",
  "_regular_price":"21.99",
  "_sale_price":"",
  "categories":[],
  "images":[
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_3_front.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_3_back.jpg"}
  ],
  "attributes":[
    {
    "name":"Color",
    "visible":"false",
    "variation":"true",
    "value":"Black|Green"
  },
  {
    "name":"Size",
    "visible": "false",
    "variation":"true",
    "value":"S|M"
       
  }
  ],
  "_manage_stock":"true",
  "_stock":"5"
}

######### Data Format For Updating Product #########

{
  "product_id":"90",
  "name":"Product title",
  "type":"simple",
  "description":"Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.",
  "short_description":"Testing",
  "status":"publish",
  "_regular_price":"21.99",
  "_sale_price":"",
  "categories":[],
  "images":[
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_3_front.jpg"},
    {"src":"http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_3_back.jpg"}
  ],
  "attributes":[
    {
    "name":"Color",
    "visible":"false",
    "variation":"true",
    "value":"Black|Green"
  },
  {
    "name":"Size",
    "visible": "false",
    "variation":"true",
    "value":"S|M"
       
  }
  ],
  "_manage_stock":"true",
  "_stock":"5"
}

######### Order Listing API #########

1. Create Order API,(http://example.com/wp-json/wc/v3/order/create/)
2. Order Listing API,(http://example.com/wp-json/wc/v3/order/list/)
3. Pagination Integrated API,(http://example.com/wp-json/wc/v3/order/list?per_page=12&paged=1)
4. Order Listing By Status API,(http://example.com/wp-json/wc/v3/order/list/?order_status=wc-on-hold)
5. Order Listing By Date and Time API,(http://example.com/wp-json/wc/v3/order/list/?date=2020-07-02 10:59:10)
6. View Order By Order Id API,(http://example.com/wp-json/wc/v3/order/list/40)

7. Search Order By Order No, Email,First_name,Last_name,Phone_no API,(http://localhost/woocommerceapi/wp-json/wc/v3/order/list/?search="Search by order_no, email,first_name,last_name,phone_no")
8. Update Order By Order_id API,(http://example.com/wp-json/wc/v3/order/update/)
9. Delete Order By Order ID API,(http://example.com/wp-json/wc/v3/order/delete/164)

######### Data Format For Creating Order #########

{
  "payment_method": "cod",
  "billing": {
    "first_name": "John",
    "last_name": "Doe",
    "address_1": "969 Market",
    "address_2": "",
    "city": "San Francisco",
    "state": "CA",
    "postcode": "94103",
    "country": "US",
    "email": "john.doe@example.com",
    "phone": "(555) 555-5555"
  },
  "shipping": {
    "first_name": "John",
    "last_name": "Doe",
    "address_1": "969 Market",
    "address_2": "",
    "city": "San Francisco",
    "state": "CA",
    "postcode": "94103",
    "country": "US"
  },
  "user_id":"",
  "order_comments":"",
  "line_items": [
    {
      "product_id": 92,
      "quantity": 1
    },
    {
      "product_id": 93,
      'variation_id' => 23,
      'quantity' => 1
    }
  ],
  "coupon_items": [
    {
      "code": "xvh73rae"
    }
  ]
}

######### Data Format For Updating Order #########
{
  "billing": {
    "first_name": "John",
    "last_name": "Doe",
    "address_1": "969 Market",
    "address_2": "",
    "city": "San Francisco",
    "state": "CA",
    "postcode": "94103",
    "country": "US",
    "email": "john.doe@example.com",
    "phone": "(555) 555-5555"
  },
  "shipping": {
    "first_name": "John",
    "last_name": "Doe",
    "address_1": "969 Market",
    "address_2": "",
    "city": "San Francisco",
    "state": "CA",
    "postcode": "94103",
    "country": "US"
  },
  "order_comments":"",
  "line_items": [
	{
	    "id": 130,
	    "quantity": 1,
	    "subtotal": "21.99",
	    "total": "18.66",
	    "price": "21.99"
	},
	{
	    "id": 129,
	    "quantity": 2,
	    "subtotal": "43.98",
	    "total": "37.31",
	    "price": "21.99"
	}
  ]
}

######### Coupons Listing API #########

1. Create Coupon API,(http://example.com/wp-json/wc/v3/coupons/create/)
2. Coupons Listing API,(http://example.com/wp-json/wc/v3/coupons/list)
3. Coupon Search Filter By Coupon Code API,(http://example.com/wp-json/wc/v3/coupons/list?code=vdtfyach)
4. Coupon Search By Date And Time API, (http://example.com/wp-json/wc/v3/coupons/list?date=2020-07-10 08:30:40)
5. Search Coupon By Id API,(http://example.com/wp-json/wc/v3/coupons/list?coupon_id=162) 
6. View Single Coupon Detail API, (http://example.com/wp-json/wc/v3/coupons/list/162)
7. Delete Coupons By ID API,(http://example.com/wp-json/wc/v3/coupons/delete/12) 
8. Update Coupons By Coupon ID (http://example.com/wp-json/wc/v3/coupons/update)

######### Data Format For Creating Coupon #########

{
    "code":"B4H8MFNJ",
    "type":"percent",
    "amount":"20",
    "individual_use":"true",
    "expiry_date":"2020-07-31",
    "exclude_sale_items": "true",
    "minimum_amount":"500.00",
    "maximum_amount":"1000.00",
    "description":"its for testing purpose"
}

######### Data Format For Updating Coupons #########
{   
    "coupon_id":"11",
    "code":"B4H8MFNJ",
    "type":"percent",
    "amount":"20",
    "individual_use":"true",
    "expiry_date":"2020-07-31",
    "exclude_sale_items": "true",
    "minimum_amount":"500.00",
    "maximum_amount":"1000.00",
    "description":"its for testing purpose"
}

######### Category Related API ###########
Product Category API

http://www.example.com/wp-json/wc/v3/product/categories

http://www.example.com/wp-json/wc/v3/product/delete-category/<id>

http://www.example.com/wp-json/wc/v3/product/create-category

"name":"cat name"
"parent":"0"
"description":"enter description "

http://www.example.com/wp-json/wc/v3/product/update-category/
"name":"cat name"
"id":"1"

######## Product variation list ##########
http://www.example.com/wp-json/wc/v3/variation/list?pid=311&sku=500

###### create product variation #######
http://www.example.com/wp-json/wc/v3/product/createvariation
product_id': '1196',
'attri_key': 'size',
'attri_value': 'Small2|Mediam2|Large2',
'attri_key1': 'color',
'attri_value1': 'Alow1|Read1|Green1',
'attri_key2': 'material',
'attri_value2': 'Cotton1|Cotton2|Cotton3',
'_stock': '15',
'price': '10',
'regular_price': '155',
'sale_price': '12',
'stock_status': 'instock',
'weight': '36',
'length': '80',
'width': '100',
'height': '50',
'stock': '10',
'description': 'description',
'sku': '1700',
'backorders': 'yes',
'low_stock_amount': '1000',
'download_limit': '200',
'download_expiry': '110',
'image_urls': "https://images.appypie.com/wp-content/uploads/2022/10/27105621/bannerSlide.png',
}
#### update product variation #######
http://www.example.com/wp-json/wc/v3/product/updatevariation

product_id': '1196',
'attri_key': 'size',
'attri_value': 'Small2|Mediam2|Large2',
'attri_key1': 'color',
'attri_value1': 'Alow1|Read1|Green1',
'attri_key2': 'material',
'attri_value2': 'Cotton1|Cotton2|Cotton3',
'_stock': '15',
'price': '10',
'regular_price': '155',
'sale_price': '12',
'stock_status': 'instock',
'weight': '36',
'length': '80',
'width': '100',
'height': '50',
'stock': '10',
'description': 'description',
'sku': '1700',
'backorders': 'yes',
'low_stock_amount': '1000',
'download_limit': '200',
'download_expiry': '110',
'image_urls': "https://images.appypie.com/wp-content/uploads/2022/10/27105621/bannerSlide.png',
'variation_id': 1100
}
