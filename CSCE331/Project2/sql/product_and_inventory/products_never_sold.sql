SELECT
	p.ProductID,
	p.Name AS ProductName
FROM product AS p
LEFT JOIN salesorderdetail AS sod
	ON sod.ProductID = p.ProductID
WHERE
	sod.SalesOrderDetailID IS NULL
ORDER BY
	ProductName;

