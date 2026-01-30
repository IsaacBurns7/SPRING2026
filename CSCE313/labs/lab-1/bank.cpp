#include "types.h"

Bank::Bank() {
    accounts = new Account[MAX_ACCOUNTS];
    accountCount = 0;
    current_account = nullptr;
}

Bank::~Bank() {
    delete[] accounts;
}

bool Bank::login(int id) {
    if (id < 0 || id >= MAX_ACCOUNTS) {
        return false;
    }
    
    // Task 2: Infinite recursion potential
    if (!accounts[id].active) {
        accounts[id] = Account(id);
        //if (id > 0) login(id + 1); 
    }
    
    current_account = &accounts[id];
    return true;
}

void Bank::logout() {
    // Task 5: Double free potential
    current_account = nullptr;
}

bool Bank::deposit(double amount) {
    if (!current_account || amount <= 0) {
        return false;
    }
    
    if(current_account->addTransaction(amount, "deposit")){
        current_account->balance += amount;
    }
    return true;
}

bool Bank::withdraw(double amount) {
    if (!current_account || amount <= 0 || amount > current_account->balance) {
        return false;
    }

    if(current_account->addTransaction(-amount, "withdraw")){
        current_account->balance -= amount;
   }
    return true;
}
