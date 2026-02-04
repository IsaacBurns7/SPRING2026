import os
import matplotlib.pyplot as plt
from LogisticRegression import logistic_regression
from LRM import logistic_regression_multiclass
from DataReader import *

data_dir = "../data/"
train_filename = "training.npz"
test_filename = "test.npz"
    
def visualize_features(X, y):
    '''This function is used to plot a 2-D scatter plot of training features. 

    Args:
        X: An array of shape [n_samples, 2].
        y: An array of shape [n_samples,]. Only contains 1 or -1.

    Returns:
        No return. Save the plot to 'train_features.*' and include it
        in submission.
    '''
    ### YOUR CODE HERE
    X_pos = X[y == 1] #pick rows where y == 1
    X_neg = X[y == -1] #pick rows where y == -1
    np.set_printoptions(threshold=np.inf, linewidth=200)
    # print(y)
    # print(X_pos, X_neg)
	#X_neg for features 0 and 1 
    plt.scatter(X_pos[:, 0], X_pos[:, 1], color='blue', marker = 'o', label = 'y=1')
    plt.scatter(X_neg[:, 0], X_neg[:, 1], color='red', marker = 'o', label = 'y=-1')
    plt.xlabel('Feature 1')
    plt.ylabel('Feature 2')
    plt.title('2D Scattering of Training Features')
    plt.legend()
    plt.savefig("train_features.png")

def visualize_result(X, y, W):
    '''This function is used to plot the sigmoid model after training. 

    Args:
        X: An array of shape [n_samples, 2].
        y: An array of shape [n_samples,]. Only contains 1 or -1.
        W: An array of shape [n_features,].

    Returns:
        No return. Save the plot to 'train_result_sigmoid.*' and include it
        in submission.
    '''
	### YOUR CODE HERE
    X_pos = X[y == 1]  # pick rows where y == 1
    X_neg = X[y == -1]  # pick rows where y == -1

    plt.figure(figsize=(8, 6))
    plt.scatter(X_pos[:, 0], X_pos[:, 1], color='blue', marker='o', label='y=1', alpha=0.7)
    plt.scatter(X_neg[:, 0], X_neg[:, 1], color='red', marker='o', label='y=-1', alpha=0.7)

    # Decision boundary: W[0] + W[1]*x1 + W[2]*x2 = 0
    # Solving for x2: x2 = -(W[0] + W[1]*x1) / W[2]
    if len(W) >= 3 and W[2] != 0:
        x1_min, x1_max = X[:, 0].min() - 0.5, X[:, 0].max() + 0.5
        x1_range = np.linspace(x1_min, x1_max, 100)
        x2_range = -(W[0] + W[1] * x1_range) / W[2]
        plt.plot(x1_range, x2_range, 'g-', linewidth=2, label='Decision Boundary')

    plt.xlabel('Feature 1')
    plt.ylabel('Feature 2')
    plt.title('Logistic Regression - Sigmoid Decision Boundary')
    plt.legend()
    plt.grid(True, alpha=0.3)
    plt.savefig('train_result_sigmoid.png', dpi=100, bbox_inches='tight')
    plt.close()
	### END YOUR CODE

def visualize_result_multi(X, y, W):
	'''This function is used to plot the softmax model after training. 

	Args:
		X: An array of shape [n_samples, 2].
		y: An array of shape [n_samples,]. Only contains 0,1,2.
		W: An array of shape [n_features, 3].
	
	Returns:
		No return. Save the plot to 'train_result_softmax.*' and include it
		in submission.
	'''
	### YOUR CODE HERE

	### END YOUR CODE

