import torch
import torch.nn as nn
import torch.optim as optim
import numpy as np
import pickle, tqdm, os
import time
from pathlib import Path 

def load_data(data_dir):
    '''
    To load the Cifar-10 Dataset from files and reshape the 
    images arrays from shape [3072,] to shape [3, 32, 32].

    Please follow the instruction on how to load the data and 
    labels at https://www.cs.toronto.edu/~kriz/cifar.html

    Args:
        data_dir: String. The directory where data batches are 
            stored.

    Returns:
        x_train: An numpy array of shape [50000, 3, 32, 32].
            (dtype=np.uint8)
        y_train: An numpy array of shape [50000,].
            (dtype=np.int64)
        x_test: An numpy array of shape [10000, 3, 32, 32].
            (dtype=np.uint8)
        y_test: An numpy array of shape [10000,].
            (dtype=np.int64)
    '''
    ### YOUR CODE HERE
    def unpickle(file):
        import pickle
        with open(file, 'rb') as fo:
            data_dict = pickle.load(fo, encoding='bytes')
        return data_dict 
    training_batches = [
        "data_batch_1",
        "data_batch_2",
        "data_batch_3",
        "data_batch_4",
        "data_batch_5",
    ]
    test_batch = "test_batch"
    data_dir = Path(__file__).parent / "cifar-10-batches-py"
    training_dicts = [unpickle(data_dir / file) for file in training_batches] #has attributes data and labels
    # data is 10000x3072 numpy array of uint8s. Each row is 32x32 color image. First 1024 columns are red, next 1024 green, last 1024 blue. 
    # Image is row-major order, so first 32 entries of the array are the red channel values of the first row of the image 
    # labels is 10000 numbers in range 0-9. The number at index i indicates the label of the ith image in the data array. 
    x_train = np.concatenate([d[b'data'] for d in training_dicts], axis=0) #stack them along first dimension 
    y_train = np.concatenate([d[b'labels'] for d in training_dicts], axis=0)

    test_dict = unpickle(data_dir / test_batch)
    x_test = test_dict[b'data']
    # Force check
    if not isinstance(x_test, np.ndarray):
        raise TypeError(f"x_test must be a NumPy array, got {type(x_test)} instead")
    y_test = np.array(test_dict[b'labels'])

    print("BEFORE RESHAPING")
    print("x_train shape:", x_train.shape)
    print("y_train shape:", y_train.shape)
    print("x_test shape:", x_test.shape)
    print("y_test shape:", y_test.shape)

    # I THINK THIS IS CORRECT if error this could be the problem. 
    #axes: 0 1 2 3  (num_samples, channels, height, width)
    x_train = x_train.reshape(-1, 3, 32, 32)
    x_test = x_test.reshape(-1, 3, 32, 32)

    print("AFTER RESHAPING")
    print("x_train shape:", x_train.shape)
    print("y_train shape:", y_train.shape)
    print("x_test shape:", x_test.shape)
    print("y_test shape:", y_test.shape)

    ### END YOUR CODE
    return x_train, y_train, x_test, y_test


def preprocess(train_images, test_images, normalize=False):
    '''
    To preprocess the data by 
        (1).Rescaling the pixels from integers in [0,255) to 
            floats in [0,1), or 
        (2).Normalizing each image using its mean and variance. 

    Args:
        train_images: An numpy array of shape [50000, 3, 32, 32].
            (dtype=np.uint8)
        test_images: An numpy array of shape [10000, 3, 32, 32].
            (dtype=np.uint8)
        normalize: Boolean. To control to rescale or normalize 
            the images.

    Returns:
        train_images: An numpy array of shape [50000, 3, 32, 32].
            (dtype=np.float64)
        test_images: An numpy array of shape [10000, 3, 32, 32].
            (dtype=np.float64)
    '''
    ### YOUR CODE HERE
    
    if normalize:
        mean = train_images.mean(axis=(0, 2, 3), keepdims=True)  # (1, 3, 1, 1)
        std  = train_images.std(axis=(0, 2, 3), keepdims=True)   # (1, 3, 1, 1)
        train_images = (train_images - mean) / std
        test_images  = (test_images  - mean) / std  # reuse training stats
    else:
        #rescale
        train_images = train_images / 255.0
        test_images = test_images / 255.0 
        print("train and test min and max")
        print(train_images.min(), train_images.max())
        print(test_images.min(), test_images.max())
        ### END CODE HERE
    return train_images, test_images


