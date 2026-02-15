
SELECT
	c.CustomerID AS CustomerID,
	c.AccountNumber AS AccountNumber,
	c.CustomerType AS CustomerType,
	ROUND(SUM(sod.LineTotal), 2) AS TotalSales
FROM Customer c
JOIN SalesOrderHeader soh
	ON soh.CustomerID = c.CustomerID
JOIN SalesOrderDetail sod
	ON sod.SalesOrderID = soh.SalesOrderID
GROUP BY c.CustomerID, c.AccountNumber, c.CustomerType
ORDER BY TotalSales DESC
LIMIT 10;