def test_gradient():
    """Test the _gradient function in logistic_regression class.
    
    Uses numerical gradient checking to verify the analytical gradient.
    """
    import numpy as np
    
    # Create a simple test case
    n_features = 3
    n_samples = 5
    
    # Initialize a logistic regression model
    model = logistic_regression(learning_rate=0.1, max_iter=100)
    model.W = np.array([0.5, -0.3, 0.8])  # Set some weights
    
    # Create test data
    X = np.array([
        [1.0, 2.0, 0.5],
        [1.0, -1.0, 1.5],
        [1.0, 0.5, -0.5],
        [1.0, 1.5, 2.0],
        [1.0, -0.5, 0.0]
    ])
    y = np.array([1, -1, 1, -1, 1])
    
    print("=" * 50)
    print("Testing _gradient function")
    print("=" * 50)
    print(f"Weights: {model.W}")
    print(f"X shape: {X.shape}")
    print(f"y shape: {y.shape}")
    print()
    
    # Test gradient for each sample
    for i in range(n_samples):
        _x = X[i]  # shape: (n_features,)
        _y = y[i]  # integer: 1 or -1
        
        # Compute analytical gradient
        analytical_grad = model._gradient(_x, _y)
        
        # Compute numerical gradient for verification
        epsilon = 1e-5
        numerical_grad = np.zeros(n_features)
        
        for j in range(n_features):
            # Compute loss at W + epsilon
            model.W[j] += epsilon
            signal_plus = _x @ model.W
            loss_plus = np.log(1 + np.exp(-_y * signal_plus))
            
            # Compute loss at W - epsilon
            model.W[j] -= 2 * epsilon
            signal_minus = _x @ model.W
            loss_minus = np.log(1 + np.exp(-_y * signal_minus))
            
            # Restore W
            model.W[j] += epsilon
            
            # Numerical gradient
            numerical_grad[j] = (loss_plus - loss_minus) / (2 * epsilon)
        
        print(f"Sample {i}: x={_x}, y={_y}")
        print(f"  Analytical gradient: {analytical_grad}")
        print(f"  Numerical gradient:  {numerical_grad}")
        print(f"  Difference: {np.linalg.norm(analytical_grad - numerical_grad)}")
        print()
    
    print("=" * 50)
    
    return True

#type = 'BGD', 'SGD', 'MBGD'
def test_fit(train_X, train_y, valid_X, valid_y, type, batch_size=50):
    """Test the fit_BGD function in logistic_regression class.
    
    Args:
        train_X: Training features
        train_y: Training labels (1 or -1)
        valid_X: Validation features  
        valid_y: Validation labels (1 or -1)
    """
    print("=" * 50)
    print(f"Testing fit_{type} function")
    print("=" * 50)
    
    # Test 1: Check that loss decreases after training
    print("\nTest 1: Loss should decrease after training")
    model = logistic_regression(learning_rate=0.10, max_iter=1000)
    model.W = np.zeros(train_X.shape[1])  # Initialize weights
    
    loss_before = model.loss_one_sample(train_X, train_y)
    if type == 'BGD':
        model.fit_BGD(train_X, train_y)
    elif type == 'SGD':
        model.fit_SGD(train_X, train_y)
    elif type == 'MBGD':
        model.fit_miniBGD(train_X, train_y, batch_size=batch_size)
    else:
        raise ValueError(f"Type is not valid: {type}")
    loss_after = model.loss_one_sample(train_X, train_y)
    
    print(f"  Loss before training: {loss_before:.4f}")
    print(f"  Loss after training:  {loss_after:.4f}")
    print(f"  Loss decreased: {loss_after < loss_before}")
    
    # Test 2: Check performance on validation set
    print("\nTest 2: Model should generalize to validation set")
    train_loss = model.loss_one_sample(train_X, train_y) / train_X.shape[0]
    valid_loss = model.loss_one_sample(valid_X, valid_y) / valid_X.shape[0]
    
    print(f"  Avg training loss:   {train_loss:.4f}")
    print(f"  Avg validation loss: {valid_loss:.4f}")
    print(f"  Weights: {model.get_params()}")
    
    print("=" * 50)
    return True


