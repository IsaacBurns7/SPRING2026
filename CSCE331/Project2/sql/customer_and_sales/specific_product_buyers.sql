-- the specific product is Mountain Bike 
SELECT DISTINCT
	c.CustomerID AS CustomerID,
	c.AccountNumber AS AccountNumber,
	c.CustomerType AS CustomerType,
	p.ProductID AS ProductID,
	p.Name AS ProductName,
	soh.SalesOrderID AS SalesOrderID,
	soh.OrderDate AS OrderDate
FROM Customer c
JOIN SalesOrderHeader soh
	ON soh.CustomerID = c.CustomerID
JOIN SalesOrderDetail sod
	ON sod.SalesOrderID = soh.SalesOrderID
JOIN Product p
	ON p.ProductID = sod.ProductID
WHERE p.Name LIKE '%Mountain Bike%'
ORDER BY c.CustomerID, soh.OrderDate DESC, soh.SalesOrderID;

