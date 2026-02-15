This folder contains a list of queries against the AdventureWorks database.

Each query has a corresponding txt file in the "out" subfolder. 

<Schema>.<TableName> -> <Schema>_<TableName> for schemas
perl -pi -e '
my @schemas = qw(Person Sales Purchasing HumanResources Production dbo);
foreach my $schema (@schemas) {
    s/\b$schema\.([A-Za-z0-9_]+)\b/$1/g;
}
' *.sql



First 20 queries written, version 1, tables used:
- Customer
- SalesOrderHeader
- SalesOrderDetail
- Product
- SalesTerritory
- ProductSubcategory
- ProductCategory
- ProductVendor
- Vendor

#	Query Goal	Tables Involved	Purpose / Logic
1	List customers with orders	Customer, SalesOrderHeader	Show customers and their associated orders.
2	Count orders per customer	Customer, SalesOrderHeader	COUNT orders grouped by customer.
3	Total sales per customer	Customer, SalesOrderHeader, SalesOrderDetail	SUM of sales amount per customer.
4	Average order value	Customer, SalesOrderHeader, SalesOrderDetail	AVG order total per customer.
5	Recent orders	Customer, SalesOrderHeader	Order orders by recent. 
6	Specific product buyers	Customer, SalesOrderHeader, SalesOrderDetail, Product	JOIN hierarchy; filter by LIKE (e.g., 'Mountain Bike').
7	Salesperson tracking	SalesOrderHeader, SalesPerson, Customer	Match sales staff to specific orders.
8	Top 10 Customers	Customer, SalesOrderHeader, SalesOrderDetail	SUM sales, ORDER BY DESC, LIMIT 10.
9	Orders by territory	SalesOrderHeader, SalesTerritory	COUNT orders grouped by territory.
10	Avg amount by territory	SalesOrderHeader, SalesTerritory, SalesOrderDetail	AVG order total grouped by territory.

#	Query Goal	Tables Involved	Purpose / Logic
11	Product Hierarchy	Product, ProductSubcategory, ProductCategory	JOIN multiple levels of categories.
12	Total stock per product	Product, ProductInventory	SUM quantity per product ID.
13	Low stock alerts	Product, ProductInventory	Filter where stock is below reorder threshold.
14	Avg price by category	Product, ProductSubcategory, ProductCategory	AVG price grouped by category.
15	Products never sold	Product, SalesOrderDetail	LEFT JOIN where SalesOrderID IS NULL.
16	Top 5 ordered products	SalesOrderDetail, Product	SUM quantity, ORDER BY DESC, LIMIT 5.
17	Multi-vendor products	ProductVendor, Product, Vendor	COUNT vendors per product > 1.
18	Above average price	Product	WHERE price > (Subquery for AVG price).
19	Product count by category	Product, ProductSubcategory, ProductCategory	GROUP BY category, COUNT products.
20	Keyword search ("Bike")	Product	Filter using LIKE '%Bike%'.