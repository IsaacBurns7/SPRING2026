perl -pi -e '
my @schemas = qw(Person Sales Purchasing HumanResources Production dbo);
foreach my $schema (@schemas) {
    s/\b$schema\.([A-Za-z0-9_]+)\b/$1/g;
}
' *.sql