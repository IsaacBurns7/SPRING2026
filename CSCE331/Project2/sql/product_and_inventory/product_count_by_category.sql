SELECT
	COALESCE(pc.ProductCategoryID, -1) AS ProductCategoryID,
	COALESCE(pc.Name, 'Uncategorized') AS CategoryName,
	COUNT(p.ProductID) AS ProductCount
FROM product AS p
LEFT JOIN productsubcategory AS ps
	ON ps.ProductSubcategoryID = p.ProductSubcategoryID
LEFT JOIN productcategory AS pc
	ON pc.ProductCategoryID = ps.ProductCategoryID
GROUP BY
	COALESCE(pc.ProductCategoryID, -1),
	COALESCE(pc.Name, 'Uncategorized')
ORDER BY
	ProductCount DESC,
	CategoryName;