class LeNet(nn.Module):
    '''
    Build the LeCun network according to the architecture in the homework part 4(c)

    You are free to use the listed APIs from torch.nn:
        torch.nn.Conv2d
        torch.nn.MaxPool2d
        torch.nn.Linear
        torch.nn.ReLU (or other activations)
        torch.nn.BatchNorm2d
        torch.nn.BatchNorm1d
        torch.nn.Dropout

    Refer to https://pytorch.org/docs/stable/nn.html
    for the instructions for those APIs
    '''
    # LeNet -> 32x32 Input -> Conv -> Pool -> Conv -> Pool -> FC -> FC -> Output 
    def __init__(self, n_classes=None):
        super(LeNet, self).__init__()
        '''
        Define each layers of the model in __init__() function
        '''
        if n_classes == None:
            print("Expected n_classes to not be none")
            return

        ### YOUR CODE HERE
        #C1 -> 5x5 kernel, 3 input channels, 6 output feature maps, no padding, 32x32 -> 28x28
        #S2 -> 2x2 max pool, halves spatial dims, 28x28 -> 14x14
        #C3 -> 6->16 feature maps, kernel 5x5, 14x14 -> 10x10
        #S4 -> 2x2 max pool, 10x10 -> 5x5
            #reuse self.pool
        #fc1 -> Fully connected, disguised as conv, but technically FC here, flatten 16x5x5 to 400, then linear layer 400 -> 120
        #fc2 -> 120 -> 84
        #fc3 aka Output -> 84 to n_classes(for cifar 10)
        #Optionally can add
            #BatchNorm2d after conv (before or after activation)
            #BatchNorm1d after linear layers
            #Add dropout before final output layer to reduce overfitting

        self.conv1 = nn.Conv2d(in_channels = 3, out_channels = 6, kernel_size=5)
        self.pool = nn.MaxPool2d(kernel_size=2, stride=2)
        self.conv2 = nn.Conv2d(in_channels=6, out_channels=16, kernel_size=5)
        self.fc1 = nn.Linear(16 * 5 * 5, 120) #120 channels, 5x5 spatial
        self.fc2 = nn.Linear(120, 84)
        self.fc3 = nn.Linear(84, n_classes)

        self.relu = nn.ReLU()
        self.bn1 = nn.BatchNorm2d(6)    # matches conv1 out_channels
        self.bn2 = nn.BatchNorm2d(16)   # matches conv2 out_channels
        self.bn3 = nn.BatchNorm1d(120)  # matches fc1 out_features
        self.bn4 = nn.BatchNorm1d(84)   # matches fc2 out_features
        self.dropout1 = nn.Dropout(p=0.25)
        self.dropout2 = nn.Dropout(p=0.5)
        ### END CODE HERE
    
    def forward(self, x):
        '''
        Run forward pass of the model defined in the above __init__() function
        Args:
            x: Tensor of shape [None, 3, 32, 32]
            for input images.

        Returns:
            logits: Tensor of shape [None, n_classes].
        '''

        #Order is typically Conv->ReLU->BN->Pool, but Conv->BN->ReLU is also common

       ### YOUR CODE HERE

        # --- Conv -> BN -> ReLU  with dropouts ---
        x = self.pool(self.relu(self.bn1(self.conv1(x))))
        x = self.pool(self.relu(self.bn2(self.conv2(x))))
        x = self.dropout1(x)
        x = x.view(x.size(0), -1)
        x = self.relu(self.bn3(self.fc1(x)))
        x = self.relu(self.bn4(self.fc2(x)))
        x = self.dropout2(x)
        x = self.fc3(x)

        # --- No BN or dropout ---
        # x = self.pool(self.relu(self.conv1(x)))
        # x = self.pool(self.relu(self.conv2(x)))
        # x = x.view(x.size(0), -1)
        # x = self.relu(self.fc1(x))
        # x = self.relu(self.fc2(x))
        # x = self.fc3(x)

        # --- shape logging ---
        # print(f"After conv1 + pool: {x.shape}")  # expect (N, 6, 14, 14)
        # print(f"After conv2 + pool: {x.shape}")  # expect (N, 16, 5, 5)
        # print(f"After flatten:      {x.shape}")  # expect (N, 400)
        # print(f"After fc1:          {x.shape}")  # expect (N, 120)
        # print(f"After fc2:          {x.shape}")  # expect (N, 84)
        # print(f"After fc3:          {x.shape}")  # expect (N, n_classes)


        ### END CODE HERE
        return x


