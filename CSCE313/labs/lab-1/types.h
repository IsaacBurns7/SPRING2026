#ifndef TYPES_H
#define TYPES_H

#include <cstring>
#include <stdexcept>

const int MAX_ACCOUNTS = 100;
const int MAX_TRANSACTIONS = 100;

// Transaction structure
struct Transaction {
    int accountId;
    double amount;
    char* description;
};

// Account structure
class Account {
public:
    int id;
    double balance;
    bool active;
    Transaction* transactions;
    int transactionCount;
    
    Account() {
        id = -1;
        balance = 0;
        active = false;
        transactionCount = 0;
        transactions = new Transaction[MAX_TRANSACTIONS];
    }
    
    Account(int i): id(i) {
        active = true;
        balance = 0;
        transactionCount = 0;
	transactions = new Transaction[MAX_TRANSACTIONS];
    }

    Account& operator=(const Account& other) {
        if (this != &other) {
            delete[] transactions; 
            
            id = other.id;
            balance = other.balance;
            active = other.active;
            transactionCount = other.transactionCount;
        }
        return *this;
    }
    
    bool addTransaction(double amount, const char* desc) {
        transactions[transactionCount].accountId = id;
        transactions[transactionCount].amount = amount;
        
        transactions[transactionCount].description = new char[strlen(desc) + 1];
        strcpy(transactions[transactionCount].description, desc);
        
        transactionCount++;
        return true;
    }

    ~Account() {
        for(int i = 0;i<transactionCount;i++){
		delete[] transactions[i].description;
	}
    }
};

// Bank class declaration
class Bank {
private:
    Account* accounts;
    int accountCount;

public:
    Account* current_account;

    Bank();
    ~Bank();
    bool login(int id);
    void logout();
    bool deposit(double amount);
    bool withdraw(double amount);
};

#endif // TYPES_H
