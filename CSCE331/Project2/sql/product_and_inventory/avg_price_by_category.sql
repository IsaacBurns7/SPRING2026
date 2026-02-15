SELECT
	COALESCE(pc.ProductCategoryID, -1) AS ProductCategoryID,
	COALESCE(pc.Name, 'Uncategorized') AS CategoryName,
	AVG(p.ListPrice) AS AvgListPrice,
	COUNT(*) AS ProductCount
FROM product AS p
LEFT JOIN productsubcategory AS ps
	ON ps.ProductSubcategoryID = p.ProductSubcategoryID
LEFT JOIN productcategory AS pc
	ON pc.ProductCategoryID = ps.ProductCategoryID
GROUP BY
	COALESCE(pc.ProductCategoryID, -1),
	COALESCE(pc.Name, 'Uncategorized')
ORDER BY
	AvgListPrice DESC,
	CategoryName;