class LeNet_Cifar10(nn.Module):
    def __init__(self, n_classes):

        super(LeNet_Cifar10, self).__init__()

        self.n_classes = n_classes
        self.model = LeNet(n_classes=n_classes)
        self.criterion = nn.CrossEntropyLoss()
        self.optimizer = optim.Adam(self.model.parameters(), lr=0.001)

    def train(self, x_train, y_train, x_valid, y_valid, batch_size, max_epoch):

        num_samples = x_train.shape[0]
        num_batches = int(num_samples / batch_size)

        num_valid_samples = x_valid.shape[0]
        num_valid_batches = (num_valid_samples - 1) // batch_size + 1

        x_train = torch.from_numpy(x_train).float()
        y_train = torch.from_numpy(y_train)
        x_valid = torch.from_numpy(x_valid).float()
        y_valid = torch.from_numpy(y_valid)

        print('---Run...')
        for epoch in range(1, max_epoch + 1):
            self.model.train()
            # To shuffle the data at the beginning of each epoch.
            shuffle_index = np.random.permutation(num_samples)
            curr_x_train = x_train[shuffle_index]
            curr_y_train = y_train[shuffle_index]

            # To start training at current epoch.
            loss_value = []
            miniters = 100
            qbar = tqdm.tqdm(range(num_batches), miniters=miniters, leave=True)
            for i in qbar:
                batch_start_time = time.time()

                start = batch_size * i
                end = batch_size * (i + 1)
                x_batch = curr_x_train[start:end]
                y_batch = curr_y_train[start:end]

                self.optimizer.zero_grad()
                outputs = self.model(x_batch)
                loss = self.criterion(outputs, y_batch)
                loss.backward()
                self.optimizer.step()
                if not i % miniters:
                    qbar.set_description(
                        'Epoch {:d} Loss {:.6f}'.format(
                            epoch, loss))

            # To start validation at the end of each epoch.
            self.model.eval()
            correct = 0
            total = 0
            print('Doing validation...', end=' ')
            with torch.no_grad():
                for i in range(num_valid_batches):

                    start = batch_size * i
                    end = min(batch_size * (i + 1), x_valid.shape[0])
                    x_valid_batch = x_valid[start:end]
                    y_valid_batch = y_valid[start:end]

                    outputs = self.model(x_valid_batch)
                    _, predicted = torch.max(outputs.data, 1)
                    total += y_valid_batch.shape[0]
                    correct += (predicted == y_valid_batch).sum().item()

            acc = correct / total
            print('Validation Acc {:.4f}'.format(acc))

    def test(self, X_test, y_test):
        self.model.eval()

        X_test = torch.from_numpy(X_test).float()
        y_test = torch.from_numpy(y_test)

        accs = 0
        for X, y in zip(X_test, y_test):

            outputs = self.model(X.unsqueeze(0))
            _, predicted = torch.max(outputs.data, 1)
            accs += (predicted == y).sum().item()

        accuracy = float(accs) / len(y_test)
        
        return accuracy