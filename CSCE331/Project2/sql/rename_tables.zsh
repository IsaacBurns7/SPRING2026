#The Curseforge MySQL port of Adventureworks does not include schemas on their tables, so this perl command changes
    # <Schema>.<Table> to <Table>, where the list of valid schemas is defined in the perl command. 

perl -pi -e '
my @schemas = qw(Person Sales Purchasing HumanResources Production dbo);
foreach my $schema (@schemas) {
    s/\b$schema\.([A-Za-z0-9_]+)\b/$1/g;
}
' *.sql