SELECT
	p.ProductID,
	p.Name AS ProductName,
	COUNT(DISTINCT pv.VendorID) AS VendorCount
FROM productvendor AS pv
INNER JOIN product AS p
	ON p.ProductID = pv.ProductID
INNER JOIN vendor AS v
	ON v.VendorID = pv.VendorID
GROUP BY
	p.ProductID,
	p.Name
HAVING
	VendorCount > 1
ORDER BY
	VendorCount DESC,
	ProductName;

