SELECT
	p.ProductID,
	p.Name AS ProductName,
	COALESCE(SUM(pi.Quantity), 0) AS TotalQuantity
FROM product AS p
LEFT JOIN productinventory AS pi
	ON pi.ProductID = p.ProductID
GROUP BY
	p.ProductID,
	p.Name
ORDER BY
	TotalQuantity DESC,
	ProductName;

