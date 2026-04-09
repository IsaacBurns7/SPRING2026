import torch
import torch.nn as nn
import torch.optim as optim
import torch.nn.functional as F

class MLP(nn.Module):
    #linear -> relu -> linear 
    def __init__(self, input_size=3, hidden_size=3, output_size=3):
        super(MLP, self).__init__()
        ### YOUR CODE HERE
        self.input_layer = nn.Linear(input_size, hidden_size)
        self.hidden_layer = nn.Linear(hidden_size, output_size)
        ### END YOUR CODE

    def forward(self, x):
        ### YOUR CODE HERE
        x = self.input_layer(x)
        x = F.relu(x)
        x = self.hidden_layer(x)
        ### END YOUR CODE
        return x