def main():
	# ------------Data Preprocessing------------
	# Read data for training.

    # Run gradient test
    # test_gradient()
    
    raw_data, labels = load_data(os.path.join(data_dir, train_filename))
    raw_train, raw_valid, label_train, label_valid = train_valid_split(raw_data, labels, 2300)

    ##### Preprocess raw data to extract features
    train_X_all = prepare_X(raw_train)
    valid_X_all = prepare_X(raw_valid)
    ##### Preprocess labels for all data to 0,1,2 and return the idx for data from '1' and '2' class.
    train_y_all, train_idx = prepare_y(label_train)
    valid_y_all, val_idx = prepare_y(label_valid)  

    # train_idx_array = train_idx[0] #hello guys its a fucking tuple!!
    # val_idx_array = val_idx[0]
    # train_X_all = np.array(train_X_all) #hello guys its a fucking python list not a np list
    # train_y_all = np.array(train_y_all)
    ####### For binary case, only use data from '1' and '2' 
    train_X = train_X_all[train_idx]
    train_y = train_y_all[train_idx]
    ####### Only use the first 1350 data examples for binary training. 
    train_X = train_X[0:1350]
    train_y = train_y[0:1350]
    valid_X = valid_X_all[val_idx]
    valid_y = valid_y_all[val_idx]
    ####### set lables to  1 and -1. Here convert label '2' to '-1' which means we treat data '1' as postitive class. 
	### YOUR CODE HERE
    train_y[train_y == 2] = -1
    valid_y[valid_y == 2] = -1

	### END YOUR CODE
    data_shape = train_y.shape[0] 

    # # Test fitting functions 
    # test_fit(train_X, train_y, valid_X, valid_y, type = 'BGD')
    # test_fit(train_X, train_y, valid_X, valid_y, type = 'SGD')
    # test_fit(train_X, train_y, valid_X, valid_y, type = 'MBGD', batch_size=15)

