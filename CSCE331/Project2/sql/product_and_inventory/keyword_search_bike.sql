SELECT
	p.ProductID,
	p.Name AS ProductName,
	p.ListPrice
FROM product AS p
WHERE
	p.Name LIKE '%Bike%'
ORDER BY
	ProductName;

