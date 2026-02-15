SELECT
	p.ProductID,
	p.Name AS ProductName,
	p.ReorderPoint,
	COALESCE(SUM(pi.Quantity), 0) AS TotalQuantity
FROM product AS p
LEFT JOIN productinventory AS pi
	ON pi.ProductID = p.ProductID
GROUP BY
	p.ProductID,
	p.Name,
	p.ReorderPoint
HAVING
	TotalQuantity < p.ReorderPoint
ORDER BY
	TotalQuantity ASC,
	ProductName;

