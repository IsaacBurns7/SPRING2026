This folder contains a list of queries against the AdventureWorks database.

Each query has a corresponding txt file in the "out" subfolder. 

<Schema>.<TableName> -> <Schema>_<TableName> for schemas
perl -pi -e '
my @schemas = qw(Person Sales Purchasing HumanResources Production dbo);
foreach my $schema (@schemas) {
    s/\b$schema\.([A-Za-z0-9_]+)\b/$1/g;
}
' *.sql

table mappings (curseforge mysql wtf...)
person -> contact

1. Customer & Sales Queries

List all customers with their orders.

Tables: Customer, SalesOrderHeader

Purpose: Show customers and the orders they’ve made.

Count of orders per customer.

Tables: Customer, SalesOrderHeader

Purpose: Aggregate orders per customer (COUNT)

Total sales per customer.

Tables: Customer, SalesOrderHeader, SalesOrderDetail

Purpose: Calculate total sales amount (SUM) per customer.

Average order value per customer.

Tables: Customer, SalesOrderHeader, SalesOrderDetail

Purpose: Calculate average order total (AVG) per customer.

List customers who placed orders in the last month.

Tables: Customer, SalesOrderHeader

Purpose: Filter using WHERE with date condition.

List customers who purchased a specific product (e.g., “Mountain Bike”).

Tables: Customer, SalesOrderHeader, SalesOrderDetail, Product

Purpose: JOIN multiple tables and filter by LIKE product name.

Orders along with salesperson information.

Tables: SalesOrderHeader, SalesPerson, Customer

Purpose: Show which salesperson handled which order.

Top 10 customers by total sales.

Tables: Customer, SalesOrderHeader, SalesOrderDetail

Purpose: Aggregate, SUM, ORDER BY total sales, limit to 10.

Count of orders per territory.

Tables: SalesOrderHeader, SalesTerritory

Purpose: COUNT orders grouped by sales territory.

Average order amount per territory.

Tables: SalesOrderHeader, SalesTerritory, SalesOrderDetail

Purpose: AVG of order totals grouped by territory.

2. Product & Inventory Queries

List all products with their category and subcategory.

Tables: Product, ProductSubcategory, ProductCategory

Purpose: JOIN hierarchy tables.

Total stock quantity per product.

Tables: Product, ProductInventory

Purpose: Aggregate stock (SUM) per product.

Products with reorder level below threshold.

Tables: Product, ProductInventory

Purpose: Filter low-stock products.

Average product price per category.

Tables: Product, ProductSubcategory, ProductCategory

Purpose: Aggregate AVG price per category.

List products with no orders.

Tables: Product, SalesOrderDetail

Purpose: LEFT JOIN to find products never sold.

Top 5 most ordered products.

Tables: SalesOrderDetail, Product

Purpose: SUM of order quantity, ORDER BY descending.

Products supplied by multiple vendors.

Tables: ProductVendor, Product, Vendor

Purpose: Identify products with more than one supplier (COUNT).

List products with price > average price.

Tables: Product

Purpose: Filter with WHERE price > AVG(price) subquery.

Count of products per category.

Tables: Product, ProductSubcategory, ProductCategory

Purpose: GROUP BY category, COUNT products.

Products with names containing “Bike”.

Tables: Product

Purpose: Filter using LIKE.

3. Employee & HR Queries

List all employees and their managers.

Tables: Employee, Employee (self-join)

Purpose: Show reporting hierarchy.

Count of employees per department.

Tables: EmployeeDepartmentHistory, Department, Employee

Purpose: GROUP BY department, COUNT employees.

Average salary per department.

Tables: EmployeePayHistory, EmployeeDepartmentHistory, Department

Purpose: Aggregate AVG salary per department.

Employees hired in the last year.

Tables: Employee

Purpose: Filter by HireDate.

Employees with multiple job history records.

Tables: EmployeeDepartmentHistory, Employee

Purpose: Identify employees who changed departments (COUNT).

Employees with salaries above department average.

Tables: EmployeePayHistory, EmployeeDepartmentHistory, Department

Purpose: Compare salary to department average.

List employees with their current position and department.

Tables: Employee, EmployeeDepartmentHistory, Department, Shift

Purpose: Combine current assignment info.

Total employees per shift.

Tables: Shift, EmployeeDepartmentHistory

Purpose: GROUP BY shift.

Employees who report to a specific manager.

Tables: Employee

Purpose: Filter using manager ID.

Top 5 highest paid employees.

Tables: EmployeePayHistory, Employee

Purpose: ORDER BY salary DESC, LIMIT 5.

4. Sales & Order Analytics

Total sales per year.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: Aggregate SUM grouped by year.

Monthly sales trends per product category.

Tables: SalesOrderHeader, SalesOrderDetail, Product, ProductSubcategory, ProductCategory

Purpose: GROUP BY month & category, aggregate SUM.

