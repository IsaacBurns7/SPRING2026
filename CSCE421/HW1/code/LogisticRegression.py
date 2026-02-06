import numpy as np
import sys

"""This script implements a two-class logistic regression model.

Cross-entropy loss
a) 
\[
E_{\text{in}}(\mathbf{w}) =
\ln \left( 1 + e^{-y_n \mathbf{w}^T \mathbf{x}_n} \right)
\]

b) 
Given $E_{in}$ for logistic regression, we calculate the gradient as  
\[
\nabla_wE_{in}(w) = \nabla_w\ln(1+e^{-y_nw^Tx_n})
\]
\[
z=-y_nw^Tx_n, E_{in}=\ln(1+e^z)
\]
\[
\frac{d}{dz}ln(1+e^z)=\frac{e^z}{1+e^z}
\]
\[
\nabla_wz=-y_nx_n, \nabla_wE_n=\frac{e^z}{1+e^z} (-y_nx_n)
\]
\[
\nabla_wE_{in}=-\frac{y_nx_ne^{-y_nw^Tx_n}}{1+e^{-y_nw^Tx_n}}
\]
(multiply both numerator and denominator by $e^{y_nw^Tx_n}$
\[
\nabla_wE_{in}=-\frac{y_nx_n}{1+e^{y_nw^Tx_n}}
\]
\[
\mathbf{g}_t
= -\frac{y_n \mathbf{x}_n}{1 + e^{y_n \mathbf{w}^\top(t)\mathbf{x}_n}}
\]

c) theta(z) >= 0.5  <=> z >= 0. Since the sigmoid is strictly increasing, the given ruleset
    predict = {+1 if theta(wTx) >= 0.5, -1 if theta(wTx) < 0.5}
    is the exact same mathematically as 
    predict = {+1 is wTx >= 0, -1 is wTx < 0}. 
    Therefore, to make class-level predictions, using the sigmoid is inefficient and unneeded. 
    However, if we want probabilities, we should still use the sigmoid-level predictions, 
    or if our threshold wasn't necessarily at 0.5, 
    or during training, because we need a usable gradient and a smooth, convex loss (for a global minimum).
d) The decision boundary is still linear, so we can do effectively the same thing as we did in c.
    theta(z) >= 0.9 <=> z >= ln(0.9 / (1 - 0.9)) = ln(9) = ~2.197 
    predict = {+1 if theta(wTx) >= 0.9, -1 if theta(wTx) < 0.9}
    is the exact same mathematically as 
    predict = {+1 is wTx >= 2.197, -1 is wTx < 2.197}. 
    <i.e. the hyperplane is shifted, but not curved>
e) That the sigmoid is strictly increasing. 
    Logistic regression applies a monotonic nonlinearity to a linear function of the input.    
    theta(wTx) >= c is the same as
    wTx >= theta^-1(c)
"""

