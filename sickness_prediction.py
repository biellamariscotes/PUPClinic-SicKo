import numpy as np
from sklearn.naive_bayes import GaussianNB

# Sample dataset
# Each row represents a patient with symptoms: dizziness, fever, cough
# The last column represents the sickness (0 for healthy, 1 for sick)
dataset = np.array([
    ["No", "Yes", "No", 0],
    ["Yes", "Yes", "Yes", 1],
    ["No", "Yes", "Yes", 1],
    ["Yes", "No", "Yes", 0],
    ["Yes", "Yes", "Yes", 1],
    ["No", "No", "No", 0],
    ["Yes", "Yes", "Yes", 1],
    ["No", "No", "Yes", 0],
    ["Yes", "Yes", "Yes", 1],
    ["No", "Yes", "No", 0]
])

X = dataset[:, :-1]  # Features
y = dataset[:, -1]   # Target (sickness)

# Gaussian Naive Bayes classifier
model = GaussianNB()

# Training the model
model.fit(X, y)

# Sample treatment options
treatments = {
    0: "Get plenty of rest and fluids.",
    1: "Take prescribed medication and visit a doctor."
}

# Function to predict sickness and recommend treatment
def predict_sickness(symptoms):
    symptoms = np.array(symptoms).reshape(1, -1)
    prediction = model.predict(symptoms)[0]
    treatment = treatments[prediction]
    sickness = "Sick" if prediction == 1 else "Healthy"
    return sickness, treatment

# Getting symptoms from the user
print("Enter your symptoms:")
dizziness = input("Do you have dizziness? (Yes/No): ")
fever = input("Do you have fever? (Yes/No): ")
cough = input("Do you have cough? (Yes/No): ")

# Predicting sickness and recommending treatment
sickness, treatment = predict_sickness([dizziness, fever, cough])
print("Predicted Sickness: {}, Treatment: {}".format(sickness, treatment))
