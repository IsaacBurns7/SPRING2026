SELECT
	c.CustomerID AS CustomerID,
	c.AccountNumber AS AccountNumber,
	c.CustomerType AS CustomerType,
	soh.SalesOrderID AS SalesOrderID,
	soh.OrderDate AS OrderDate,
	ROUND(soh.TotalDue, 2) AS TotalDue
FROM Customer c
JOIN SalesOrderHeader soh
	ON soh.CustomerID = c.CustomerID
ORDER BY soh.OrderDate DESC, soh.SalesOrderID;

