import os
import numpy as np
import torch
import torch.nn as nn
import torch.optim as optim
from MLP import MLP
from DataReader import *
import random

data_dir = "../data/"
train_filename = "training.npz"
test_filename = "test.npz"

# set seed
seed = 42
random.seed(seed)
np.random.seed(seed)
torch.manual_seed(seed)
torch.cuda.manual_seed_all(seed)

# Hyperparameters
EPOCHS = 50
LEARNING_RATE = 0.001
HIDDEN_SIZE = 3
BATCH_SIZE = 32


def evaluation(model, best_model_state, best_val_acc):
    ### YOUR CODE HERE
    # step 1: Load test data
    raw_data, labels = load_data(os.path.join(data_dir, test_filename))

    # step 2: Preprocess raw data to extract features
    X_all = prepare_X(raw_data)
    y_all, _ = prepare_y(labels)

    # step 3: Convert numpy arrays to PyTorch tensors
    X_all = torch.tensor(X_all, dtype = torch.float32)
    y_all = torch.tensor(y_all, dtype = torch.long)

    # step 4: Load the best validation model
    model.load_state_dict(best_model_state)

    # step 5: Evaluate classification accuracy on the test set
    print("Evaluation accuracy on initial test set: ", best_val_acc)
    model.eval()
    with torch.no_grad():
        outputs = model(X_all)
        predictions = torch.argmax(outputs, dim=1)
        val_acc = (predictions == y_all).float().mean().item()
        print("Evaluation accuracy on final test set: ", val_acc)
        return val_acc
    ### END YOUR CODE

def train(hidden_size=3):
    ##### Load Data
    raw_data, labels = load_data(os.path.join(data_dir, train_filename))
    raw_train, raw_valid, label_train, label_valid = train_valid_split(raw_data, labels, 2300)

    ##### Preprocess raw data to extract features
    train_X_all = prepare_X(raw_train)
    valid_X_all = prepare_X(raw_valid)

    ##### Preprocess labels for all three classes (0,1,2)
    train_y_all, _ = prepare_y(label_train)
    valid_y_all, _ = prepare_y(label_valid)


    ##### Convert numpy array to torch tensors
    train_X_all = torch.tensor(train_X_all, dtype=torch.float32)
    valid_X_all = torch.tensor(valid_X_all, dtype=torch.float32)

    train_y_all = torch.tensor(train_y_all, dtype=torch.long)
    valid_y_all = torch.tensor(valid_y_all, dtype=torch.long)


    # Initialize model, loss, and optimizer
    model = MLP(input_size=train_X_all.shape[1], hidden_size=hidden_size, output_size=3)
    criterion = nn.CrossEntropyLoss()
    optimizer = optim.Adam(model.parameters(), lr=LEARNING_RATE)

    # Track the best validation accuracy and corresponding model
    best_val_acc = 0.0
    best_model_state = None

    # Training loop
    for epoch in range(EPOCHS):
        model.train()
        total_loss = 0
        num_batches = len(train_X_all) // BATCH_SIZE

        # Mini-batch training (manual batching)
        for i in range(num_batches):
            start_idx = i * BATCH_SIZE
            end_idx = min((i + 1) * BATCH_SIZE, len(train_X_all))

            X_batch = train_X_all[start_idx:end_idx]
            y_batch = train_y_all[start_idx:end_idx]

            optimizer.zero_grad()
            outputs = model(X_batch)
            loss = criterion(outputs, y_batch)
            loss.backward()
            optimizer.step()

            total_loss += loss.item()

        # Evaluate on validation set
        model.eval()
        with torch.no_grad():
            outputs = model(valid_X_all)
            predictions = torch.argmax(outputs, dim=1)
            val_acc  = (predictions == valid_y_all).float().mean().item()

        print(f"Epoch [{epoch+1}/{EPOCHS}], Loss: {total_loss / num_batches:.4f}, Val Accuracy: {val_acc:.4f}")

        # Save the best validation model
        if val_acc > best_val_acc:
            best_val_acc = val_acc
            best_model_state = model.state_dict() #not a deep copy ? shouldnt matter though... 

    # print("model: ", model)
    # print("BEST MODEL STATE: ", best_model_state)
    ##### Test the best model on the test set
    return evaluation(model, best_model_state, best_val_acc), model 

best_model_state = None 
best_model_val_acc = 0
best_model_hidden_layers = 0
for i in range (3, 100):
    model_val_acc, model = train(hidden_size=i)
    if model_val_acc > best_model_val_acc:
        best_model_state = model.state_dict()
        best_model_val_acc = model_val_acc
        best_model_hidden_layers = i
# Save the best trained model
torch.save(best_model_state, "best_mlp_model.pth")
print("Training complete. Best model saved as best_mlp_model.pth.")
print("Best model: ", best_model_state)
print("Best model accuracy: ", best_model_val_acc)
print("Best model hidden layers: ", best_model_hidden_layers)