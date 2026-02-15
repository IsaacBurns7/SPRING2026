SELECT
	p.ProductID,
	p.Name AS ProductName,
	SUM(sod.OrderQty) AS TotalOrderedQuantity
FROM salesorderdetail AS sod
INNER JOIN product AS p
	ON p.ProductID = sod.ProductID
GROUP BY
	p.ProductID,
	p.Name
ORDER BY
	TotalOrderedQuantity DESC,
	ProductName
LIMIT 5;

