SELECT c.CustomerID as CustomerID, 
    c.AccountNumber as AccountNumber, 
    c.CustomerType as CustomerType,
    COUNT(soh.SalesOrderID) AS OrderCount
FROM Customer c 
JOIN SalesOrderHeader soh 
    ON c.CustomerID = soh.CustomerID
GROUP BY c.CustomerID, c.AccountNumber, c.CustomerType
ORDER BY c.CustomerID;