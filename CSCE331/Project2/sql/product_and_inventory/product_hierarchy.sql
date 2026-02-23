SELECT
	p.ProductID,
	p.Name AS ProductName,
	ps.ProductSubcategoryID,
	ps.Name AS SubcategoryName,
	pc.ProductCategoryID,
	pc.Name AS CategoryName
FROM product AS p
JOIN productsubcategory AS ps 
	ON ps.ProductSubcategoryID = p.ProductSubcategoryID
JOIN productcategory AS pc 
	ON pc.ProductCategoryID = ps.ProductCategoryID
ORDER BY
	CategoryName,
	SubcategoryName,
	ProductName;

