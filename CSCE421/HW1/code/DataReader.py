import numpy as np

"""This script implements the functions for reading data.
"""

def load_data(filename):
    """Load a given txt file.

    Args:
        filename: A string.

    Returns:
        raw_data: An array of shape [n_samples, 256].
        labels : An array of shape [n_samples,].
        
    """
    data= np.load(filename)
    x= data['x']
    y= data['y']
    return x, y

# a) train_valid_split splits the data into test and train at a split index
# we need this to test if our model is generalizable, or whether it just memorized
# the dataset. 
# b) Yes, the first iteration may not have caused enough movement in the 
# direction of the gradient, and thereby, the loss function can be minimzied further.

def train_valid_split(raw_data, labels, split_index):
	"""Split the original training data into a new training dataset
	and a validation dataset.
	n_samples = n_train_samples + n_valid_samples

	Args:
		raw_data: An array of shape [n_samples, 256].
        labels : An array of shape [n_samples,].
		split_index: An integer.

	"""
	return raw_data[:split_index], raw_data[split_index:], labels[:split_index], labels[split_index:]

def prepare_X(raw_X):
    """Extract features from raw_X as required.

    Args:
        raw_X: An array of shape [n_samples, 256].

    Returns:
        X: An array of shape [n_samples, n_features].
    """
    raw_image = raw_X.reshape((-1, 16, 16))
    n_samples = raw_image.shape[0]
    X_all = np.zeros((n_samples, 3))
    for i in range(n_samples):
        img = raw_image[i]
        # Feature 1: Measure of Symmetry
        F_symmetry = -np.sum(np.abs(img - np.flip(img, 1))) / img.size

        # Feature 2: Measure of Intensity
        ### YOUR CODE HERE
        F_intensity = np.sum(img) / img.size	
        ### END YOUR CODE

        # Feature 3: Bias Term. Always 1.
        # d) We need this because it matches with the bias in the weights vector. 
        # If it did not exist, that term in the weight vector would do nothing
        # and our model would always have to pass through the origin, and could not shift.
        ### YOUR CODE HERE
        F_bias = 1 
        ### END YOUR CODE

        # Stack features together in the following order.
        # [Feature 3, Feature 1, Feature 2]
        ### YOUR CODE HERE
        X_all[i] = [F_bias, F_symmetry, F_intensity]
	
	### END YOUR CODE
    return X_all

def prepare_y(raw_y):
    """
    Args:
        raw_y: An array of shape [n_samples,].
        
    Returns:
        y: An array of shape [n_samples,].
        idx:return idx for data label 1 and 2.
    """
    #ok but this literally does nothing
    y = raw_y
    idx = np.where((raw_y==1) | (raw_y==2))
    y[np.where(raw_y==0)] = 0
    y[np.where(raw_y==1)] = 1
    y[np.where(raw_y==2)] = 2

    return y, idx




