import numpy as np
from sklearn.naive_bayes import GaussianNB
import sys

# Sample dataset
# Each row represents a patient with symptoms: dizziness, fever, cough, pale lips, pale skin
# The last column represents the sickness (0 for healthy, 1 for sick)
dataset = np.array([
    ["No", "Yes", "No", "Yes", "Yes", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["Yes", "Yes", "Yes", "No", "No", "Healthy"],  # Healthy
    ["No", "Yes", "Yes", "Yes", "Yes", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["Yes", "No", "Yes", "No", "Yes", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["Yes", "Yes", "Yes", "Yes", "No", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["No", "No", "No", "No", "No", "Healthy"],  # Healthy
    ["Yes", "Yes", "Yes", "No", "Yes", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["No", "No", "Yes", "Yes", "No", "Healthy"],  # Healthy
    ["Yes", "Yes", "Yes", "Yes", "Yes", "Low Blood Pressure or Anemia"],  # Sick: low blood pressure or anemia
    ["No", "Yes", "No", "No", "Yes", "Low Blood Pressure or Anemia"]  # Sick: low blood pressure or anemia
])

X = dataset[:, :-1]  # Features
y = dataset[:, -1]   # Target (sickness)

# Gaussian Naive Bayes classifier
model = GaussianNB()

# Training the model
model.fit(X, y)

# Sample treatment options
treatments = {
    "Low Blood Pressure or Anemia": "Take prescribed medication and visit a doctor. Consider iron supplements and proper sleep.",
    "Cough and Fever": "Take Neozep or Decolgen.",
    "Healthy": "No specific treatment required."
}

# Function to predict sickness and recommend treatment
def predict_sickness(symptoms):
    symptoms = np.array(symptoms).reshape(1, -1)
    predictions = model.predict(symptoms)
    predicted_sicknesses = set(predictions)
    suggested_treatments = []
    for sickness in predicted_sicknesses:
        if sickness in treatments:
            suggested_treatments.append(treatments[sickness])
    return predicted_sicknesses, suggested_treatments

# Parse symptoms input from command-line arguments
if len(sys.argv) > 1:
    symptoms = sys.argv[1].split(', ')  # Split symptoms string into a list
    predicted_sicknesses, suggested_treatments = predict_sickness(symptoms)
    print("Predicted Sicknesses: {}".format(predicted_sicknesses))
    print("Suggested Treatments: {}".format(suggested_treatments))
else:
    print("No symptoms provided.")
