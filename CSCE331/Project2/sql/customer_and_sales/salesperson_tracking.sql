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
JOIN SalesPerson sp 
-- a LOT of sales order headers DO NOT have a specific salesperson attached
    -- so a lot of salesorderheaders WILL NOT appear. left join if u want those unattached sales 
	ON sp.SalesPersonID = soh.SalesPersonID
ORDER BY soh.OrderDate DESC, soh.SalesOrderID;

