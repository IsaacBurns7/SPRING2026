SELECT c.CustomerID as CustomerID, 
    c.AccountNumber as AccountNumber, 
    c.CustomerType as CustomerType,
    JSON_ARRAYAGG(
        JSON_OBJECT( 
            'SalesOrderID', soh.SalesOrderID,
            'OrderDate', soh.OrderDate,
            'TotalDue', soh.TotalDue
        )
    ) AS Orders
FROM Customer c 
JOIN SalesOrderHeader soh ON c.CustomerID = soh.CustomerID
GROUP BY c.CustomerID, c.AccountNumber, c.CustomerType;