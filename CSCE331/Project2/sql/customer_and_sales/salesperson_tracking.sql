
SELECT
	soh.SalesOrderID AS SalesOrderID,
	soh.OrderDate AS OrderDate,
	c.CustomerID AS CustomerID,
	c.AccountNumber AS AccountNumber,
	soh.SalesPersonID AS SalesPersonID,
	sp.TerritoryID AS SalesPersonTerritoryID,
	sp.CommissionPct AS CommissionPct,
	ROUND(soh.TotalDue, 2) AS TotalDue
FROM SalesOrderHeader soh
JOIN Customer c
	ON c.CustomerID = soh.CustomerID
LEFT JOIN SalesPerson sp
	ON sp.SalesPersonID = soh.SalesPersonID
ORDER BY soh.OrderDate DESC, soh.SalesOrderID;

