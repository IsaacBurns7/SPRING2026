SELECT
	st.TerritoryID AS TerritoryID,
	st.Name AS TerritoryName,
	st.Group AS TerritoryGroup,
	COUNT(soh.SalesOrderID) AS OrderCount
FROM SalesTerritory st
LEFT JOIN SalesOrderHeader soh
	ON soh.TerritoryID = st.TerritoryID
GROUP BY st.TerritoryID, st.Name, st.Group
ORDER BY OrderCount DESC, st.TerritoryID;

