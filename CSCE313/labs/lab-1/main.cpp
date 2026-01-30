/******************************************************************************
 * File: banking_system.cpp
 * 
 * Object-Oriented Financial System
 * Implementation of a financial system
 * 
 * CSCE 313: Intro to Computer Systems
 * Spring 2026
 *****************************************************************************/

#include <iostream>
#include "types.h"

int main() {
    Bank* bank = new Bank();
    bool running = true;
    
    while (running) {
        std::cout << "\n=== Banking System Menu ===\n"
                  << "1. Login\n"
                  << "2. Deposit\n"
                  << "3. Withdraw\n"
                  << "4. View Balance\n"
                  << "5. Logout\n"
                  << "6. Exit\n"
                  << "Enter choice: ";
        
        int choice;
        std::cin >> choice;
        std::cin.ignore();

        try {
            switch (choice) {
                case 1: {
                    if (bank->current_account) {
                        std::cout << "Already logged in! Please logout first.\n";
                        break;
                    }
                    
                    int id;
                    std::cout << "Enter user ID: ";
                    std::cin >> id;
                    
                    if (bank->login(id)) {
                        std::cout << "Logged in as user " << id << std::endl;
                    } else {
                        std::cout << "Login failed\n";
                    }
                    break;
                }
                
                case 2: {
                    if (!bank->current_account) {
                        std::cout << "Please login first!\n";
                        break;
                    }
                    
                    double amount;
                    std::cout << "Enter amount to deposit: ";
                    std::cin >> amount;
                    
                    if (bank->deposit(amount)) {
                        std::cout << "Deposit successful\n";
                    } else {
                        std::cout << "Deposit failed\n";
                    }
                    break;
                }
                
                case 3: {
                    if (!bank->current_account) {
                        std::cout << "Please login first!\n";
                        break;
                    }
                    
                    double amount;
                    std::cout << "Enter amount to withdraw: ";
                    std::cin >> amount;
                    
                    if (bank->withdraw(amount)) {
                        std::cout << "Withdrawal successful\n";
                    } else {
                        std::cout << "Withdrawal failed\n";
                    }
                    break;
                }
                
                case 4: {
                    if (!bank->current_account) {
                        std::cout << "Please login first!\n";
                        break;
                    }
                    
                    std::cout << "Current balance: " << bank->current_account->balance << std::endl;
                    break;
                }
                
                case 5: {
                    if (!bank->current_account) {
                        std::cout << "Please login first!\n";
                        break;
                    }
                    
                    bank->logout();
                    break;
                }

                case 6: {
                    running = false;
                    break;
                }
                
                default:
                    std::cout << "Invalid choice. Please try again.\n";
            }
        }
        catch (const std::exception& e) {
            std::cerr << "Error: " << e.what() << "\n";
        }
    }
    
    delete bank;
    return 0;
}