Average discount per product.

Tables: SalesOrderDetail, Product

Purpose: Aggregate AVG(Discount) per product.

Orders with total > $1000.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: Filter high-value orders.

Top 10 selling products.

Tables: SalesOrderDetail, Product

Purpose: Aggregate SUM quantity sold, ORDER BY descending.

Total sales per region.

Tables: SalesOrderHeader, SalesTerritory

Purpose: GROUP BY territory, aggregate SUM.

Number of orders per month for the current year.

Tables: SalesOrderHeader

Purpose: GROUP BY month, COUNT orders.

Average shipping time per ship method.

Tables: SalesOrderHeader, ShipMethod

Purpose: Calculate AVG(ShipDate - OrderDate) per method.

Products never sold this year.

Tables: Product, SalesOrderDetail, SalesOrderHeader

Purpose: Identify unsold products.

Top 5 customers with highest average order.

Tables: Customer, SalesOrderHeader, SalesOrderDetail

Purpose: Aggregate AVG(order total), ORDER BY descending.

5. Purchasing & Vendor Queries

List all vendors and the products they supply.

Tables: Vendor, ProductVendor, Product

Purpose: Show which vendors supply which products (JOIN 3 tables).

Count of products per vendor.

Tables: Vendor, ProductVendor

Purpose: GROUP BY vendor, COUNT products.

Total cost of products per vendor.

Tables: ProductVendor, Product

Purpose: SUM(StandardPrice * Quantity) per vendor.

Vendors supplying a specific category of products.

Tables: Vendor, ProductVendor, Product, ProductSubcategory, ProductCategory

Purpose: Filter vendors by category using JOINs.

Vendors with more than 5 products.

Tables: Vendor, ProductVendor

Purpose: GROUP BY vendor, HAVING COUNT(products) > 5.

List vendors who haven’t supplied any product recently.

Tables: Vendor, ProductVendor

Purpose: LEFT JOIN to detect vendors with no recent supply.

Top 10 vendors by total product cost.

Tables: Vendor, ProductVendor, Product

Purpose: Aggregate SUM(cost), ORDER BY descending.

Products supplied by multiple vendors.

Tables: Product, ProductVendor, Vendor

Purpose: Identify products with COUNT(vendors) > 1.

Vendors located in a specific country/region.

Tables: Vendor

Purpose: Filter using WHERE CountryRegion LIKE 'United%'.

Average lead time per vendor.

Tables: ProductVendor, Vendor

Purpose: Aggregate AVG(LeadTimeDays).

6. Production & Manufacturing Queries

List all work orders with product details.

Tables: WorkOrder, Product

Purpose: Show work order with associated product.

Total quantity produced per product.

Tables: WorkOrder, Product

Purpose: GROUP BY product, SUM(quantity).

Average production time per product.

Tables: WorkOrder, Product

Purpose: AVG(FinishedDate - StartDate) per product.

Work orders by production start date.

Tables: WorkOrder

Purpose: ORDER BY StartDate.

Count of work orders per product category.

Tables: WorkOrder, Product, ProductSubcategory, ProductCategory

Purpose: GROUP BY category, COUNT(work orders).

List products currently in production.

Tables: WorkOrder, Product

Purpose: Filter where EndDate IS NULL.

Total labor cost per work order.

Tables: WorkOrder, WorkOrderRouting

Purpose: SUM(LaborHours * Rate) per work order.

Average cost per product produced.

Tables: WorkOrder, WorkOrderRouting, Product

Purpose: AVG(labor + material cost) per product.

Top 5 products with highest production quantity.

Tables: WorkOrder, Product

Purpose: SUM(quantity), ORDER BY descending, LIMIT 5.

Work orders delayed past due date.

Tables: WorkOrder

Purpose: Filter where EndDate > DueDate.

7. Shipping & Orders

Orders with shipping method and customer.

Tables: SalesOrderHeader, ShipMethod, Customer

Purpose: JOIN to show order, customer, and ship method.

Total shipping cost per customer.

Tables: SalesOrderHeader, Customer

Purpose: SUM(Freight) grouped by customer.

Average shipping cost per ship method.

Tables: SalesOrderHeader, ShipMethod

Purpose: AVG(Freight) grouped by method.

Orders shipped late.

Tables: SalesOrderHeader

Purpose: Filter where ShipDate > DueDate.

Orders per ship method.

Tables: SalesOrderHeader, ShipMethod

Purpose: GROUP BY ShipMethod, COUNT orders.

Top 5 customers by total shipping cost.

Tables: SalesOrderHeader, Customer

Purpose: SUM(Freight) ORDER BY descending.

Orders with multiple products.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: GROUP BY OrderID, HAVING COUNT(ProductID) > 1.

Orders shipped to a specific region.

Tables: SalesOrderHeader, SalesTerritory

Purpose: JOIN & filter by TerritoryID.

