#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Fri Sep  6 12:00:48 2019

@author: 
"""

"""
Assume:

W.shape == (K, d)

_x.shape == (d,)

_y.shape == (K,)

p.shape == (K,)

Then:
Expression	            Shape
p - _y	                (K,)
_x	                    (d,)
np.outer(p - _y, _x)	(K, d)
"""
import numpy as np
import sys

"""This script implements a two-class logistic regression model.
"""

class logistic_regression_multiclass(object):
	
    def __init__(self, learning_rate, max_iter, k):
        self.learning_rate = learning_rate
        self.max_iter = max_iter
        self.k = k 
        self.W = None  # Initialize to None so we can check if it exists
        
    def fit_miniBGD(self, X, labels, batch_size):
        """Train perceptron model on data (X,y) with mini-Batch GD.

        Args:
            X: An array of shape [n_samples, n_features].
            labels: An array of shape [n_samples,].  Only contains 0,..,k-1.
            batch_size: An integer.

        Returns:
            self: Returns an instance of self.

        Hint: the labels should be converted to one-hot vectors, for example: 1----> [0,1,0]; 2---->[0,0,1].
        """

		### YOUR CODE HERE
        n_samples, n_features = X.shape
        if self.W is None:
            self.W = np.zeros((self.k, n_features))
        for _ in range(self.max_iter):
            indicies = np.random.permutation(n_samples)
            X_shuffled = X[indicies]
            labels_shuffled = labels[indicies]
            # iterate from i to i + batch_size
            for i in range(0, n_samples, batch_size):
                gradient = np.zeros((self.k, n_features))
                end = min(i + batch_size, n_samples)  # non-inclusive
                for j in range(i, end):
                    correct_class = int(labels_shuffled[j])
                    one_hot = np.zeros(self.k)
                    one_hot[correct_class] = 1
                    gradient += self._gradient(X_shuffled[j], one_hot)
                gradient /= (end - i)  # average over actual batch size
                self.W -= self.learning_rate * gradient
		### END YOUR CODE
        return self 
    def _gradient(self, _x, _y):
        """Compute the gradient of cross-entropy with respect to self.W
        for one training sample (_x, _y). This function is used in fit_*.

        Args:
            _x: An array of shape [n_features,].
            _y: One_hot vector. 

        Returns:
            _g: An array of shape [n_features,]. The gradient of
                cross-entropy with respect to self.W.
        """
		### YOUR CODE HERE
        #for class K, the gradient is (p_k-y_n_k)x_n, or for all classes
            #p in R^K, y_n in R^K, nabla_wE_n = (p-y_n)x^T_n
        
        if self.W is None:
            raise ValueError("Weights must be initialized to calculate gradient")
        logits = self.W @ _x
        p = self.softmax(logits) #vector of probabilities across all classes
        # print("raw probability - observations:", (p-_y), "input: ", _x)
        gradient = np.outer((p - _y), _x)
        # print("computed gradient: ", gradient)
        return gradient 

		### END YOUR CODE
    
    def softmax(self, x):
        """Compute softmax values for each sets of scores in x."""
        ### You must implement softmax by youself, otherwise you will not get credits for this part.

		### YOUR CODE HERE
        # z = x - np.max(x) #stability trick?
        # exp_z = np.exp(x)
        exp = np.exp(x)
        sum = np.sum(np.exp(x))
       
        return exp/sum 
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


    def predict(self, X):
        """Predict class labels for samples in X.

        Args:
            X: An array of shape [n_samples, n_features].

        Returns:
            preds: An array of shape [n_samples,]. Only contains 0,..,k-1.
        """
		### YOUR CODE HERE
        # X @ W.T: (n_samples, n_features) @ (n_features, K) = (n_samples, K)
        # Each row i contains K scores for sample i
        logits = X @ self.W.T
        
        # argmax along axis=1 picks the class (column) with highest score per sample
        preds = np.argmax(logits, axis=1)
        return preds
		### END YOUR CODE


    def score(self, X, labels):
        """Returns the mean accuracy on the given test data and labels.

        Args:
            X: An array of shape [n_samples, n_features].
            labels: An array of shape [n_samples,]. Only contains 0,..,k-1.

        Returns:
            score: An float. Mean accuracy of self.predict(X) wrt. labels.
        """
		### YOUR CODE HERE
        preds = self.predict(X)
        mean = np.mean(preds == labels)
        return mean

		### END YOUR CODE
