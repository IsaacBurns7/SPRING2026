
SELECT
	c.CustomerID AS CustomerID,
	c.AccountNumber AS AccountNumber,
	c.CustomerType AS CustomerType,
	ROUND(AVG(order_totals.OrderTotal), 2) AS AvgOrderValue
FROM Customer c
JOIN (
	SELECT
		soh.CustomerID AS CustomerID,
		soh.SalesOrderID AS SalesOrderID,
		SUM(sod.LineTotal) AS OrderTotal
	FROM SalesOrderHeader soh
	JOIN SalesOrderDetail sod
		ON sod.SalesOrderID = soh.SalesOrderID
	GROUP BY soh.CustomerID, soh.SalesOrderID
) AS order_totals
	ON order_totals.CustomerID = c.CustomerID
GROUP BY c.CustomerID, c.AccountNumber, c.CustomerType
ORDER BY AvgOrderValue DESC, c.CustomerID;