Total shipped quantity per product.

Tables: SalesOrderDetail, Product

Purpose: SUM(OrderQty) grouped by product.

Orders that were returned or cancelled.

Tables: SalesOrderHeader

Purpose: Filter using Status codes.

8. Advanced Analytics & Aggregates

Top 5 products by revenue per year.

Tables: SalesOrderHeader, SalesOrderDetail, Product

Purpose: SUM(LineTotal), GROUP BY Product & Year.

Total sales by customer region.

Tables: Customer, SalesOrderHeader, SalesTerritory

Purpose: GROUP BY Region, SUM(SalesAmount).

Average discount per salesperson.

Tables: SalesPerson, SalesOrderHeader, SalesOrderDetail

Purpose: AVG(Discount) per salesperson.

Total sales per month for current year.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: GROUP BY month, SUM(LineTotal).

Most popular product per territory.

Tables: SalesOrderHeader, SalesOrderDetail, Product, SalesTerritory

Purpose: COUNT(product sold), GROUP BY territory.

Customers with above average total spend.

Tables: Customer, SalesOrderHeader, SalesOrderDetail

Purpose: Compare customer total to AVG(total) across all customers.

Products contributing top 20% of revenue.

Tables: SalesOrderDetail, Product

Purpose: Use SUM(LineTotal), ORDER BY, LIMIT top 20%.

Monthly revenue growth rate.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: Calculate monthly SUM, compare to previous month.

Average order size per region.

Tables: SalesOrderHeader, SalesOrderDetail, SalesTerritory

Purpose: AVG(LineTotal) GROUP BY Territory.

Product revenue share per category.

Tables: Product, SalesOrderDetail, ProductSubcategory, ProductCategory

Purpose: SUM(LineTotal) per category / total revenue.

9. Promotions & Special Offers

List products on special promotion.

Tables: Product, SpecialOfferProduct, SpecialOffer

Purpose: Show active promotions.

Total sales per promotion.

Tables: SalesOrderHeader, SalesOrderDetail, SpecialOfferProduct, SpecialOffer

Purpose: SUM(LineTotal) GROUP BY promotion.

Customers who used a promotion.

Tables: Customer, SalesOrderHeader, SalesOrderDetail, SpecialOfferProduct

Purpose: Identify promotion users.

Average discount per promotion.

Tables: SalesOrderDetail, SpecialOfferProduct, SpecialOffer

Purpose: AVG(Discount) per promotion.

Promotions that didn’t result in sales.

Tables: SpecialOffer, SpecialOfferProduct, SalesOrderDetail

Purpose: LEFT JOIN to detect zero-sales promotions.

Products repeatedly on promotions.

Tables: SpecialOfferProduct, Product

Purpose: GROUP BY ProductID, HAVING COUNT(PromotionID) > 1.

Top 5 most effective promotions (by revenue).

Tables: SalesOrderDetail, SpecialOfferProduct, SpecialOffer

Purpose: SUM(LineTotal), ORDER BY descending.

Promotions per product category.

Tables: SpecialOfferProduct, Product, ProductSubcategory, ProductCategory

Purpose: GROUP BY category, COUNT(promotions).

Average number of products per promotion.

Tables: SpecialOfferProduct

Purpose: COUNT(ProductID) per promotion, AVG across promotions.

Customers who purchased promotion products above average.

Tables: Customer, SalesOrderHeader, SalesOrderDetail, SpecialOfferProduct

Purpose: SUM(LineTotal) per customer, compare to average.

10. Miscellaneous & Utility Queries

List all addresses per customer.

Tables: Customer, CustomerAddress, Address

Purpose: Show multiple addresses.

Count of customers per city.

Tables: Customer, CustomerAddress, Address

Purpose: GROUP BY city, COUNT customers.

Total orders per year per salesperson.

Tables: SalesOrderHeader, SalesPerson

Purpose: GROUP BY year & salesperson, COUNT orders.

List products with description containing “Carbon”.

Tables: Product

Purpose: Filter with LIKE '%Carbon%'.

Total stock value per category.

Tables: Product, ProductInventory, ProductSubcategory, ProductCategory

Purpose: SUM(UnitPrice * Quantity) GROUP BY category.

Employees per territory.

Tables: Employee, SalesTerritory

Purpose: GROUP BY territory, COUNT employees.

Products that are both sold and in inventory.

Tables: Product, SalesOrderDetail, ProductInventory

Purpose: INNER JOIN, filter products in both tables.

Customers who haven’t ordered in last 6 months.

Tables: Customer, SalesOrderHeader

Purpose: LEFT JOIN, filter by last order date.

Average order quantity per product.

Tables: SalesOrderDetail, Product

Purpose: AVG(OrderQty) GROUP BY product.

List orders along with total discounts applied.

Tables: SalesOrderHeader, SalesOrderDetail

Purpose: SUM(Discount) per order.