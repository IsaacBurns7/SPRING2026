
SELECT
	st.TerritoryID AS TerritoryID,
	st.Name AS TerritoryName,
	st.`Group` AS TerritoryGroup,
	ROUND(AVG(order_totals.OrderTotal), 2) AS AvgOrderAmount
FROM SalesTerritory st
JOIN (
	SELECT
		soh.TerritoryID AS TerritoryID,
		soh.SalesOrderID AS SalesOrderID,
		SUM(sod.LineTotal) AS OrderTotal
	FROM SalesOrderHeader soh
	JOIN SalesOrderDetail sod
		ON sod.SalesOrderID = soh.SalesOrderID
	WHERE soh.TerritoryID IS NOT NULL
	GROUP BY soh.TerritoryID, soh.SalesOrderID
) AS order_totals
	ON order_totals.TerritoryID = st.TerritoryID
GROUP BY st.TerritoryID, st.Name, st.`Group`
ORDER BY AvgOrderAmount DESC, st.TerritoryID;