class logistic_regression(object):
	
    def __init__(self, learning_rate, max_iter):
        self.learning_rate = learning_rate
        self.max_iter = max_iter
        self.W = None

    def fit_BGD(self, X, y):
        """Train perceptron model on data (X,y) with Batch Gradient Descent.

        Args:
            X: An array of shape [n_samples, n_features].
            y: An array of shape [n_samples,]. Only contains 1 or -1.

        Returns:
            self: Returns an instance of self.
        """
        n_samples, n_features = X.shape

		### YOUR CODE HERE
        self.W = np.zeros(n_features)
        
        for _ in range(self.max_iter):
            gradient = np.zeros(n_features)
            for i in range(n_samples):
                gradient += self._gradient(X[i], y[i])
            gradient /= n_samples #average it out
            # print(gradient)
            self.W -= gradient * self.learning_rate #subtract to descend (gradient normally is steepest ascent)
        ### END YOUR CODE
        return self

    def fit_miniBGD(self, X, y, batch_size):
        """Train perceptron model on data (X,y) with mini-Batch Gradient Descent.

        Args:
            X: An array of shape [n_samples, n_features].
            y: An array of shape [n_samples,]. Only contains 1 or -1.
            batch_size: An integer.

        Returns:
            self: Returns an instance of self.
        """
		### YOUR CODE HERE
        if self.W is None:
            self.W = np.zeros(n_features)
        n_samples, n_features = X.shape
        for _ in range(self.max_iter):
            indicies = np.random.permutation(n_samples)
            X_shuffled = X[indicies]
            y_shuffled = y[indicies]
            # iterate from i to i + batch_size
            for i in range(0, n_samples, batch_size):
                gradient = np.zeros(n_features)
                end = min(i + batch_size, n_samples)  # non-inclusive
                for j in range(i, end):
                    gradient += self._gradient(X_shuffled[j], y_shuffled[j])
                gradient /= (end - i)  # average over actual batch size
                self.W -= self.learning_rate * gradient
		### END YOUR CODE
        return self

    def fit_SGD(self, X, y):
        """Train perceptron model on data (X,y) with Stochastic Gradient Descent.

        Args:
            X: An array of shape [n_samples, n_features].
            y: An array of shape [n_samples,]. Only contains 1 or -1.

        Returns:
            self: Returns an instance of self.
        """
        n_samples, n_features = X.shape
		### YOUR CODE HERE
        self.W = np.zeros(n_features)

        for _ in range(self.max_iter):
            # Shuffle X and y together to keep them aligned
            indices = np.random.permutation(n_samples)
            X_shuffled = X[indices]
            y_shuffled = y[indices]
            
            for i in range(n_samples):
                gradient = self._gradient(X_shuffled[i], y_shuffled[i])
                self.W -= gradient * self.learning_rate
        ### END YOUR CODE
        return self

    def _gradient(self, _x, _y):
        """Compute the gradient of cross-entropy with respect to self.W
        for one training sample (_x, _y). This function is used in fit_*.

        Args:
            _x: An array of shape [n_features,].
            _y: An integer. 1 or -1.

        Returns:
            _g: An array of shape [n_features,]. The gradient of
                cross-entropy with respect to self.W.
        """
		### YOUR CODE HERE

        #gt = -1/N * for all([y_nx_n] / 1 + e^{y_n*signal_n})
            #since only training example, ignore 1/n and for all, keep negative though
        if self.W is None:
            print("Cant calculate loss without weights")
            sys.exit(-1)
        
        # signal = _x @ self.W
        # grad = -(_y * _x) / (1 + np.exp(_y * signal))
        # return grad
        signal = _x @ self.W #x_n * y_n
        signal_times_y = _y * signal 
        x_times_y = _y * _x 
        loss = - x_times_y / (1 + np.exp(signal_times_y))
        return loss
		### END YOUR CODE

    def get_params(self):
        """Get parameters for this perceptron model.

        Returns:
            W: An array of shape [n_features,].
        """
        if self.W is None:
            print("Run fit first!")
            sys.exit(-1)
        return self.W

    def predict_proba(self, X):
        """Predict class probabilities for samples in X.

        Args:
            X: An array of shape [n_samples, n_features].

        Returns:
            preds_proba: An array of shape [n_samples, 2].
                Only contains floats between [0,1].
        """
		### YOUR CODE HERE
        if self.W is None:
            raise ValueError("cannot predict probability without weights")
        signal = X @ self.W 
        # sigmoid: P(y=1|x) = 1 / (1 + exp(-signal))
        prob_pos = 1 / (1 + np.exp(-signal))
        prob_neg = 1 - prob_pos
        preds_proba = np.column_stack((prob_neg, prob_pos))
        return preds_proba
		### END YOUR CODE


    def predict(self, X):
        """Predict class labels for samples in X.

        Args:
            X: An array of shape [n_samples, n_features].

        Returns:
            preds: An array of shape [n_samples,]. Only contains 1 or -1.
        """
		### YOUR CODE HERE
        if self.W is None:
            raise ValueError("cannot predict without weights")
        signal = X @ self.W
        preds = np.where(signal >= 0, 1, -1)
        return preds
		### END YOUR CODE

    def score(self, X, y):
        """Returns the mean accuracy on the given test data and labels.

        Args:
            X: An array of shape [n_samples, n_features].
            y: An array of shape [n_samples,]. Only contains 1 or -1.

        Returns:
            score: An float. Mean accuracy of self.predict(X) wrt. y.
        """
		### YOUR CODE HERE
        preds = self.predict(X)
        accuracy = np.mean(preds == y)
        return accuracy
		### END YOUR CODE
    
    def loss_one_sample(self, X, y):
        # z = w^Tx + b
        # y^ = 1 / (1 + e^-z)
        # loss(y, y^) = -[ylog(y^) + (1-y)log(1-y^)]
            # or E_in(w) = sum over all examples(ln(1+e^(-y_n * w^Tx_n)))
            # (theyre the same)
        if self.W is None:
            print("Cant calculate loss without weights")
            sys.exit(-1)
        if X.shape[0] != y.shape[0]:
            raise ValueError(f"Mismatch: x has {X.shape[0]} rows, and y has {y.shape[0]} rows")
        # n = X.shape[0]
        # signal = self.predict_proba(X)
        signal = X @ self.W
        signal_times_y = y * signal 
        loss_per_example = np.log(1 + np.exp(-signal_times_y))
        E_in = np.sum(loss_per_example)
        return E_in 
    def assign_weights(self, weights):
        self.W = weights
        return self

