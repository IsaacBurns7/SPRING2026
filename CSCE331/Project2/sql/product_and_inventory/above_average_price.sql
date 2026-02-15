SELECT
	p.ProductID,
	p.Name AS ProductName,
	p.ListPrice
FROM product AS p
WHERE
	p.ListPrice > (
		SELECT AVG(p2.ListPrice)
		FROM product AS p2
	)
ORDER BY
	p.ListPrice DESC,
	ProductName;

