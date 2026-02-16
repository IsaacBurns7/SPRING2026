#!/bin/bash

# MySQL connection info
USER="root"
PASSWORD="pandasw1ths0y"
DATABASE="adventureworks"

# NOTE: PLEASE CREATE OUT FOLDER BEFORE RUNNING!!
# Folders
QUERY_DIR="."       # Root folder containing your 10 category subfolders
OUT_DIR="../out"     # Folder to save outputs. 

# Ensure the base output directory exists
mkdir -p "$OUT_DIR"

# Use 'find' to locate all .sql files recursively
find "$QUERY_DIR" -type f -name "*.sql" | while read -r SQL_FILE; do
    
    # 1. Get the relative path (e.g., "Customer_Sales/query1.sql")
    # This removes the leading "./" if present
    REL_PATH="${SQL_FILE#./}"
    
    # 2. Determine the directory part of the relative path
    DIR_PATH=$(dirname "$REL_PATH")
    
    # 3. Get the filename without extension for the output
    BASENAME=$(basename "$SQL_FILE" .sql)
    
    # 4. Create the mirrored directory inside OUT_DIR
    TARGET_OUT_DIR="$OUT_DIR/$DIR_PATH"
    mkdir -p "$TARGET_OUT_DIR"
    
    # 5. Set final output file path
    OUT_FILE="$TARGET_OUT_DIR/${BASENAME}.txt"
    
    echo "Running: $SQL_FILE"
    echo "Saving to: $OUT_FILE"
    
    # Execute the SQL file
    # If you need to use the password, uncomment the next line and comment out the simple one
    # mysql -u "$USER" -p"$PASSWORD" "$DATABASE" < "$SQL_FILE" > "$OUT_FILE"
    mysql "$DATABASE" < "$SQL_FILE" > "$OUT_FILE"
    
done

echo "---"
echo "All queries executed. Structure mirrored in $OUT_DIR/"