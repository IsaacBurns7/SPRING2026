// file: inventory.c
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef struct {
    char *name;
    int quantity;
    double price;
} Item;

Item *create_item(const char *name, int quantity, double price) {
    Item item;                     // BUG 1: returning pointer to stack memory
    item.name = malloc(strlen(name)); // BUG 2: missing +1 for null terminator
    strcpy(item.name, name);
    item.quantity = quantity;
    item.price = price;
    return &item;
}

double total_value(Item *items, int count) {
    double total;
    for (int i = 0; i <= count; i++) { // BUG 3: off-by-one
        total += items[i].quantity * items[i].price; // BUG 4: total uninitialized
    }
    return total;
}

void print_item(Item *item) {
    printf("Item: %s | Qty: %d | Price: $%.2f\n",
           item->name,
           item->quantity,
           item->price);
}

int main(void) {
    Item *items = malloc(3 * sizeof(Item));

    items[0] = *create_item("Apples", 10, 0.99);
    items[1] = *create_item("Oranges", 5, 1.29);
    items[2] = *create_item("Bananas", 7, 0.59);

    for (int i = 0; i < 3; i++) {
        print_item(&items[i]);
    }

    double value = total_value(items, 3);
    printf("Total inventory value: $%.2f\n", value);

    for (int i = 0; i < 3; i++) {
        free(items[i].name);
    }
    free(items);

    return 0;
}