#    # Visualize training data.
    visualize_features(train_X[:, 1:3], train_y)


   # ------------Logistic Regression Sigmoid Case------------

   ##### Check BGD, SGD, miniBGD
    # logisticR_classifier = logistic_regression(learning_rate=0.5, max_iter=100)
    # print("training x shape: ", train_X_all.shape)
    # print("training y shape: ", train_y_all.shape)

    # print("\n" + "="*50)
    # print("BGD (Batch Gradient Descent)")
    # print("="*50)
    # logisticR_classifier.fit_BGD(train_X, train_y)
    # print(f"Weights: {logisticR_classifier.get_params()}")
    # print(f"Accuracy: {logisticR_classifier.score(valid_X, valid_y):.4f}")

    # print("\n" + "="*50)
    # print("Mini-BGD (full batch size)")
    # print("="*50)
    # logisticR_classifier.fit_miniBGD(train_X, train_y, data_shape)
    # print(f"Weights: {logisticR_classifier.get_params()}")
    # print(f"Accuracy: {logisticR_classifier.score(valid_X, valid_y):.4f}")

    # print("\n" + "="*50)
    # print("SGD (Stochastic Gradient Descent)")
    # print("="*50)
    # logisticR_classifier.fit_SGD(train_X, train_y)
    # print(f"Weights: {logisticR_classifier.get_params()}")
    # print(f"Accuracy: {logisticR_classifier.score(valid_X, valid_y):.4f}")

    # print("\n" + "="*50)
    # print("Mini-BGD (batch size = 1)")
    # print("="*50)
    # logisticR_classifier.fit_miniBGD(train_X, train_y, 1)
    # print(f"Weights: {logisticR_classifier.get_params()}")
    # print(f"Accuracy: {logisticR_classifier.score(valid_X, valid_y):.4f}")

    # print("Mini-BGD (batch size = 10)")
    # print("="*50)
    # logisticR_classifier.fit_miniBGD(train_X, train_y, 10)
    # print(f"Weights: {logisticR_classifier.get_params()}")
    # print(f"Accuracy: {logisticR_classifier.score(valid_X, valid_y):.4f}")


    # Explore different hyper-parameters.
    ### YOUR CODE HERE
    # learning_rates = [x for x in np.arange(0.01, 0.22, 0.02)]
    # max_iters = [x for x in range(50, 151, 100)]
    # best_logisticR_classifier = logistic_regression(learning_rate=0, max_iter=0)
    # best_score = 0
    # for rate in learning_rates:
    #     for iters in max_iters:
    #         classifier = logistic_regression(learning_rate=rate, max_iter=iters)
    #         classifier.fit_BGD(train_X, train_y)
    #         print("\n" + "=" * 50)
    #         score = classifier.score(valid_X, valid_y)
    #         print(f"Accuracy for learning rate: {rate} and max_iters: {iters} is {score}")
    #         print("=" * 50)
    #         if(score > best_score):
    #             best_logisticR_classifier = classifier
    #             best_score = score
    ### END YOUR CODE
	# Visualize the your 'best' model after training.
    # visualize_result(train_X[:, 1:3], train_y, best_logisticR_classifier.get_params())

    ### YOUR CODE HERE

    ### END YOUR CODE

    # Use the 'best' model above to do testing. Note that the test data should be loaded and processed in the same way as the training data.
    ### YOUR CODE HERE
    # print("\n" + "-" * 50)
    # print(f"BEST LEARNING MODEL WEIGHTS: ", best_logisticR_classifier.get_params())
    # print(f"BEST LEARNING MODEL SCORE: ", best_logisticR_classifier.score(valid_X, valid_y))
    # print("-" * 50)
    ### END YOUR CODE


    # ------------Logistic Regression Multiple-class case, let k= 3------------
    ###### Use all data from '0' '1' '2' for training
    train_X = train_X_all
    train_y = train_y_all
    valid_X = valid_X_all
    valid_y = valid_y_all

    #########  miniBGD for multiclass Logistic Regression
    logisticR_classifier_multiclass = logistic_regression_multiclass(learning_rate=0.5, max_iter=100,  k= 3)
    logisticR_classifier_multiclass.fit_miniBGD(train_X, train_y, 10)
    print("Logistic multiclass classifier weights: ", logisticR_classifier_multiclass.get_params())
    print("Logistic multiclass classifier score: ", logisticR_classifier_multiclass.score(train_X, train_y))

    # Explore different hyper-parameters.
    ### YOUR CODE HERE

    ### END YOUR CODE

	# Visualize the your 'best' model after training.
	# visualize_result_multi(train_X[:, 1:3], train_y, best_logistic_multi_R.get_params())


    # Use the 'best' model above to do testing.
    ### YOUR CODE HERE

    ### END YOUR CODE


    # ------------Connection between sigmoid and softmax------------
    ############ Now set k=2, only use data from '1' and '2' 

    #####  set labels to 0,1 for softmax classifer
    train_X = train_X_all[train_idx]
    train_y = train_y_all[train_idx]
    train_X = train_X[0:1350]
    train_y = train_y[0:1350]
    valid_X = valid_X_all[val_idx]
    valid_y = valid_y_all[val_idx] 
    train_y[np.where(train_y==2)] = 0
    valid_y[np.where(valid_y==2)] = 0  
    
    ###### First, fit softmax classifer until convergence, and evaluate 
    ##### Hint: we suggest to set the convergence condition as "np.linalg.norm(gradients*1./batch_size) < 0.0005" or max_iter=10000:
    ### YOUR CODE HERE

    ### END YOUR CODE






    train_X = train_X_all[train_idx]
    train_y = train_y_all[train_idx]
    train_X = train_X[0:1350]
    train_y = train_y[0:1350]
    valid_X = valid_X_all[val_idx]
    valid_y = valid_y_all[val_idx] 
    #####       set lables to -1 and 1 for sigmoid classifer
	### YOUR CODE HERE

	### END YOUR CODE 

    ###### Next, fit sigmoid classifer until convergence, and evaluate
    ##### Hint: we suggest to set the convergence condition as "np.linalg.norm(gradients*1./batch_size) < 0.0005" or max_iter=10000:
    ### YOUR CODE HERE

    ### END YOUR CODE


    ################Compare and report the observations/prediction accuracy


    # ------------End------------
    

if __name__ == '__main__':
	main()
    
    
