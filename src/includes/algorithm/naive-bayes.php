<?php

class NaiveBayesClassifier {
    private $priorProbabilities = [];
    private $likelihoods = [];

    public function train($dataset) {
        $this->calculatePriorProbabilities($dataset);
        $this->calculateLikelihoods($dataset);
    }

    public function predict($symptoms) {
        $predictions = [];

        foreach ($this->priorProbabilities as $diagnosis => $priorProbability) {
            $likelihood = 1.0;
            foreach ($symptoms as $symptom) {
                if (isset($this->likelihoods[$diagnosis][$symptom])) {
                    $likelihood *= $this->likelihoods[$diagnosis][$symptom];
                } else {
                    $likelihood *= 0.000001;
                }
            }
            $predictions[$diagnosis] = $likelihood * $priorProbability;
        }

        arsort($predictions);
        return key($predictions);
    }

    private function calculatePriorProbabilities($dataset) {
        $totalSamples = count($dataset);
        $diagnoses = array_column($dataset, 'diagnosis');

        foreach (array_count_values($diagnoses) as $diagnosis => $count) {
            $this->priorProbabilities[$diagnosis] = $count / $totalSamples;
        }
    }

    private function calculateLikelihoods($dataset) {
        foreach ($dataset as $data) {
            $diagnosis = $data['diagnosis'];
            foreach ($data['symptoms'] as $symptom) {
                if (!isset($this->likelihoods[$diagnosis][$symptom])) {
                    $this->likelihoods[$diagnosis][$symptom] = 0;
                }
                $this->likelihoods[$diagnosis][$symptom]++;
            }
        }

        foreach ($this->likelihoods as $diagnosis => &$symptoms) {
            $total = array_sum($symptoms);
            foreach ($symptoms as &$count) {
                $count /= $total;
            }
        }
    }
}

$dataset = [
    //Tonsillitis
    ['symptoms' => ['swollen tonsils', 'sore throat', 'fever'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['painful swallowing', 'fever', 'headache'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['bad breath', 'yellow coating on tonsils', 'fever'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['sore throat', 'hoarseness', 'fever'], 'diagnosis' => 'tonsillitis'],

    //LBP
    ['symptoms' => ['blurry vision', 'dizziness', 'vomiting'], 'diagnosis' => 'low blood pressure'],
    ['symptoms' => ['lightheadedness', 'sleepiness', 'weakness'], 'diagnosis' => 'low blood pressure'],
    ['symptoms' => ['dizziness', 'nausea' ,'confusion'], 'diagnosis' => 'low blood pressure'],
    ['symptoms' => ['fainting', 'nausea', 'weakness'], 'diagnosis' => 'low blood pressure'],

    //Anemia
    ['symptoms' => ['yellowish skin', 'tiredness', 'shortness of breath'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['cold hands', 'irregular heartbeat', 'pale lips'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['pale skin', 'weakness', 'cold feet'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['dizziness', 'cold hands', 'pale lips'], 'diagnosis' => 'anemia'],

    //Common Cold
    ['symptoms' => ['runny nose', 'fatigue', 'fever'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['cough', 'sneezing', 'headache'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['headache', 'sore throat', 'sneezing'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['sneezing', 'watery eyes', 'headache'], 'diagnosis' => 'common cold'],

    //Flu
    ['symptoms' => ['cough', 'sore throat', 'fever'], 'diagnosis' => 'flu'],
    ['symptoms' => ['fatigue', 'fever', 'muscle pain'], 'diagnosis' => 'flu'],
    ['symptoms' => ['chills', 'body aches', 'cough'], 'diagnosis' => 'flu'],
    ['symptoms' => ['headache', 'fatigue', 'fever', 'cough'], 'diagnosis' => 'flu'],

    //Food Poisoning
    ['symptoms' => ['abdominal cramps', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['vomiting', 'fever', 'diarrhea'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['nausea', 'headache', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['diarrhea', 'abdominal cramps', 'fever'], 'diagnosis' => 'food poisoning'],

    //Diarrhea
    ['symptoms' => ['loose stool', 'frequent bowel movements', 'watery stool'], 'diagnosis' => 'diarrhea'],
    ['symptoms' => ['cramping in the abdomen', 'nausea', 'bloody stool'], 'diagnosis' => 'diarrhea'],
    ['symptoms' => ['watery stool', 'bloating', 'pain in the abdomen'], 'diagnosis' => 'diarrhea'],
    ['symptoms' => ['watery stool', 'nausea', 'frequent bowel movement'], 'diagnosis' => 'diarrhea'],

    //Pneumonia
    ['symptoms' => ['cough', 'shortness of breath', 'fever'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'fever', 'chills'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chest congestion', 'fever'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chest pain', 'fever'], 'diagnosis' => 'pneumonia'],

    //Asthma
    ['symptoms' => ['wheeze', 'chest pain', 'trouble sleeping'], 'diagnosis' => 'asthma'], 
    ['symptoms' => ['wheeze', 'shortness of breath', 'chest pain'], 'diagnosis' => 'asthma'],
    ['symptoms' => ['wheeze', 'shortness of breath', 'trouble sleeping'], 'diagnosis' => 'asthma'],
    
    //UTI
    ['symptoms' => ['yellowish urine', 'painful urination',  'back pain'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['yellowish urine', 'back pain', 'fever'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['yellowish urine', 'pelvic pain',  'fever'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['yellowish urine', 'painful urination',  'back pain'], 'diagnosis' => 'urinary tract infection'],
    
    //Migraine
    ['symptoms' => ['headache', 'trouble sleeping', 'mood changes'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache', 'ringing in your ears', 'blurred vision'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['muscle weakness', 'one sided head pain', 'sense sensitivity'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache', 'neck stiffness', 'confusion'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache', 'blurred vision', 'fatigue'], 'diagnosis' => 'migraine'],

    //Allergic Rhinitis
    ['symptoms' => ['sneezing', 'runny nose', 'watery eyes'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['sneezing', 'blocked nose', 'cough'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['sneezing', 'cough', 'blocked nose'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['sneezing', 'cough', 'itchy nose'], 'diagnosis' => 'allergic rhinitis'],

    //Bronchitis
    ['symptoms' => ['cough', 'mucus production', 'fatigue'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'shortness of breath', 'wheezing'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'fever', 'body aches'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'wheezing', 'runny nose'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'mucus production', 'runny nose'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'mucus production', 'shortness of breath'], 'diagnosis' => 'bronchitis'],

    //Sinusitis
    ['symptoms' => ['nasal congestion', 'pain behind the eyes', 'headache'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['nasal congestion', 'loss of smell', 'cough'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['nasal congestion', 'cough', 'fever'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['nasal congestion', 'nasal discharge', 'fever'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['nasal congestion', 'fatigue', 'headache'], 'diagnosis' => 'sinusitis'],

    //Conjunctivitis 
    ['symptoms' => ['red eyes', 'itchiness', 'tearing'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['red eyes', 'eyes discharge', 'sensitivity to light'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['red eyes', 'tearing', 'swollen eyelids'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['red eyes', 'eyes discharge', 'itchiness'], 'diagnosis' => 'conjunctivitis'],

    //Chickenpox
    ['symptoms' => ['skin rash', 'fever', 'tiredness'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['skin rash', 'loss of appetite', 'headache'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['skin rash', 'red spots', 'blisters'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['skin rash', 'blisters', 'tiredness'], 'diagnosis' => 'chickenpox'],

    //Vertigo
    ['symptoms' => ['dizziness', 'spinning sensation', 'loss of balance'], 'diagnosis' => 'vertigo'],
    ['symptoms' => ['dizziness', 'ringing in the ears', 'vomiting'], 'diagnosis' => 'vertigo'],
    ['symptoms' => ['dizziness', 'hearing loss', 'loss of balance'], 'diagnosis' => 'vertigo'],
    ['symptoms' => ['dizziness', 'nausea', 'ringing in the ears'], 'diagnosis' => 'vertigo'],

    //Mononucleosis
    ['symptoms' => ['sore throat', 'fever', 'swollen lymph nodes'], 'diagnosis' => 'mononucleosis'],
    ['symptoms' => ['fatigue', 'muscle aches', 'rash'], 'diagnosis' => 'mononucleosis'],
    ['symptoms' => ['headache', 'loss of appetite', 'night sweats'], 'diagnosis' => 'mononucleosis'],

    //Gastroenteritis
    ['symptoms' => ['diarrhea', 'nausea', 'bloating'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'vomiting'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['diarrhea', 'abdominal pain', 'bloating'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['diarrhea', 'bloody stool', 'loss of appetite'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['diarrhea', 'body pain', 'bloating'], 'diagnosis' => 'gastroenteritis'],

    //Stress
    ['symptoms' => ['difficulty breathing', 'panic attacks', 'muscle tension'], 'diagnosis' => 'stress'],
    ['symptoms' => ['indigestion', 'difficulty sleeping', 'heartburn'], 'diagnosis' => 'stress'],
    ['symptoms' => ['anxiety', 'sleep problem', 'difficulty concentrating'], 'diagnosis' => 'stress'],
    ['symptoms' => ['sleep problem', 'fatigue', 'headache'], 'diagnosis' => 'stress'],
    ['symptoms' => ['depression', 'difficulty concentrating', 'panic attacks'], 'diagnosis' => 'stress'],
    
    // Ear Infection
    ['symptoms' => ['ear pain', 'headache', 'ear discharge'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['fever', 'trouble hearing', 'loss of balance'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['tugging at ear', 'irritability', 'difficulty hearing'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['ear pain', 'itching around the ear', 'ear discharge'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['trouble hearing', 'headache', 'irritability'], 'diagnosis' => 'ear infection'],

    // Appendicitis
    ['symptoms' => ['pain in right lower abdomen', 'nausea', 'constipation'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['pain in right lower abdomen', 'diarrhea', 'vomiting'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['pain in right lower abdomen', 'diarrhea', 'inability to pass gas'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['pain in right lower abdomen', 'abdominal bloating', 'inability to pass gas'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['pain in right lower abdomen', 'pain worsen by movements', 'constipation'], 'diagnosis' => 'appendicitis'],

    // Measles
    ['symptoms' => ['skin rashes', 'fever', 'runny nose'], 'diagnosis' => 'measles'],
    ['symptoms' => ['skin rashes', 'fever', 'red eyes'], 'diagnosis' => 'measles'],
    ['symptoms' => ['skin rashes', 'fever', 'watery eyes'], 'diagnosis' => 'measles'],
    ['symptoms' => ['skin rashes', 'fever', 'cough'], 'diagnosis' => 'measles'],
    ['symptoms' => ['skin rashes', 'fever', 'sore throat'], 'diagnosis' => 'measles'],

    // Mumps
    ['symptoms' => ['swollen salivary glands', 'trouble chewing', 'pain while chewing'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['swollen salivary glands', 'tiredness', 'loss of appetite'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['swollen salivary glands', 'pain while swallowing', 'fever'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['swollen salivary glands', 'pain while swallowing', 'pain while chewing'], 'diagnosis' => 'mumps'],

    // Hyperventilation
    ['symptoms' => ['lightheadedness', 'dizziness', 'spasms in the hands'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['numbness in the hands', 'shortness of breath', 'numbness in the feet'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['spasms in the feet', 'rapid breathing', 'fast hearbeat'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['numbness in the hands', 'dizziness', 'rapid breathing'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['numbness in the feet', 'numbness in the hands', 'rapid breathing'], 'diagnosis' => 'hyperventilation'],

    // Hypothermia
    ['symptoms' => ['shivering', 'slurred speech', 'cold skin'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['shivering', 'weak pulse', 'drowsiness'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['shivering', 'low energy', 'pale skin'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['shivering', 'clumsiness', 'loss of consciousness'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['shivering', 'cold skin', 'drowsiness'], 'diagnosis' => 'hypothermia'],

    // Heartburn/Acid Reflux
    ['symptoms' => ['burning pain in chest', 'acid taste in mouth', 'difficulty swallowing'], 'diagnosis' => 'heartburn'],
    ['symptoms' => ['burning pain in chest', 'bitter taste in mouth', 'bloating'], 'diagnosis' => 'heartburn'],
    ['symptoms' => ['burning pain in chest', 'sore throat', 'acid taste in mouth'], 'diagnosis' => 'heartburn'],

    // Indigestion
    ['symptoms' => ['pain in upper abdomen', 'bloating', 'burping'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['pain in upper abdomen', 'upper abdominal pain', 'heartburn'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['pain in upper abdomen', 'acidic taste', 'gas'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['pain in upper abdomen', 'fullness after eating', 'gas'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['pain in upper abdomen', 'fullness after eating', 'burping'], 'diagnosis' => 'indigestion'],

    // Dysmenorrhea
    ['symptoms' => ['cramping in lower abdomen', 'lower back pain', 'nausea'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['cramping in lower abdomen', 'diarrhea', 'fatigue'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['cramping in lower abdomen', 'vomiting', 'bloating'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['cramping in lower abdomen', 'headache', 'muscle pain'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['cramping in lower abdomen', 'lower back pain', 'diarrhea'], 'diagnosis' => 'dysmenorrhea'],

    // Ulcer
    ['symptoms' => ['sharp pain in upper abdomen', 'dark stool', 'fainting'], 'diagnosis' => 'ulcer'],
    ['symptoms' => ['sharp pain in upper abdomen', 'red stool', 'dizziness'], 'diagnosis' => 'ulcer'],
    ['symptoms' => ['sharp pain in upper abdomen', 'indigestion', 'heartburn'], 'diagnosis' => 'ulcer'],

    // Fever
    ['symptoms' => ['raised temperature', 'chills', 'sweating'], 'diagnosis' => 'fever'],
    ['symptoms' => ['raised temperature', 'headache', 'muscle aches'], 'diagnosis' => 'fever'],
    ['symptoms' => ['raised temperature', 'loss of appetite', 'dehydration'], 'diagnosis' => 'fever'],
    ['symptoms' => ['raised temperature', 'headache', 'weakness'], 'diagnosis' => 'fever'],
    ['symptoms' => ['raised temperature', 'headache', 'loss of appetite'], 'diagnosis' => 'fever'],
    ['symptoms' => ['raised temperature', 'muscle aches', 'dehydration'], 'diagnosis' => 'fever'],

    // Diabetes
    ['symptoms' => ['frequent urination', 'excessive thirst', 'unexplained weight loss'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['frequent urination', 'feeling tired', 'fatigue'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['frequent urination', 'slow healing sores', 'frequent infections'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['frequent urination', 'slow healing sores', 'unexplained weight loss'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['frequent urination', 'requent infections', 'blurrry vision'], 'diagnosis' => 'diabetes'],

    // Over Fatigue
    ['symptoms' => ['extreme tiredness', 'muscle weakness', 'difficulty concentrating'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'dizziness', 'headaches'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'sleepiness', 'decreased motivation'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'muscle weakness', 'loss of appetite'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'dizziness', 'hallucinations'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'short-term memory problem', 'poor concentration'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['extreme tiredness', 'headaches', 'loss of appetite'], 'diagnosis' => 'over fatigue'], 

    // Eye Strain
    ['symptoms' => ['dry eyes', 'headache', 'blurred vision'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['dry eyes', 'sensitivity to light', 'difficulty concentrating'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['dry eyes', 'neck or shoulder pain', 'difficulty keeping eyes open'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['dry eyes', 'tired eyelids', 'red eyes'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['watery eyes', 'headache', 'blurred vision'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['watery eyes', 'sensitivity to light', 'difficulty concentrating'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['watery eyes', 'neck or shoulder pain', 'difficulty keeping eyes open'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['watery eyes', 'tired eyelids', 'red eyes'], 'diagnosis' => 'eye strain'],

    // Stroke
    ['symptoms' => ['sudden numbness', 'confusion', 'trouble speaking'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'trouble walking', 'severe headache'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'trouble seeing', 'dizziness'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'trouble understanding', 'loss of balance'], 'diagnosis' => 'stroke'],

    // Hyperthyroidism
    ['symptoms' => ['weight loss', 'rapid heartbeat', 'nervousness'], 'diagnosis' => 'hyperthyroidism'],
    ['symptoms' => ['weight loss', 'sweating', 'tremor'], 'diagnosis' => 'hyperthyroidism'],
    ['symptoms' => ['weight loss', 'difficulty sleeping', 'irritability'], 'diagnosis' => 'hyperthyroidism'],

    // Hypothyroidism
    ['symptoms' => ['weight gain', 'fatigue', 'cold intolerance'], 'diagnosis' => 'hypothyroidism'],
    ['symptoms' => ['weight gain', 'constipation', 'dry skin'], 'diagnosis' => 'hypothyroidism'],
    ['symptoms' => ['weight gain', 'muscle weakness', 'depression'], 'diagnosis' => 'hypothyroidism'],

    // Gallstones
    ['symptoms' => ['abdominal pain', 'nausea', 'vomiting'], 'diagnosis' => 'gallstones'],
    ['symptoms' => ['abdominal pain', 'jaundice', 'fever'], 'diagnosis' => 'gallstones'],
    ['symptoms' => ['abdominal pain', 'indigestion', 'bloating'], 'diagnosis' => 'gallstones'],

    // Hepatitis
    ['symptoms' => ['jaundice', 'fatigue', 'abdominal pain'], 'diagnosis' => 'hepatitis'],
    ['symptoms' => ['jaundice', 'nausea', 'loss of appetite'], 'diagnosis' => 'hepatitis'],
    ['symptoms' => ['jaundice', 'dark urine', 'fever'], 'diagnosis' => 'hepatitis'],

    // Hepatitis B
    ['symptoms' => ['fatigue', 'nausea', 'yellowing of the skin'], 'diagnosis' => 'hepatitis b'],
    ['symptoms' => ['fatigue', 'abdominal pain', 'dark urine'], 'diagnosis' => 'hepatitis b'],
    ['symptoms' => ['fatigue', 'joint pain', 'loss of appetite'], 'diagnosis' => 'hepatitis b'],

    // Hepatitis C
    ['symptoms' => ['fatigue', 'fever', 'jaundice'], 'diagnosis' => 'hepatitis c'],
    ['symptoms' => ['fatigue', 'nausea', 'loss of appetite'], 'diagnosis' => 'hepatitis c'],
    ['symptoms' => ['fatigue', 'joint pain', 'dark urine'], 'diagnosis' => 'hepatitis c'],

    // Irritable Bowel Syndrome (IBS)
    ['symptoms' => ['abdominal pain', 'bloating', 'diarrhea'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'constipation', 'bloating'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'diarrhea', 'mucus in stool'], 'diagnosis' => 'irritable bowel syndrome'],

    // Celiac Disease
    ['symptoms' => ['diarrhea', 'bloating', 'fatigue'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['diarrhea', 'weight loss', 'abdominal pain'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['diarrhea', 'anemia', 'rash'], 'diagnosis' => 'celiac disease'],

    // Chronic Kidney Disease
    ['symptoms' => ['fatigue', 'swelling in ankles', 'shortness of breath'], 'diagnosis' => 'chronic kidney disease'],
    ['symptoms' => ['fatigue', 'nausea', 'loss of appetite'], 'diagnosis' => 'chronic kidney disease'],
    ['symptoms' => ['fatigue', 'muscle cramps', 'itchy skin'], 'diagnosis' => 'chronic kidney disease'],

    // Chronic Obstructive Pulmonary Disease (COPD)
    ['symptoms' => ['shortness of breath', 'chronic cough', 'wheezing'], 'diagnosis' => 'chronic obstructive pulmonary disease'],
    ['symptoms' => ['shortness of breath', 'chest tightness', 'excess mucus'], 'diagnosis' => 'chronic obstructive pulmonary disease'],
    ['symptoms' => ['shortness of breath', 'fatigue', 'frequent respiratory infections'], 'diagnosis' => 'chronic obstructive pulmonary disease'],

    // Gout
    ['symptoms' => ['joint pain', 'swelling', 'redness'], 'diagnosis' => 'gout'],
    ['symptoms' => ['joint pain', 'tenderness', 'warmth'], 'diagnosis' => 'gout'],
    ['symptoms' => ['joint pain', 'stiffness', 'fever'], 'diagnosis' => 'gout'],

    // Rheumatoid Arthritis
    ['symptoms' => ['joint pain', 'stiffness', 'swelling'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['joint pain', 'fatigue', 'fever'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['joint pain', 'loss of joint function', 'deformities'], 'diagnosis' => 'rheumatoid arthritis'],

    // Depression
    ['symptoms' => ['persistent sadness', 'loss of interest', 'fatigue'], 'diagnosis' => 'depression'],
    ['symptoms' => ['persistent sadness', 'changes in appetite', 'sleep disturbances'], 'diagnosis' => 'depression'],
    ['symptoms' => ['persistent sadness', 'difficulty concentrating', 'feelings of worthlessness'], 'diagnosis' => 'depression'],

    // Anxiety Disorder
    ['symptoms' => ['excessive worry', 'restlessness', 'muscle tension'], 'diagnosis' => 'anxiety disorder'],
    ['symptoms' => ['excessive worry', 'difficulty concentrating', 'irritability'], 'diagnosis' => 'anxiety disorder'],
    ['symptoms' => ['excessive worry', 'sleep disturbances', 'fatigue'], 'diagnosis' => 'anxiety disorder'],

    // Pancreatitis
    ['symptoms' => ['abdominal pain', 'nausea', 'vomiting'], 'diagnosis' => 'pancreatitis'],
    ['symptoms' => ['abdominal pain', 'fever', 'rapid pulse'], 'diagnosis' => 'pancreatitis'],
    ['symptoms' => ['abdominal pain', 'tenderness', 'swollen abdomen'], 'diagnosis' => 'pancreatitis'],

    // Gastritis
    ['symptoms' => ['abdominal pain', 'nausea', 'bloating'], 'diagnosis' => 'gastritis'],
    ['symptoms' => ['abdominal pain', 'vomiting', 'indigestion'], 'diagnosis' => 'gastritis'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'belching'], 'diagnosis' => 'gastritis'],

    // Tuberculosis (TB)
    ['symptoms' => ['cough', 'fever', 'night sweats'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['cough', 'weight loss', 'fatigue'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['cough', 'chest pain', 'chills'], 'diagnosis' => 'tuberculosis'],

    // Meningitis
    ['symptoms' => ['fever', 'headache', 'stiff neck'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['fever', 'nausea', 'sensitivity to light'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['fever', 'confusion', 'seizures'], 'diagnosis' => 'meningitis'],

    // Hypertension (High Blood Pressure)
    ['symptoms' => ['headache', 'shortness of breath', 'nosebleeds'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'dizziness', 'chest pain'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'blurred vision', 'fatigue'], 'diagnosis' => 'hypertension'],

    // Heart Failure
    ['symptoms' => ['shortness of breath', 'fatigue', 'swelling in legs'], 'diagnosis' => 'heart failure'],
    ['symptoms' => ['shortness of breath', 'rapid heartbeat', 'persistent cough'], 'diagnosis' => 'heart failure'],
    ['symptoms' => ['shortness of breath', 'chest pain', 'lack of appetite'], 'diagnosis' => 'heart failure'],

    // Coronary Artery Disease
    ['symptoms' => ['chest pain', 'shortness of breath', 'fatigue'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['chest pain', 'nausea', 'sweating'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['chest pain', 'pain in arms', 'dizziness'], 'diagnosis' => 'coronary artery disease'],

    // Arrhythmia
    ['symptoms' => ['palpitations', 'shortness of breath', 'dizziness'], 'diagnosis' => 'arrhythmia'],
    ['symptoms' => ['palpitations', 'chest pain', 'fatigue'], 'diagnosis' => 'arrhythmia'],
    ['symptoms' => ['palpitations', 'lightheadedness', 'fainting'], 'diagnosis' => 'arrhythmia'],

    // Alzheimer's Disease
    ['symptoms' => ['memory loss', 'difficulty thinking', 'confusion'], 'diagnosis' => 'alzheimer\'s disease'],
    ['symptoms' => ['difficulty remembering recent events', 'problems with language', 'mood swings'], 'diagnosis' => 'alzheimer\'s disease'],
    ['symptoms' => ['disorientation', 'behavior changes', 'difficulty performing familiar tasks'], 'diagnosis' => 'alzheimer\'s disease'],

    // Bipolar Disorder
    ['symptoms' => ['mood swings', 'high energy', 'reduced need for sleep'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['depression', 'irritability', 'rapid speech'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['elevated mood', 'impulsivity', 'difficulty concentrating'], 'diagnosis' => 'bipolar disorder'],

    // Epilepsy
    ['symptoms' => ['seizures', 'confusion', 'staring spell'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['uncontrollable jerking movements', 'loss of consciousness', 'fear or anxiety'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['sudden mood changes', 'unusual sensations', 'temporary confusion'], 'diagnosis' => 'epilepsy'],

    // Kidney Stones
    ['symptoms' => ['severe pain in the back', 'nausea', 'blood in urine'], 'diagnosis' => 'kidney stones'],
    ['symptoms' => ['severe pain in the abdomen', 'frequent urination', 'painful urination'], 'diagnosis' => 'kidney stones'],
    ['symptoms' => ['severe pain in the groin', 'cloudy urine', 'foul-smelling urine'], 'diagnosis' => 'kidney stones'],

    // Shingles
    ['symptoms' => ['painful rash', 'blisters', 'itching'], 'diagnosis' => 'shingles'],
    ['symptoms' => ['painful rash', 'fever', 'headache'], 'diagnosis' => 'shingles'],
    ['symptoms' => ['painful rash', 'chills', 'upset stomach'], 'diagnosis' => 'shingles'],

    // Glaucoma
    ['symptoms' => ['eye pain', 'blurred vision', 'halo around lights'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['eye pain', 'redness in eyes', 'tunnel vision'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['eye pain', 'nausea', 'vomiting'], 'diagnosis' => 'glaucoma'],

    // Cataracts
    ['symptoms' => ['clouded vision', 'sensitivity to light', 'double vision'], 'diagnosis' => 'cataracts'],
    ['symptoms' => ['clouded vision', 'difficulty with vision at night', 'seeing halos around lights'], 'diagnosis' => 'cataracts'],
    ['symptoms' => ['clouded vision', 'fading colors', 'frequent changes in glasses prescription'], 'diagnosis' => 'cataracts'],

    // Macular Degeneration
    ['symptoms' => ['blurry vision', 'dark spots in vision', 'difficulty recognizing faces'], 'diagnosis' => 'macular degeneration'],
    ['symptoms' => ['blurry vision', 'straight lines appear wavy', 'difficulty reading'], 'diagnosis' => 'macular degeneration'],
    ['symptoms' => ['blurry vision', 'reduced central vision', 'need for brighter light'], 'diagnosis' => 'macular degeneration'],

    // Osteoporosis
    ['symptoms' => ['back pain', 'loss of height', 'stooped posture'], 'diagnosis' => 'osteoporosis'],
    ['symptoms' => ['back pain', 'bone fractures', 'brittle nails'], 'diagnosis' => 'osteoporosis'],
    ['symptoms' => ['back pain', 'receding gums', 'weakened grip strength'], 'diagnosis' => 'osteoporosis'],

    // Lupus
    ['symptoms' => ['fatigue', 'joint pain', 'butterfly rash'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fatigue', 'fever', 'chest pain'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fatigue', 'hair loss', 'sun sensitivity'], 'diagnosis' => 'lupus'],

    // Hay Fever (Allergic Rhinitis)
    ['symptoms' => ['sneezing', 'runny nose', 'itchy eyes'], 'diagnosis' => 'hay fever'],
    ['symptoms' => ['sneezing', 'nasal congestion', 'cough'], 'diagnosis' => 'hay fever'],
    ['symptoms' => ['sneezing', 'fatigue', 'headache'], 'diagnosis' => 'hay fever'],

    // Sleep Apnea
    ['symptoms' => ['loud snoring', 'daytime sleepiness', 'morning headache'], 'diagnosis' => 'sleep apnea'],
    ['symptoms' => ['loud snoring', 'gasping for air during sleep', 'difficulty staying asleep'], 'diagnosis' => 'sleep apnea'],
    ['symptoms' => ['loud snoring', 'dry mouth in the morning', 'difficulty concentrating'], 'diagnosis' => 'sleep apnea'],

    // Hemophilia
    ['symptoms' => ['excessive bleeding', 'easy bruising', 'joint pain'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'nosebleeds', 'blood in urine'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'swollen joints', 'prolonged bleeding from cuts'], 'diagnosis' => 'hemophilia'],

    // Leukemia
    ['symptoms' => ['fatigue', 'fever', 'frequent infections'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'unexplained weight loss', 'easy bruising'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'bone pain', 'swollen lymph nodes'], 'diagnosis' => 'leukemia'],

    // Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'fatigue', 'fever'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'night sweats', 'unexplained weight loss'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'shortness of breath', 'itching'], 'diagnosis' => 'lymphoma'],

    // Melanoma
    ['symptoms' => ['new mole', 'changing mole', 'itching mole'], 'diagnosis' => 'melanoma'],
    ['symptoms' => ['new mole', 'bleeding mole', 'painful mole'], 'diagnosis' => 'melanoma'],
    ['symptoms' => ['new mole', 'darkening of mole', 'redness around mole'], 'diagnosis' => 'melanoma'],

    // Psoriasis
    ['symptoms' => ['red patches of skin', 'dry skin', 'itching'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches of skin', 'scaly skin', 'burning sensation'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches of skin', 'cracked skin', 'stiff joints'], 'diagnosis' => 'psoriasis'],

    // Endometriosis
    ['symptoms' => ['pelvic pain', 'painful periods', 'pain during or after sex'], 'diagnosis' => 'endometriosis'],
    ['symptoms' => ['pelvic pain', 'excessive bleeding during periods', 'difficulty getting pregnant'], 'diagnosis' => 'endometriosis'],
    ['symptoms' => ['pelvic pain', 'pain with bowel movements', 'lower back pain'], 'diagnosis' => 'endometriosis'],

    // Fibroids
    ['symptoms' => ['heavy menstrual bleeding', 'pelvic pressure or pain', 'frequent urination'], 'diagnosis' => 'fibroids'],
    ['symptoms' => ['heavy menstrual bleeding', 'back or leg pain', 'constipation'], 'diagnosis' => 'fibroids'],
    ['symptoms' => ['heavy menstrual bleeding', 'enlarged abdomen', 'pain during sex'], 'diagnosis' => 'fibroids'],

    // Polycystic Ovary Syndrome (PCOS)
    ['symptoms' => ['irregular periods', 'excess facial or body hair', 'acne'], 'diagnosis' => 'polycystic ovary syndrome'],
    ['symptoms' => ['irregular periods', 'thinning hair on scalp', 'weight gain'], 'diagnosis' => 'polycystic ovary syndrome'],
    ['symptoms' => ['irregular periods', 'mood swings', 'fatigue'], 'diagnosis' => 'polycystic ovary syndrome'],

    // Ovarian Cancer
    ['symptoms' => ['abdominal bloating or swelling', 'pelvic pain', 'feeling full quickly while eating'], 'diagnosis' => 'ovarian cancer'],
    ['symptoms' => ['abdominal bloating or swelling', 'urinary urgency', 'changes in bowel habits'], 'diagnosis' => 'ovarian cancer'],
    ['symptoms' => ['abdominal bloating or swelling', 'fatigue', 'back pain'], 'diagnosis' => 'ovarian cancer'],

    // Prostate Cancer
    ['symptoms' => ['urinary problems', 'blood in semen', 'bone pain'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['urinary problems', 'erectile dysfunction', 'pelvic discomfort'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['urinary problems', 'painful ejaculation', 'weight loss'], 'diagnosis' => 'prostate cancer'],

    // Testicular Cancer
    ['symptoms' => ['lump in testicle', 'swelling in scrotum', 'dull ache or sharp pain'], 'diagnosis' => 'testicular cancer'],
    ['symptoms' => ['lump in testicle', 'feeling of heaviness in scrotum', 'pain or discomfort in testicle or scrotum'], 'diagnosis' => 'testicular cancer'],
    ['symptoms' => ['lump in testicle', 'sudden buildup of fluid in scrotum', 'lower back pain'], 'diagnosis' => 'testicular cancer'],

    // Colon Cancer
    ['symptoms' => ['change in bowel habits', 'blood in stool', 'abdominal discomfort'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['change in bowel habits', 'unexplained weight loss', 'fatigue'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['change in bowel habits', 'persistent abdominal pain', 'feeling of incomplete bowel movement'], 'diagnosis' => 'colon cancer'],

    // Breast Cancer
    ['symptoms' => ['lump in breast', 'change in breast shape or size', 'dimpling of breast skin'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'nipple discharge other than breast milk', 'redness or flaky skin in the nipple area'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'swelling in armpit', 'pain in breast or nipple'], 'diagnosis' => 'breast cancer'],

    // Dermatitis (Eczema)
    ['symptoms' => ['itchy skin', 'red rash', 'dry skin'], 'diagnosis' => 'dermatitis (eczema)'],
    ['symptoms' => ['itchy skin', 'scaly patches', 'thickened skin'], 'diagnosis' => 'dermatitis (eczema)'],
    ['symptoms' => ['itchy skin', 'blisters', 'oozing or crusting'], 'diagnosis' => 'dermatitis (eczema)'],

    // Acne
    ['symptoms' => ['pimples', 'blackheads', 'whiteheads'], 'diagnosis' => 'acne'],
    ['symptoms' => ['pimples', 'painful cysts', 'nodules'], 'diagnosis' => 'acne'],
    ['symptoms' => ['pimples', 'scarring', 'dark spots'], 'diagnosis' => 'acne'],

    // Hives (Urticaria)
    ['symptoms' => ['raised, itchy bumps', 'red or skin-colored welts', 'swelling'], 'diagnosis' => 'hives (urticaria)'],
    ['symptoms' => ['raised, itchy bumps', 'blanching (turning white when pressed)', 'burning sensation'], 'diagnosis' => 'hives (urticaria)'],
    ['symptoms' => ['raised, itchy bumps', 'rash that changes location', 'triggers (such as heat or stress)'], 'diagnosis' => 'hives (urticaria)'],

    // Ringworm (Tinea Corporis)
    ['symptoms' => ['red, scaly rash', 'circular shape', 'itching'], 'diagnosis' => 'ringworm (tinea corporis)'],
    ['symptoms' => ['red, scaly rash', 'clear center in the rash', 'crusting'], 'diagnosis' => 'ringworm (tinea corporis)'],
    ['symptoms' => ['red, scaly rash', 'multiple rings or patches', 'spreading of rash'], 'diagnosis' => 'ringworm (tinea corporis)'],

    // Athlete's Foot (Tinea Pedis)
    ['symptoms' => ['itchy, scaly rash', 'cracked skin', 'burning sensation'], 'diagnosis' => 'athlete\'s foot (tinea pedis)'],
    ['symptoms' => ['itchy, scaly rash', 'blisters', 'peeling skin'], 'diagnosis' => 'athlete\'s foot (tinea pedis)'],
    ['symptoms' => ['itchy, scaly rash', 'discolored toenails', 'unpleasant foot odor'], 'diagnosis' => 'athlete\'s foot (tinea pedis)'],

    // Warts
    ['symptoms' => ['flesh-colored bumps', 'rough to the touch', 'black dots (clotted blood vessels)'], 'diagnosis' => 'warts'],
    ['symptoms' => ['flesh-colored bumps', 'tiny black dots', 'pain or tenderness'], 'diagnosis' => 'warts'],
    ['symptoms' => ['flesh-colored bumps', 'clusters of bumps', 'spreading to nearby skin'], 'diagnosis' => 'warts'],

    // Cold Sores (Herpes Simplex Virus)
    ['symptoms' => ['fluid-filled blisters', 'tingling or burning sensation', 'painful sores'], 'diagnosis' => 'cold sores (herpes simplex virus)'],
    ['symptoms' => ['fluid-filled blisters', 'fever', 'swollen lymph nodes'], 'diagnosis' => 'cold sores (herpes simplex virus)'],
    ['symptoms' => ['fluid-filled blisters', 'crusting sores', 'tingling around lips'], 'diagnosis' => 'cold sores (herpes simplex virus)'],

    // Scleroderma
    ['symptoms' => ['hardening and tightening of skin', 'swelling of hands and feet', 'red spots on skin'], 'diagnosis' => 'scleroderma'],
    ['symptoms' => ['hardening and tightening of skin', 'white or blue fingers or toes in response to cold or stress', 'ulcers on fingertips or toes'], 'diagnosis' => 'scleroderma'],
    ['symptoms' => ['hardening and tightening of skin', 'pain and stiffness in joints', 'shortness of breath'], 'diagnosis' => 'scleroderma'],

    // Muscle Strain
    ['symptoms' => ['muscle pain and tenderness', 'swelling', 'bruising'], 'diagnosis' => 'muscle strain'],
    ['symptoms' => ['muscle pain and tenderness', 'limited range of motion', 'muscle spasms'], 'diagnosis' => 'muscle strain'],
    ['symptoms' => ['muscle pain and tenderness', 'stiffness', 'difficulty moving the muscle'], 'diagnosis' => 'muscle strain'],

    // Muscle Sprain
    ['symptoms' => ['sharp pain', 'swelling', 'bruising'], 'diagnosis' => 'muscle sprain'],
    ['symptoms' => ['sharp pain', 'limited range of motion', 'muscle weakness'], 'diagnosis' => 'muscle sprain'],
    ['symptoms' => ['sharp pain', 'instability', 'popping sensation'], 'diagnosis' => 'muscle sprain'],

    // Muscular Dystrophy
    ['symptoms' => ['muscle weakness', 'progressive loss of muscle mass', 'difficulty walking'], 'diagnosis' => 'muscular dystrophy'],
    ['symptoms' => ['muscle weakness', 'difficulty with motor skills', 'contractures (muscle tightness)'], 'diagnosis' => 'muscular dystrophy'],
    ['symptoms' => ['muscle weakness', 'drooping eyelids', 'curved spine (scoliosis)'], 'diagnosis' => 'muscular dystrophy'],

    // Compartment Syndrome
    ['symptoms' => ['muscle pain', 'tingling or burning sensation', 'swelling'], 'diagnosis' => 'compartment syndrome'],
    ['symptoms' => ['muscle pain', 'numbness or paralysis', 'pale or cool skin'], 'diagnosis' => 'compartment syndrome'],
    ['symptoms' => ['muscle pain', 'decreased sensation', 'weakness'], 'diagnosis' => 'compartment syndrome'],

    // Muscular Atrophy
    ['symptoms' => ['muscle wasting', 'weakness', 'loss of motor control'], 'diagnosis' => 'muscular atrophy'],
    ['symptoms' => ['muscle wasting', 'impaired balance', 'difficulty standing or walking'], 'diagnosis' => 'muscular atrophy'],
    ['symptoms' => ['muscle wasting', 'fasciculations (muscle twitches)', 'difficulty swallowing'], 'diagnosis' => 'muscular atrophy'],

    // Bone Cancer
    ['symptoms' => ['bone pain', 'swelling and tenderness near the affected area', 'fractures'], 'diagnosis' => 'bone cancer'],
    ['symptoms' => ['unexplained weight loss', 'fatigue', 'anemia'], 'diagnosis' => 'bone cancer'],
    ['symptoms' => ['fever', 'chills', 'night sweats'], 'diagnosis' => 'bone cancer'],

    // Bone Spurs (Osteophytes)
    ['symptoms' => ['pain in the affected joint', 'limited range of motion', 'swelling'], 'diagnosis' => 'bone spurs (osteophytes)'],
    ['symptoms' => ['numbness or tingling', 'muscle weakness', 'difficulty walking'], 'diagnosis' => 'bone spurs (osteophytes)'],
    ['symptoms' => ['neck pain', 'headaches', 'joint instability'], 'diagnosis' => 'bone spurs (osteophytes)'],

    // Bone Cysts
    ['symptoms' => ['bone pain', 'swelling', 'fracture'], 'diagnosis' => 'bone cysts'],
    ['symptoms' => ['joint pain', 'limited range of motion', 'weakness in the affected area'], 'diagnosis' => 'bone cysts'],
    ['symptoms' => ['tenderness over the affected bone', 'muscle atrophy', 'numbness or tingling'], 'diagnosis' => 'bone cysts'],

    // Bone Marrow Cancer (Multiple Myeloma)
    ['symptoms' => ['bone pain (especially in the back or ribs)', 'fatigue', 'bone fractures'], 'diagnosis' => 'bone marrow cancer (multiple myeloma)'],
    ['symptoms' => ['frequent infections', 'weakness or numbness in the legs', 'nausea or constipation'], 'diagnosis' => 'bone marrow cancer (multiple myeloma)'],
    ['symptoms' => ['unexplained weight loss', 'thirst', 'confusion'], 'diagnosis' => 'bone marrow cancer (multiple myeloma)'],

    // Osteogenesis Imperfecta (Brittle Bone Disease)
    ['symptoms' => ['frequent bone fractures', 'blue tint to the sclera (whites of the eyes)', 'short stature'], 'diagnosis' => 'osteogenesis imperfecta (brittle bone disease)'],
    ['symptoms' => ['weak muscles', 'hearing loss', 'teeth that appear translucent or discolored'], 'diagnosis' => 'osteogenesis imperfecta (brittle bone disease)'],
    ['symptoms' => ['respiratory problems', 'scoliosis', 'brittle teeth'], 'diagnosis' => 'osteogenesis imperfecta (brittle bone disease)'],

    // Osteochondroma
    ['symptoms' => ['pain or swelling near the affected bone', 'limited range of motion', 'numbness or tingling in the nearby tissues'], 'diagnosis' => 'osteochondroma'],
    ['symptoms' => ['a lump or mass near the affected bone', 'a sensation of warmth or redness near the lump', 'inability to move the affected joint'], 'diagnosis' => 'osteochondroma'],
    ['symptoms' => ['abnormal growth near the affected bone', 'loss of muscle mass', 'decreased ability to move the affected joint'], 'diagnosis' => 'osteochondroma'],

    // Cirrhosis
    ['symptoms' => ['fatigue', 'easily bleeding or bruising', 'loss of appetite'], 'diagnosis' => 'cirrhosis'],
    ['symptoms' => ['nausea', 'swelling in your legs, feet or ankles (edema)', 'weight loss'], 'diagnosis' => 'cirrhosis'],
    ['symptoms' => ['itchy skin', 'yellow discoloration in the skin and eyes (jaundice)', 'fluid accumulation in your abdomen (ascites)'], 'diagnosis' => 'cirrhosis'],

    // Pancreatic Cancer
    ['symptoms' => ['abdominal pain that radiates to your back', 'loss of appetite', 'unexplained weight loss'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['jaundice', 'light-colored stools', 'dark-colored urine'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['blood clots', 'diabetes', 'fatigue'], 'diagnosis' => 'pancreatic cancer'],

    // Liver Cancer
    ['symptoms' => ['abdominal pain or tenderness', 'swelling in the abdomen', 'unexplained weight loss'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['yellow discoloration of your skin and the whites of your eyes (jaundice)', 'white, chalky stools', 'easy bruising or bleeding'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['loss of appetite', 'nausea or vomiting', 'an enlarged liver felt as a mass under the ribs on the right side'], 'diagnosis' => 'liver cancer'],

    // Kidney Cancer (Renal Cell Carcinoma)
    ['symptoms' => ['blood in urine', 'back pain just below the ribs that doesn\'t go away', 'weight loss'], 'diagnosis' => 'kidney cancer (renal cell carcinoma)'],
    ['symptoms' => ['loss of appetite', 'fatigue', 'anemia'], 'diagnosis' => 'kidney cancer (renal cell carcinoma)'],
    ['symptoms' => ['swelling in legs or ankles', 'vision problems', 'high blood pressure'], 'diagnosis' => 'kidney cancer (renal cell carcinoma)'],

    // Bladder Cancer
    ['symptoms' => ['blood in urine', 'pelvic pain', 'back pain'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['painful urination', 'frequent urination', 'urinary urgency'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['fatigue', 'weight loss', 'bone pain'], 'diagnosis' => 'bladder cancer'],

    // Colon Cancer
    ['symptoms' => ['a feeling that your bowel doesn\'t empty completely', 'weakness or fatigue', 'unexplained weight loss'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['iron deficiency anemia', 'narrow stool', 'abdominal pain or discomfort'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['diarrhea', 'constipation', 'changes in bowel habits'], 'diagnosis' => 'colon cancer'],

    // Skin Cancer
    ['symptoms' => ['changes in skin growth', 'sores that do not heal', 'changes in mole appearance'], 'diagnosis' => 'skin cancer'],
    ['symptoms' => ['changes in skin growth', 'itchy or painful lesions', 'bleeding from skin lesions'], 'diagnosis' => 'skin cancer'],
    ['symptoms' => ['changes in skin growth', 'red or inflamed skin', 'swelling around skin lesions'], 'diagnosis' => 'skin cancer'],

    // Bladder Cancer
    ['symptoms' => ['blood in urine', 'frequent urination', 'painful urination'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['blood in urine', 'lower back pain', 'urinary urgency'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['blood in urine', 'fatigue', 'weight loss'], 'diagnosis' => 'bladder cancer'],

    // Pancreatic Cancer
    ['symptoms' => ['abdominal pain', 'unintended weight loss', 'jaundice'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'nausea'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'back pain', 'digestive problems'], 'diagnosis' => 'pancreatic cancer'],

    // Liver Cancer
    ['symptoms' => ['abdominal pain', 'swelling in abdomen', 'unexplained weight loss'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['abdominal pain', 'yellowing of skin and eyes', 'fatigue'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'loss of appetite'], 'diagnosis' => 'liver cancer'],

    // Esophageal Cancer
    ['symptoms' => ['difficulty swallowing', 'unintended weight loss', 'chest pain'], 'diagnosis' => 'esophageal cancer'],
    ['symptoms' => ['difficulty swallowing', 'hoarseness', 'coughing or choking while swallowing'], 'diagnosis' => 'esophageal cancer'],
    ['symptoms' => ['difficulty swallowing', 'indigestion', 'heartburn'], 'diagnosis' => 'esophageal cancer'],

    // Bone Cancer
    ['symptoms' => ['bone pain', 'swelling around bones', 'fractures or breaks in bones'], 'diagnosis' => 'bone cancer'],
    ['symptoms' => ['bone pain', 'fatigue', 'unexplained weight loss'], 'diagnosis' => 'bone cancer'],
    ['symptoms' => ['bone pain', 'fever', 'night sweats'], 'diagnosis' => 'bone cancer'],

    // Brain Tumor
    ['symptoms' => ['headaches', 'seizures', 'nausea or vomiting'], 'diagnosis' => 'brain tumor'],
    ['symptoms' => ['headaches', 'changes in vision', 'weakness or numbness in limbs'], 'diagnosis' => 'brain tumor'],
    ['symptoms' => ['headaches', 'difficulty with balance', 'personality changes'], 'diagnosis' => 'brain tumor'],

    // Leukemia
    ['symptoms' => ['fatigue', 'fever', 'frequent infections'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'unexplained weight loss', 'easy bruising'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'bone pain', 'swollen lymph nodes'], 'diagnosis' => 'leukemia'],

    // Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'fatigue', 'fever'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'night sweats', 'unexplained weight loss'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'shortness of breath', 'itching'], 'diagnosis' => 'lymphoma'],

    // Melanoma
    ['symptoms' => ['new mole', 'changing mole', 'itching mole'], 'diagnosis' => 'melanoma'],
    ['symptoms' => ['new mole', 'bleeding mole', 'painful mole'], 'diagnosis' => 'melanoma'],
    ['symptoms' => ['new mole', 'darkening of mole', 'redness around mole'], 'diagnosis' => 'melanoma'],

    // Psoriasis
    ['symptoms' => ['red patches of skin', 'dry skin', 'itching'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches of skin', 'scaly skin', 'burning sensation'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches of skin', 'cracked skin', 'stiff joints'], 'diagnosis' => 'psoriasis'],

    // Endometriosis
    ['symptoms' => ['pelvic pain', 'painful periods', 'pain during or after sex'], 'diagnosis' => 'endometriosis'],
    ['symptoms' => ['pelvic pain', 'excessive bleeding during periods', 'difficulty getting pregnant'], 'diagnosis' => 'endometriosis'],
    ['symptoms' => ['pelvic pain', 'pain with bowel movements', 'lower back pain'], 'diagnosis' => 'endometriosis'],

    // Fibroids
    ['symptoms' => ['heavy menstrual bleeding', 'pelvic pressure or pain', 'frequent urination'], 'diagnosis' => 'fibroids'],
    ['symptoms' => ['heavy menstrual bleeding', 'back or leg pain', 'constipation'], 'diagnosis' => 'fibroids'],
    ['symptoms' => ['heavy menstrual bleeding', 'enlarged abdomen', 'pain during sex'], 'diagnosis' => 'fibroids'],

    // Polycystic Ovary Syndrome (PCOS)
    ['symptoms' => ['irregular periods', 'excess facial or body hair', 'acne'], 'diagnosis' => 'polycystic ovary syndrome'],
    ['symptoms' => ['irregular periods', 'thinning hair on scalp', 'weight gain'], 'diagnosis' => 'polycystic ovary syndrome'],
    ['symptoms' => ['irregular periods', 'mood swings', 'fatigue'], 'diagnosis' => 'polycystic ovary syndrome'],

    // Ovarian Cancer
    ['symptoms' => ['abdominal bloating', 'pelvic pain', 'difficulty eating'], 'diagnosis' => 'ovarian cancer'],
    ['symptoms' => ['abdominal bloating', 'feeling full quickly', 'frequent urination'], 'diagnosis' => 'ovarian cancer'],
    ['symptoms' => ['abdominal bloating', 'back pain', 'constipation'], 'diagnosis' => 'ovarian cancer'],

    // Uterine Cancer
    ['symptoms' => ['abnormal vaginal bleeding', 'pelvic pain', 'pain during urination'], 'diagnosis' => 'uterine cancer'],
    ['symptoms' => ['abnormal vaginal bleeding', 'watery or bloody vaginal discharge', 'pelvic mass'], 'diagnosis' => 'uterine cancer'],
    ['symptoms' => ['abnormal vaginal bleeding', 'pain during intercourse', 'weight loss'], 'diagnosis' => 'uterine cancer'],

    // Cervical Cancer
    ['symptoms' => ['abnormal vaginal bleeding', 'pelvic pain', 'pain during urination'], 'diagnosis' => 'cervical cancer'],
    ['symptoms' => ['abnormal vaginal bleeding', 'watery or bloody vaginal discharge', 'pelvic mass'], 'diagnosis' => 'cervical cancer'],
    ['symptoms' => ['abnormal vaginal bleeding', 'pain during intercourse', 'lower back pain'], 'diagnosis' => 'cervical cancer'],

    // Breast Cancer
    ['symptoms' => ['lump in breast', 'change in breast size or shape', 'nipple discharge'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'skin changes on breast', 'redness or pitting of breast skin'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'swelling in armpit', 'pain in breast or nipple'], 'diagnosis' => 'breast cancer'],

    // Prostate Cancer
    ['symptoms' => ['urinary problems', 'blood in urine', 'erectile dysfunction'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['urinary problems', 'bone pain', 'weakness or numbness in legs or feet'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['urinary problems', 'painful ejaculation', 'decreased force in urine stream'], 'diagnosis' => 'prostate cancer'],

    // Colon Cancer
    ['symptoms' => ['change in bowel habits', 'blood in stool', 'abdominal discomfort'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['change in bowel habits', 'rectal bleeding', 'persistent abdominal pain'], 'diagnosis' => 'colon cancer'],
    ['symptoms' => ['change in bowel habits', 'unintended weight loss', 'weakness or fatigue'], 'diagnosis' => 'colon cancer'],

    // Rectal Cancer
    ['symptoms' => ['rectal bleeding', 'persistent bowel changes', 'abdominal pain'], 'diagnosis' => 'rectal cancer'],
    ['symptoms' => ['rectal bleeding', 'unintended weight loss', 'fatigue'], 'diagnosis' => 'rectal cancer'],
    ['symptoms' => ['rectal bleeding', 'anemia', 'pelvic pain'], 'diagnosis' => 'rectal cancer'],

    // Esophageal Cancer
    ['symptoms' => ['difficulty swallowing', 'weight loss', 'chest pain'], 'diagnosis' => 'esophageal cancer'],
    ['symptoms' => ['difficulty swallowing', 'hoarseness', 'persistent cough'], 'diagnosis' => 'esophageal cancer'],
    ['symptoms' => ['difficulty swallowing', 'indigestion', 'heartburn'], 'diagnosis' => 'esophageal cancer'],

    // Stomach Cancer
    ['symptoms' => ['abdominal pain', 'nausea', 'unintended weight loss'], 'diagnosis' => 'stomach cancer'],
    ['symptoms' => ['abdominal pain', 'difficulty swallowing', 'bloating after meals'], 'diagnosis' => 'stomach cancer'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'heartburn'], 'diagnosis' => 'stomach cancer'],

    // Kidney Cancer
    ['symptoms' => ['blood in urine', 'persistent back pain', 'unexplained weight loss'], 'diagnosis' => 'kidney cancer'],
    ['symptoms' => ['blood in urine', 'fatigue', 'fever'], 'diagnosis' => 'kidney cancer'],
    ['symptoms' => ['blood in urine', 'abdominal pain', 'swelling in legs or ankles'], 'diagnosis' => 'kidney cancer'],

    // Gallbladder Cancer
    ['symptoms' => ['abdominal pain', 'jaundice', 'unintended weight loss'], 'diagnosis' => 'gallbladder cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'bloating'], 'diagnosis' => 'gallbladder cancer'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'fever'], 'diagnosis' => 'gallbladder cancer'],

    // Thyroid Cancer
    ['symptoms' => ['lump in neck', 'difficulty swallowing', 'hoarseness'], 'diagnosis' => 'thyroid cancer'],
    ['symptoms' => ['lump in neck', 'persistent cough', 'neck pain'], 'diagnosis' => 'thyroid cancer'],
    ['symptoms' => ['lump in neck', 'difficulty breathing', 'swollen lymph nodes'], 'diagnosis' => 'thyroid cancer'],

    // Hodgkin's Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'night sweats', 'unexplained weight loss'], 'diagnosis' => 'Hodgkin\'s lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'fever', 'fatigue'], 'diagnosis' => 'Hodgkin\'s lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'persistent fatigue', 'itching'], 'diagnosis' => 'Hodgkin\'s lymphoma'],

    // Non-Hodgkin's Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'fever', 'night sweats'], 'diagnosis' => 'non-Hodgkin\'s lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'unexplained weight loss', 'fatigue'], 'diagnosis' => 'non-Hodgkin\'s lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'cough', 'shortness of breath'], 'diagnosis' => 'non-Hodgkin\'s lymphoma'],

    // Multiple Myeloma
    ['symptoms' => ['bone pain', 'fatigue', 'frequent infections'], 'diagnosis' => 'multiple myeloma'],
    ['symptoms' => ['bone pain', 'nausea', 'constipation'], 'diagnosis' => 'multiple myeloma'],
    ['symptoms' => ['bone pain', 'loss of appetite', 'weight loss'], 'diagnosis' => 'multiple myeloma'],

    // Rheumatoid Arthritis
    ['symptoms' => ['joint pain', 'swelling in joints', 'stiffness in joints'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['joint pain', 'fatigue', 'fever'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['joint pain', 'weight loss', 'weakness'], 'diagnosis' => 'rheumatoid arthritis'],

    // Lupus
    ['symptoms' => ['fatigue', 'joint pain', 'skin rashes'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fatigue', 'fever', 'hair loss'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fatigue', 'chest pain', 'shortness of breath'], 'diagnosis' => 'lupus'],

    // Multiple Sclerosis
    ['symptoms' => ['numbness or weakness in limbs', 'vision problems', 'tingling or pain'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['numbness or weakness in limbs', 'tremors', 'fatigue'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['numbness or weakness in limbs', 'dizziness', 'loss of coordination'], 'diagnosis' => 'multiple sclerosis'],

    // Parkinson's Disease
    ['symptoms' => ['tremors', 'slowed movement', 'rigid muscles'], 'diagnosis' => 'Parkinson\'s disease'],
    ['symptoms' => ['tremors', 'impaired posture', 'balance problems'], 'diagnosis' => 'Parkinson\'s disease'],
    ['symptoms' => ['tremors', 'speech changes', 'writing changes'], 'diagnosis' => 'Parkinson\'s disease'],

    // Amyotrophic Lateral Sclerosis (ALS)
    ['symptoms' => ['muscle weakness', 'difficulty speaking', 'difficulty swallowing'], 'diagnosis' => 'amyotrophic lateral sclerosis'],
    ['symptoms' => ['muscle weakness', 'muscle cramps', 'twitching'], 'diagnosis' => 'amyotrophic lateral sclerosis'],
    ['symptoms' => ['muscle weakness', 'difficulty walking', 'difficulty breathing'], 'diagnosis' => 'amyotrophic lateral sclerosis'],

    // Huntington's Disease
    ['symptoms' => ['movement disorders', 'cognitive decline', 'psychiatric disorders'], 'diagnosis' => 'Huntington\'s disease'],
    ['symptoms' => ['movement disorders', 'difficulty concentrating', 'depression'], 'diagnosis' => 'Huntington\'s disease'],
    ['symptoms' => ['movement disorders', 'impulsiveness', 'mood swings'], 'diagnosis' => 'Huntington\'s disease'],

    // Crohn's Disease
    ['symptoms' => ['abdominal pain', 'diarrhea', 'weight loss'], 'diagnosis' => 'Crohn\'s disease'],
    ['symptoms' => ['abdominal pain', 'fatigue', 'fever'], 'diagnosis' => 'Crohn\'s disease'],
    ['symptoms' => ['abdominal pain', 'mouth sores', 'reduced appetite'], 'diagnosis' => 'Crohn\'s disease'],

    // Ulcerative Colitis
    ['symptoms' => ['abdominal pain', 'bloody diarrhea', 'weight loss'], 'diagnosis' => 'ulcerative colitis'],
    ['symptoms' => ['abdominal pain', 'fatigue', 'rectal bleeding'], 'diagnosis' => 'ulcerative colitis'],
    ['symptoms' => ['abdominal pain', 'urgent need to defecate', 'inability to defecate despite urgency'], 'diagnosis' => 'ulcerative colitis'],

    // Celiac Disease
    ['symptoms' => ['diarrhea', 'weight loss', 'bloating'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['diarrhea', 'anemia', 'fatigue'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['diarrhea', 'osteoporosis', 'headaches'], 'diagnosis' => 'celiac disease'],

    // Irritable Bowel Syndrome (IBS)
    ['symptoms' => ['abdominal pain', 'cramping', 'bloating'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'diarrhea', 'constipation'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'gas', 'mucus in stool'], 'diagnosis' => 'irritable bowel syndrome'],

    // Bowel Syndrome
    ['symptoms' => ['abdominal pain', 'nausea', 'loss of appetite'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'urgent need to defecate', 'bloating'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['abdominal pain', 'feeling of incomplete evacuation', 'constipation'], 'diagnosis' => 'irritable bowel syndrome'],

    // Chronic Fatigue Syndrome
    ['symptoms' => ['extreme fatigue', 'unrefreshing sleep', 'difficulty concentrating'], 'diagnosis' => 'chronic fatigue syndrome'],
    ['symptoms' => ['extreme fatigue', 'sore throat', 'muscle pain'], 'diagnosis' => 'chronic fatigue syndrome'],
    ['symptoms' => ['extreme fatigue', 'headaches', 'joint pain'], 'diagnosis' => 'chronic fatigue syndrome'],

    // Fibromyalgia
    ['symptoms' => ['widespread pain', 'fatigue', 'sleep disturbances'], 'diagnosis' => 'fibromyalgia'],
    ['symptoms' => ['widespread pain', 'memory problems', 'irritable bowel syndrome'], 'diagnosis' => 'fibromyalgia'],
    ['symptoms' => ['widespread pain', 'depression', 'headaches'], 'diagnosis' => 'fibromyalgia'],

    // Lyme Disease
    ['symptoms' => ['rash', 'fever', 'headache'], 'diagnosis' => 'Lyme disease'],
    ['symptoms' => ['rash', 'fatigue', 'muscle and joint pain'], 'diagnosis' => 'Lyme disease'],
    ['symptoms' => ['rash', 'swollen lymph nodes', 'chills'], 'diagnosis' => 'Lyme disease'],

    // Hepatitis B
    ['symptoms' => ['fatigue', 'nausea', 'jaundice'], 'diagnosis' => 'hepatitis B'],
    ['symptoms' => ['fatigue', 'abdominal pain', 'dark urine'], 'diagnosis' => 'hepatitis B'],
    ['symptoms' => ['fatigue', 'loss of appetite', 'joint pain'], 'diagnosis' => 'hepatitis B'],

    // Hepatitis C
    ['symptoms' => ['fatigue', 'muscle aches', 'jaundice'], 'diagnosis' => 'hepatitis C'],
    ['symptoms' => ['fatigue', 'abdominal pain', 'nausea'], 'diagnosis' => 'hepatitis C'],
    ['symptoms' => ['fatigue', 'loss of appetite', 'dark urine'], 'diagnosis' => 'hepatitis C'],

    // Liver Cirrhosis
    ['symptoms' => ['fatigue', 'weakness', 'easy bruising'], 'diagnosis' => 'liver cirrhosis'],
    ['symptoms' => ['fatigue', 'swelling in legs', 'jaundice'], 'diagnosis' => 'liver cirrhosis'],
    ['symptoms' => ['fatigue', 'weight loss', 'itchy skin'], 'diagnosis' => 'liver cirrhosis'],

    // Pancreatitis
    ['symptoms' => ['abdominal pain', 'nausea', 'vomiting'], 'diagnosis' => 'pancreatitis'],
    ['symptoms' => ['abdominal pain', 'fever', 'rapid pulse'], 'diagnosis' => 'pancreatitis'],
    ['symptoms' => ['abdominal pain', 'bloating', 'tender abdomen'], 'diagnosis' => 'pancreatitis'],

    // Peptic Ulcer
    ['symptoms' => ['burning stomach pain', 'bloating', 'heartburn'], 'diagnosis' => 'peptic ulcer'],
    ['symptoms' => ['burning stomach pain', 'nausea', 'fatty food intolerance'], 'diagnosis' => 'peptic ulcer'],
    ['symptoms' => ['burning stomach pain', 'belching', 'poor appetite'], 'diagnosis' => 'peptic ulcer'],

    // Gastroesophageal Reflux Disease (GERD)
    ['symptoms' => ['heartburn', 'regurgitation', 'difficulty swallowing'], 'diagnosis' => 'gastroesophageal reflux disease'],
    ['symptoms' => ['heartburn', 'chest pain', 'chronic cough'], 'diagnosis' => 'gastroesophageal reflux disease'],
    ['symptoms' => ['heartburn', 'sour taste in mouth', 'hoarseness'], 'diagnosis' => 'gastroesophageal reflux disease'],

    // Chronic Obstructive Pulmonary Disease (COPD)
    ['symptoms' => ['shortness of breath', 'chronic cough', 'wheezing'], 'diagnosis' => 'chronic obstructive pulmonary disease'],
    ['symptoms' => ['shortness of breath', 'frequent respiratory infections', 'fatigue'], 'diagnosis' => 'chronic obstructive pulmonary disease'],
    ['symptoms' => ['shortness of breath', 'chest tightness', 'cyanosis'], 'diagnosis' => 'chronic obstructive pulmonary disease'],

    // Asthma
    ['symptoms' => ['shortness of breath', 'chest tightness', 'wheezing'], 'diagnosis' => 'asthma'],
    ['symptoms' => ['shortness of breath', 'coughing', 'difficulty sleeping'], 'diagnosis' => 'asthma'],
    ['symptoms' => ['shortness of breath', 'whistling sound when exhaling', 'chest pain'], 'diagnosis' => 'asthma'],

    // Tuberculosis
    ['symptoms' => ['persistent cough', 'chest pain', 'coughing up blood'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['persistent cough', 'fever', 'night sweats'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['persistent cough', 'fatigue', 'unintended weight loss'], 'diagnosis' => 'tuberculosis'],

    // Cystic Fibrosis
    ['symptoms' => ['persistent cough', 'frequent lung infections', 'wheezing'], 'diagnosis' => 'cystic fibrosis'],
    ['symptoms' => ['persistent cough', 'shortness of breath', 'poor growth'], 'diagnosis' => 'cystic fibrosis'],
    ['symptoms' => ['persistent cough', 'greasy stools', 'nasal polyps'], 'diagnosis' => 'cystic fibrosis'],

    // Pulmonary Fibrosis
    ['symptoms' => ['shortness of breath', 'dry cough', 'fatigue'], 'diagnosis' => 'pulmonary fibrosis'],
    ['symptoms' => ['shortness of breath', 'unexplained weight loss', 'aching muscles'], 'diagnosis' => 'pulmonary fibrosis'],
    ['symptoms' => ['shortness of breath', 'clubbing of fingers', 'wheezing'], 'diagnosis' => 'pulmonary fibrosis'],

    // Bronchitis
    ['symptoms' => ['cough', 'production of mucus', 'fatigue'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'shortness of breath', 'chest discomfort'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'mild fever', 'sore throat'], 'diagnosis' => 'bronchitis'],

    // Pneumonia
    ['symptoms' => ['cough', 'fever', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'fatigue', 'nausea'], 'diagnosis' => 'pneumonia'],

    // COVID-19
    ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'COVID-19'],
    ['symptoms' => ['fever', 'fatigue', 'loss of taste or smell'], 'diagnosis' => 'COVID-19'],
    ['symptoms' => ['fever', 'body aches', 'sore throat'], 'diagnosis' => 'COVID-19'],

    // Influenza
    ['symptoms' => ['fever', 'chills', 'muscle aches'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['fever', 'sore throat', 'runny nose'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'influenza'],

    // Common Cold
    ['symptoms' => ['runny nose', 'sore throat', 'cough'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['runny nose', 'sneezing', 'congestion'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['runny nose', 'headache', 'mild fever'], 'diagnosis' => 'common cold'],

    // Allergic Rhinitis
    ['symptoms' => ['sneezing', 'runny nose', 'itchy eyes'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['sneezing', 'congestion', 'postnasal drip'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['sneezing', 'cough', 'watery eyes'], 'diagnosis' => 'allergic rhinitis'],

    // Sinusitis
    ['symptoms' => ['facial pain', 'nasal congestion', 'headache'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['facial pain', 'thick nasal discharge', 'reduced sense of smell'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['facial pain', 'fever', 'ear pain'], 'diagnosis' => 'sinusitis'],

    // Tonsillitis
    ['symptoms' => ['sore throat', 'red, swollen tonsils', 'difficulty swallowing'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['sore throat', 'white or yellow coating on tonsils', 'fever'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['sore throat', 'tender lymph nodes', 'bad breath'], 'diagnosis' => 'tonsillitis'],

    // Pharyngitis
    ['symptoms' => ['sore throat', 'scratchy throat', 'difficulty swallowing'], 'diagnosis' => 'pharyngitis'],
    ['symptoms' => ['sore throat', 'dry throat', 'hoarseness'], 'diagnosis' => 'pharyngitis'],
    ['symptoms' => ['sore throat', 'swollen glands', 'runny nose'], 'diagnosis' => 'pharyngitis'],

    // Laryngitis
    ['symptoms' => ['hoarseness', 'loss of voice', 'sore throat'], 'diagnosis' => 'laryngitis'],
    ['symptoms' => ['hoarseness', 'dry throat', 'tickling sensation in throat'], 'diagnosis' => 'laryngitis'],
    ['symptoms' => ['hoarseness', 'cough', 'difficulty speaking'], 'diagnosis' => 'laryngitis'],

    // Otitis Media
    ['symptoms' => ['ear pain', 'fever', 'hearing loss'], 'diagnosis' => 'otitis media'],
    ['symptoms' => ['ear pain', 'fluid drainage from ear', 'difficulty sleeping'], 'diagnosis' => 'otitis media'],
    ['symptoms' => ['ear pain', 'loss of balance', 'ear tugging in children'], 'diagnosis' => 'otitis media'],

    // Otitis Externa
    ['symptoms' => ['ear pain', 'itching in ear canal', 'redness in ear'], 'diagnosis' => 'otitis externa'],
    ['symptoms' => ['ear pain', 'drainage from ear', 'swelling in ear'], 'diagnosis' => 'otitis externa'],
    ['symptoms' => ['ear pain', 'hearing loss', 'feeling of fullness in ear'], 'diagnosis' => 'otitis externa'],

    // Conjunctivitis
    ['symptoms' => ['red eye', 'itchy eyes', 'discharge from eyes'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['red eye', 'tearing', 'sensitivity to light'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['red eye', 'gritty feeling in eye', 'swollen eyelids'], 'diagnosis' => 'conjunctivitis'],

    // Glaucoma
    ['symptoms' => ['eye pain', 'blurred vision', 'halos around lights'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['eye pain', 'redness in eye', 'nausea'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['eye pain', 'severe headache', 'tunnel vision'], 'diagnosis' => 'glaucoma'],

    // Cataract
    ['symptoms' => ['clouded vision', 'difficulty seeing at night', 'sensitivity to light'], 'diagnosis' => 'cataract'],
    ['symptoms' => ['clouded vision', 'seeing halos around lights', 'fading of colors'], 'diagnosis' => 'cataract'],
    ['symptoms' => ['clouded vision', 'double vision in a single eye', 'frequent changes in prescription glasses'], 'diagnosis' => 'cataract'],

    // Retinal Detachment
    ['symptoms' => ['sudden flashes of light', 'floaters in vision', 'shadow over visual field'], 'diagnosis' => 'retinal detachment'],
    ['symptoms' => ['sudden flashes of light', 'curtain-like shadow over vision', 'blurred vision'], 'diagnosis' => 'retinal detachment'],
    ['symptoms' => ['sudden flashes of light', 'decreased peripheral vision', 'sudden onset of floaters'], 'diagnosis' => 'retinal detachment'],

    // Macular Degeneration
    ['symptoms' => ['blurred vision', 'difficulty recognizing faces', 'straight lines appear distorted'], 'diagnosis' => 'macular degeneration'],
    ['symptoms' => ['blurred vision', 'reduced central vision', 'need for brighter light'], 'diagnosis' => 'macular degeneration'],
    ['symptoms' => ['blurred vision', 'increased blurriness of printed words', 'difficulty adapting to low light'], 'diagnosis' => 'macular degeneration'],

    // Diabetic Retinopathy
    ['symptoms' => ['floaters', 'blurred vision', 'impaired color vision'], 'diagnosis' => 'diabetic retinopathy'],
    ['symptoms' => ['floaters', 'dark areas of vision', 'vision loss'], 'diagnosis' => 'diabetic retinopathy'],
    ['symptoms' => ['floaters', 'difficulty seeing at night', 'spots in vision'], 'diagnosis' => 'diabetic retinopathy'],

    // Hypertensive Retinopathy
    ['symptoms' => ['headache', 'vision problems', 'double vision'], 'diagnosis' => 'hypertensive retinopathy'],
    ['symptoms' => ['headache', 'burst blood vessel in eye', 'swelling of optic nerve'], 'diagnosis' => 'hypertensive retinopathy'],
    ['symptoms' => ['headache', 'reduced vision', 'eye pain'], 'diagnosis' => 'hypertensive retinopathy'],

    // Anemia
    ['symptoms' => ['fatigue', 'pale skin', 'shortness of breath'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['fatigue', 'dizziness', 'cold hands and feet'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['fatigue', 'headache', 'chest pain'], 'diagnosis' => 'anemia'],

    // Leukemia
    ['symptoms' => ['fatigue', 'fever', 'frequent infections'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'easy bruising', 'weight loss'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'swollen lymph nodes', 'bone pain'], 'diagnosis' => 'leukemia'],

    // Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'fever', 'night sweats'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'fatigue', 'weight loss'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'chills', 'persistent cough'], 'diagnosis' => 'lymphoma'],

    // Thalassemia
    ['symptoms' => ['fatigue', 'weakness', 'pale skin'], 'diagnosis' => 'thalassemia'],
    ['symptoms' => ['fatigue', 'slow growth', 'abdominal swelling'], 'diagnosis' => 'thalassemia'],
    ['symptoms' => ['fatigue', 'dark urine', 'facial bone deformities'], 'diagnosis' => 'thalassemia'],

    // Sickle Cell Disease
    ['symptoms' => ['anemia', 'episodes of pain', 'swelling of hands and feet'], 'diagnosis' => 'sickle cell disease'],
    ['symptoms' => ['anemia', 'delayed growth', 'vision problems'], 'diagnosis' => 'sickle cell disease'],
    ['symptoms' => ['anemia', 'frequent infections', 'pain in joints and bones'], 'diagnosis' => 'sickle cell disease'],

    // Hemophilia
    ['symptoms' => ['excessive bleeding', 'easy bruising', 'joint pain'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'nosebleeds', 'blood in urine'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'unexplained bleeding after surgery', 'large bruises'], 'diagnosis' => 'hemophilia'],

    // Thrombocytopenia
    ['symptoms' => ['easy bruising', 'prolonged bleeding', 'blood in urine'], 'diagnosis' => 'thrombocytopenia'],
    ['symptoms' => ['easy bruising', 'frequent nosebleeds', 'fatigue'], 'diagnosis' => 'thrombocytopenia'],
    ['symptoms' => ['easy bruising', 'petechiae', 'bleeding gums'], 'diagnosis' => 'thrombocytopenia'],

    // Deep Vein Thrombosis (DVT)
    ['symptoms' => ['swelling in one leg', 'pain in leg', 'red or discolored skin'], 'diagnosis' => 'deep vein thrombosis'],
    ['symptoms' => ['swelling in one leg', 'warmth in affected leg', 'cramping in leg'], 'diagnosis' => 'deep vein thrombosis'],
    ['symptoms' => ['swelling in one leg', 'pain that starts in calf', 'pain that gets worse when walking'], 'diagnosis' => 'deep vein thrombosis'],

    // Pulmonary Embolism
    ['symptoms' => ['shortness of breath', 'chest pain', 'cough'], 'diagnosis' => 'pulmonary embolism'],
    ['symptoms' => ['shortness of breath', 'rapid heart rate', 'lightheadedness'], 'diagnosis' => 'pulmonary embolism'],
    ['symptoms' => ['shortness of breath', 'leg pain or swelling', 'excessive sweating'], 'diagnosis' => 'pulmonary embolism'],

    // Atrial Fibrillation
    ['symptoms' => ['irregular heartbeat', 'shortness of breath', 'weakness'], 'diagnosis' => 'atrial fibrillation'],
    ['symptoms' => ['irregular heartbeat', 'palpitations', 'dizziness'], 'diagnosis' => 'atrial fibrillation'],
    ['symptoms' => ['irregular heartbeat', 'chest pain', 'fainting'], 'diagnosis' => 'atrial fibrillation'],

    // Congestive Heart Failure
    ['symptoms' => ['shortness of breath', 'fatigue', 'swelling in legs'], 'diagnosis' => 'congestive heart failure'],
    ['symptoms' => ['shortness of breath', 'rapid weight gain', 'persistent cough'], 'diagnosis' => 'congestive heart failure'],
    ['symptoms' => ['shortness of breath', 'difficulty sleeping', 'loss of appetite'], 'diagnosis' => 'congestive heart failure'],

    // Coronary Artery Disease
    ['symptoms' => ['chest pain', 'shortness of breath', 'heart attack'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['chest pain', 'fatigue', 'nausea'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['chest pain', 'pain in neck or jaw', 'sweating'], 'diagnosis' => 'coronary artery disease'],

    // Myocardial Infarction
    ['symptoms' => ['chest pain', 'shortness of breath', 'nausea'], 'diagnosis' => 'myocardial infarction'],
    ['symptoms' => ['chest pain', 'sweating', 'pain in left arm'], 'diagnosis' => 'myocardial infarction'],
    ['symptoms' => ['chest pain', 'dizziness', 'anxiety'], 'diagnosis' => 'myocardial infarction'],

    // Stroke
    ['symptoms' => ['sudden numbness', 'confusion', 'trouble speaking'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'trouble walking', 'severe headache'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'vision problems', 'difficulty understanding speech'], 'diagnosis' => 'stroke'],

    // Peripheral Artery Disease
    ['symptoms' => ['leg pain', 'coldness in lower leg', 'weak pulse'], 'diagnosis' => 'peripheral artery disease'],
    ['symptoms' => ['leg pain', 'sores on toes', 'shiny skin on legs'], 'diagnosis' => 'peripheral artery disease'],
    ['symptoms' => ['leg pain', 'pain when using arms', 'erectile dysfunction'], 'diagnosis' => 'peripheral artery disease'],

    // Aortic Aneurysm
    ['symptoms' => ['pain in abdomen', 'pulsating feeling near navel', 'back pain'], 'diagnosis' => 'aortic aneurysm'],
    ['symptoms' => ['pain in abdomen', 'shortness of breath', 'rapid heart rate'], 'diagnosis' => 'aortic aneurysm'],
    ['symptoms' => ['pain in abdomen', 'clammy skin', 'dizziness'], 'diagnosis' => 'aortic aneurysm'],

    // Cardiomyopathy
    ['symptoms' => ['fatigue', 'shortness of breath', 'swelling in legs'], 'diagnosis' => 'cardiomyopathy'],
    ['symptoms' => ['fatigue', 'dizziness', 'chest pain'], 'diagnosis' => 'cardiomyopathy'],
    ['symptoms' => ['fatigue', 'palpitations', 'fainting'], 'diagnosis' => 'cardiomyopathy'],

    // Hypertension
    ['symptoms' => ['headache', 'shortness of breath', 'nosebleeds'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'chest pain', 'vision problems'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'dizziness', 'fatigue'], 'diagnosis' => 'hypertension'],

    // Hypotension
    ['symptoms' => ['dizziness', 'fainting', 'blurred vision'], 'diagnosis' => 'hypotension'],
    ['symptoms' => ['dizziness', 'nausea', 'fatigue'], 'diagnosis' => 'hypotension'],
    ['symptoms' => ['dizziness', 'lack of concentration', 'pale skin'], 'diagnosis' => 'hypotension'],

    // Rheumatic Fever
    ['symptoms' => ['fever', 'joint pain', 'red, swollen joints'], 'diagnosis' => 'rheumatic fever'],
    ['symptoms' => ['fever', 'chest pain', 'fatigue'], 'diagnosis' => 'rheumatic fever'],
    ['symptoms' => ['fever', 'skin rash', 'shortness of breath'], 'diagnosis' => 'rheumatic fever'],

    // Infective Endocarditis
    ['symptoms' => ['fever', 'chills', 'heart murmur'], 'diagnosis' => 'infective endocarditis'],
    ['symptoms' => ['fever', 'night sweats', 'fatigue'], 'diagnosis' => 'infective endocarditis'],
    ['symptoms' => ['fever', 'shortness of breath', 'weight loss'], 'diagnosis' => 'infective endocarditis'],

    // Valvular Heart Disease
    ['symptoms' => ['shortness of breath', 'fatigue', 'palpitations'], 'diagnosis' => 'valvular heart disease'],
    ['symptoms' => ['shortness of breath', 'swelling in legs', 'chest pain'], 'diagnosis' => 'valvular heart disease'],
    ['symptoms' => ['shortness of breath', 'dizziness', 'heart murmur'], 'diagnosis' => 'valvular heart disease'],

    // Congenital Heart Disease
    ['symptoms' => ['shortness of breath', 'cyanosis', 'fatigue'], 'diagnosis' => 'congenital heart disease'],
    ['symptoms' => ['shortness of breath', 'poor weight gain', 'frequent respiratory infections'], 'diagnosis' => 'congenital heart disease'],
    ['symptoms' => ['shortness of breath', 'swelling in legs', 'irregular heartbeat'], 'diagnosis' => 'congenital heart disease'],

    // Pericarditis
    ['symptoms' => ['sharp chest pain', 'shortness of breath', 'heart palpitations'], 'diagnosis' => 'pericarditis'],
    ['symptoms' => ['sharp chest pain', 'low-grade fever', 'fatigue'], 'diagnosis' => 'pericarditis'],
    ['symptoms' => ['sharp chest pain', 'cough', 'swelling in legs'], 'diagnosis' => 'pericarditis'],

    // Myocarditis
    ['symptoms' => ['chest pain', 'fatigue', 'shortness of breath'], 'diagnosis' => 'myocarditis'],
    ['symptoms' => ['chest pain', 'rapid heart rate', 'swelling in legs'], 'diagnosis' => 'myocarditis'],
    ['symptoms' => ['chest pain', 'palpitations', 'fever'], 'diagnosis' => 'myocarditis'],

    // Atrial Septal Defect
    ['symptoms' => ['shortness of breath', 'fatigue', 'heart palpitations'], 'diagnosis' => 'atrial septal defect'],
    ['symptoms' => ['shortness of breath', 'swelling in legs', 'frequent respiratory infections'], 'diagnosis' => 'atrial septal defect'],
    ['symptoms' => ['shortness of breath', 'difficulty exercising', 'cyanosis'], 'diagnosis' => 'atrial septal defect'],

    // Ventricular Septal Defect
    ['symptoms' => ['shortness of breath', 'rapid breathing', 'frequent respiratory infections'], 'diagnosis' => 'ventricular septal defect'],
    ['symptoms' => ['shortness of breath', 'poor weight gain', 'fatigue'], 'diagnosis' => 'ventricular septal defect'],
    ['symptoms' => ['shortness of breath', 'cyanosis', 'swelling in legs'], 'diagnosis' => 'ventricular septal defect'],

    // Patent Ductus Arteriosus
    ['symptoms' => ['shortness of breath', 'rapid breathing', 'poor feeding'], 'diagnosis' => 'patent ductus arteriosus'],
    ['symptoms' => ['shortness of breath', 'fatigue', 'poor weight gain'], 'diagnosis' => 'patent ductus arteriosus'],
    ['symptoms' => ['shortness of breath', 'rapid heart rate', 'sweating'], 'diagnosis' => 'patent ductus arteriosus'],

    // Tetralogy of Fallot
    ['symptoms' => ['cyanosis', 'shortness of breath', 'loss of consciousness'], 'diagnosis' => 'tetralogy of fallot'],
    ['symptoms' => ['cyanosis', 'poor weight gain', 'clubbing of fingers'], 'diagnosis' => 'tetralogy of fallot'],
    ['symptoms' => ['cyanosis', 'irritability', 'fatigue during feeding'], 'diagnosis' => 'tetralogy of fallot'],

    // Coarctation of the Aorta
    ['symptoms' => ['high blood pressure', 'shortness of breath', 'chest pain'], 'diagnosis' => 'coarctation of the aorta'],
    ['symptoms' => ['high blood pressure', 'headache', 'muscle weakness'], 'diagnosis' => 'coarctation of the aorta'],
    ['symptoms' => ['high blood pressure', 'cold legs or feet', 'nosebleeds'], 'diagnosis' => 'coarctation of the aorta'],

    // Pulmonary Valve Stenosis
    ['symptoms' => ['shortness of breath', 'chest pain', 'fatigue'], 'diagnosis' => 'pulmonary valve stenosis'],
    ['symptoms' => ['shortness of breath', 'heart murmur', 'fainting'], 'diagnosis' => 'pulmonary valve stenosis'],
    ['symptoms' => ['shortness of breath', 'cyanosis', 'irregular heartbeat'], 'diagnosis' => 'pulmonary valve stenosis'],

    // Tricuspid Atresia
    ['symptoms' => ['cyanosis', 'shortness of breath', 'poor feeding'], 'diagnosis' => 'tricuspid atresia'],
    ['symptoms' => ['cyanosis', 'clubbing of fingers', 'slow growth'], 'diagnosis' => 'tricuspid atresia'],
    ['symptoms' => ['cyanosis', 'rapid breathing', 'fatigue during feeding'], 'diagnosis' => 'tricuspid atresia'],

    // Epstein-Barr Virus (Mononucleosis)
    ['symptoms' => ['fever', 'sore throat', 'swollen lymph nodes'], 'diagnosis' => 'epstein-barr virus'],
    ['symptoms' => ['fever', 'fatigue', 'swollen tonsils'], 'diagnosis' => 'epstein-barr virus'],
    ['symptoms' => ['fever', 'headache', 'skin rash'], 'diagnosis' => 'epstein-barr virus'],

    // Cytomegalovirus
    ['symptoms' => ['fever', 'fatigue', 'swollen glands'], 'diagnosis' => 'cytomegalovirus'],
    ['symptoms' => ['fever', 'muscle aches', 'sore throat'], 'diagnosis' => 'cytomegalovirus'],
    ['symptoms' => ['fever', 'loss of appetite', 'night sweats'], 'diagnosis' => 'cytomegalovirus'],

    // Human Immunodeficiency Virus (HIV)
    ['symptoms' => ['fever', 'fatigue', 'swollen lymph nodes'], 'diagnosis' => 'hiv'],
    ['symptoms' => ['fever', 'rash', 'muscle aches'], 'diagnosis' => 'hiv'],
    ['symptoms' => ['fever', 'mouth ulcers', 'night sweats'], 'diagnosis' => 'hiv'],

    // Herpes Simplex Virus
    ['symptoms' => ['blisters or sores', 'itching or tingling', 'painful urination'], 'diagnosis' => 'herpes simplex virus'],
    ['symptoms' => ['blisters or sores', 'flu-like symptoms', 'swollen lymph nodes'], 'diagnosis' => 'herpes simplex virus'],
    ['symptoms' => ['blisters or sores', 'burning sensation', 'headache'], 'diagnosis' => 'herpes simplex virus'],

    // Human Papillomavirus (HPV)
    ['symptoms' => ['warts', 'itching', 'discomfort'], 'diagnosis' => 'human papillomavirus'],
    ['symptoms' => ['warts', 'bleeding', 'pain during sex'], 'diagnosis' => 'human papillomavirus'],
    ['symptoms' => ['warts', 'lumps', 'irritation'], 'diagnosis' => 'human papillomavirus'],

    // Hepatitis A
    ['symptoms' => ['fever', 'fatigue', 'loss of appetite'], 'diagnosis' => 'hepatitis a'],
    ['symptoms' => ['fever', 'nausea', 'dark urine'], 'diagnosis' => 'hepatitis a'],
    ['symptoms' => ['fever', 'jaundice', 'abdominal pain'], 'diagnosis' => 'hepatitis a'],

    // Hepatitis B
    ['symptoms' => ['fever', 'fatigue', 'loss of appetite'], 'diagnosis' => 'hepatitis b'],
    ['symptoms' => ['fever', 'nausea', 'dark urine'], 'diagnosis' => 'hepatitis b'],
    ['symptoms' => ['fever', 'jaundice', 'abdominal pain'], 'diagnosis' => 'hepatitis b'],

    // Hepatitis C
    ['symptoms' => ['fever', 'fatigue', 'loss of appetite'], 'diagnosis' => 'hepatitis c'],
    ['symptoms' => ['fever', 'nausea', 'dark urine'], 'diagnosis' => 'hepatitis c'],
    ['symptoms' => ['fever', 'jaundice', 'abdominal pain'], 'diagnosis' => 'hepatitis c'],

    // Dengue Fever
    ['symptoms' => ['fever', 'headache', 'muscle pain'], 'diagnosis' => 'dengue fever'],
    ['symptoms' => ['fever', 'rash', 'joint pain'], 'diagnosis' => 'dengue fever'],
    ['symptoms' => ['fever', 'nausea', 'eye pain'], 'diagnosis' => 'dengue fever'],

    // Malaria
    ['symptoms' => ['fever', 'chills', 'sweats'], 'diagnosis' => 'malaria'],
    ['symptoms' => ['fever', 'headache', 'nausea'], 'diagnosis' => 'malaria'],
    ['symptoms' => ['fever', 'fatigue', 'muscle pain'], 'diagnosis' => 'malaria'],

    // Lyme Disease
    ['symptoms' => ['fever', 'fatigue', 'bullseye rash'], 'diagnosis' => 'lyme disease'],
    ['symptoms' => ['fever', 'headache', 'joint pain'], 'diagnosis' => 'lyme disease'],
    ['symptoms' => ['fever', 'neck stiffness', 'muscle pain'], 'diagnosis' => 'lyme disease'],

    // Toxoplasmosis
    ['symptoms' => ['fever', 'muscle pain', 'swollen lymph nodes'], 'diagnosis' => 'toxoplasmosis'],
    ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'toxoplasmosis'],
    ['symptoms' => ['fever', 'sore throat', 'blurred vision'], 'diagnosis' => 'toxoplasmosis'],

    // Zika Virus
    ['symptoms' => ['fever', 'rash', 'joint pain'], 'diagnosis' => 'zika virus'],
    ['symptoms' => ['fever', 'headache', 'red eyes'], 'diagnosis' => 'zika virus'],
    ['symptoms' => ['fever', 'muscle pain', 'fatigue'], 'diagnosis' => 'zika virus'],

    // Yellow Fever
    ['symptoms' => ['fever', 'headache', 'nausea'], 'diagnosis' => 'yellow fever'],
    ['symptoms' => ['fever', 'jaundice', 'muscle pain'], 'diagnosis' => 'yellow fever'],
    ['symptoms' => ['fever', 'fatigue', 'bleeding'], 'diagnosis' => 'yellow fever'],

    // West Nile Virus
    ['symptoms' => ['fever', 'headache', 'body aches'], 'diagnosis' => 'west nile virus'],
    ['symptoms' => ['fever', 'fatigue', 'skin rash'], 'diagnosis' => 'west nile virus'],
    ['symptoms' => ['fever', 'nausea', 'swollen lymph nodes'], 'diagnosis' => 'west nile virus'],

    // Rabies
    ['symptoms' => ['fever', 'headache', 'muscle weakness'], 'diagnosis' => 'rabies'],
    ['symptoms' => ['fever', 'anxiety', 'difficulty swallowing'], 'diagnosis' => 'rabies'],
    ['symptoms' => ['fever', 'hallucinations', 'excessive salivation'], 'diagnosis' => 'rabies'],

    // Influenza
    ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['fever', 'body aches', 'chills'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'influenza'],

    // Measles
    ['symptoms' => ['fever', 'rash', 'runny nose'], 'diagnosis' => 'measles'],
    ['symptoms' => ['fever', 'cough', 'red eyes'], 'diagnosis' => 'measles'],
    ['symptoms' => ['fever', 'white spots in mouth', 'sore throat'], 'diagnosis' => 'measles'],

    // Mumps
    ['symptoms' => ['fever', 'swollen salivary glands', 'headache'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['fever', 'muscle aches', 'fatigue'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['fever', 'loss of appetite', 'pain while chewing'], 'diagnosis' => 'mumps'],

    // Rubella
    ['symptoms' => ['fever', 'rash', 'sore throat'], 'diagnosis' => 'rubella'],
    ['symptoms' => ['fever', 'swollen lymph nodes', 'joint pain'], 'diagnosis' => 'rubella'],
    ['symptoms' => ['fever', 'headache', 'red eyes'], 'diagnosis' => 'rubella'],

    // Chickenpox
    ['symptoms' => ['fever', 'rash', 'itching'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['fever', 'fatigue', 'loss of appetite'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['fever', 'headache', 'muscle pain'], 'diagnosis' => 'chickenpox'],

    // Smallpox
    ['symptoms' => ['fever', 'rash', 'headache'], 'diagnosis' => 'smallpox'],
    ['symptoms' => ['fever', 'back pain', 'vomiting'], 'diagnosis' => 'smallpox'],
    ['symptoms' => ['fever', 'fatigue', 'severe abdominal pain'], 'diagnosis' => 'smallpox'],

    // Polio
    ['symptoms' => ['fever', 'fatigue', 'stiff neck'], 'diagnosis' => 'polio'],
    ['symptoms' => ['fever', 'headache', 'muscle weakness'], 'diagnosis' => 'polio'],
    ['symptoms' => ['fever', 'nausea', 'paralysis'], 'diagnosis' => 'polio'],

    // SARS (Severe Acute Respiratory Syndrome)
    ['symptoms' => ['fever', 'dry cough', 'difficulty breathing'], 'diagnosis' => 'sars'],
    ['symptoms' => ['fever', 'chills', 'muscle pain'], 'diagnosis' => 'sars'],
    ['symptoms' => ['fever', 'headache', 'sore throat'], 'diagnosis' => 'sars'],

    // MERS (Middle East Respiratory Syndrome)
    ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'mers'],
    ['symptoms' => ['fever', 'chills', 'sore throat'], 'diagnosis' => 'mers'],
    ['symptoms' => ['fever', 'muscle pain', 'nausea'], 'diagnosis' => 'mers'],

    // COVID-19
    ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'covid-19'],
    ['symptoms' => ['fever', 'loss of taste', 'muscle pain'], 'diagnosis' => 'covid-19'],
    ['symptoms' => ['fever', 'headache', 'sore throat'], 'diagnosis' => 'covid-19'],

    // Tuberculosis
    ['symptoms' => ['fever', 'cough', 'night sweats'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['fever', 'weight loss', 'chest pain'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['fever', 'fatigue', 'coughing up blood'], 'diagnosis' => 'tuberculosis'],

    // Legionnaires' Disease
    ['symptoms' => ['fever', 'cough', 'muscle pain'], 'diagnosis' => 'legionnaires disease'],
    ['symptoms' => ['fever', 'shortness of breath', 'headache'], 'diagnosis' => 'legionnaires disease'],
    ['symptoms' => ['fever', 'chest pain', 'confusion'], 'diagnosis' => 'legionnaires disease'],

    // Meningitis
    ['symptoms' => ['fever', 'headache', 'stiff neck'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['fever', 'nausea', 'sensitivity to light'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['fever', 'confusion', 'skin rash'], 'diagnosis' => 'meningitis'],

    // Encephalitis
    ['symptoms' => ['fever', 'headache', 'confusion'], 'diagnosis' => 'encephalitis'],
    ['symptoms' => ['fever', 'seizures', 'loss of consciousness'], 'diagnosis' => 'encephalitis'],
    ['symptoms' => ['fever', 'personality changes', 'nausea'], 'diagnosis' => 'encephalitis'],

    // Pneumonia
    ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['fever', 'chest pain', 'fatigue'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['fever', 'sweating', 'nausea'], 'diagnosis' => 'pneumonia'],

    // Bronchitis
    ['symptoms' => ['cough', 'fatigue', 'shortness of breath'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'chest discomfort', 'wheezing'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['cough', 'low-grade fever', 'sore throat'], 'diagnosis' => 'bronchitis'],

    // Sinusitis
    ['symptoms' => ['facial pain', 'nasal congestion', 'headache'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['facial pain', 'runny nose', 'toothache'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['facial pain', 'postnasal drip', 'loss of smell'], 'diagnosis' => 'sinusitis'],

    // Otitis Media
    ['symptoms' => ['ear pain', 'difficulty hearing', 'fever'], 'diagnosis' => 'otitis media'],
    ['symptoms' => ['ear pain', 'fluid drainage', 'irritability'], 'diagnosis' => 'otitis media'],
    ['symptoms' => ['ear pain', 'difficulty sleeping', 'loss of balance'], 'diagnosis' => 'otitis media'],

    // Cellulitis
    ['symptoms' => ['redness', 'swelling', 'warmth'], 'diagnosis' => 'cellulitis'],
    ['symptoms' => ['redness', 'pain', 'fever'], 'diagnosis' => 'cellulitis'],
    ['symptoms' => ['redness', 'blisters', 'tenderness'], 'diagnosis' => 'cellulitis'],

    // Impetigo
    ['symptoms' => ['red sores', 'itching', 'blisters'], 'diagnosis' => 'impetigo'],
    ['symptoms' => ['red sores', 'oozing fluid', 'yellow crust'], 'diagnosis' => 'impetigo'],
    ['symptoms' => ['red sores', 'swelling', 'pain'], 'diagnosis' => 'impetigo'],

    // Erysipelas
    ['symptoms' => ['redness', 'swelling', 'fever'], 'diagnosis' => 'erysipelas'],
    ['symptoms' => ['redness', 'pain', 'blisters'], 'diagnosis' => 'erysipelas'],
    ['symptoms' => ['redness', 'swollen lymph nodes', 'chills'], 'diagnosis' => 'erysipelas'],

    // Acne
    ['symptoms' => ['pimples', 'blackheads', 'whiteheads'], 'diagnosis' => 'acne'],
    ['symptoms' => ['pimples', 'redness', 'swelling'], 'diagnosis' => 'acne'],
    ['symptoms' => ['pimples', 'pus-filled lumps', 'scarring'], 'diagnosis' => 'acne'],

    // Psoriasis
    ['symptoms' => ['red patches', 'scales', 'itching'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches', 'cracking skin', 'burning'], 'diagnosis' => 'psoriasis'],
    ['symptoms' => ['red patches', 'dry skin', 'joint pain'], 'diagnosis' => 'psoriasis'],

    // Eczema (Atopic Dermatitis)
    ['symptoms' => ['itching', 'red patches', 'dry skin'], 'diagnosis' => 'eczema'],
    ['symptoms' => ['itching', 'swelling', 'crusting'], 'diagnosis' => 'eczema'],
    ['symptoms' => ['itching', 'blisters', 'scaling'], 'diagnosis' => 'eczema'],

    // Rosacea
    ['symptoms' => ['redness', 'visible blood vessels', 'bumps'], 'diagnosis' => 'rosacea'],
    ['symptoms' => ['redness', 'swelling', 'burning'], 'diagnosis' => 'rosacea'],
    ['symptoms' => ['redness', 'eye irritation', 'thickened skin'], 'diagnosis' => 'rosacea'],

    // Dermatitis
    ['symptoms' => ['redness', 'itching', 'swelling'], 'diagnosis' => 'dermatitis'],
    ['symptoms' => ['redness', 'crusting', 'blisters'], 'diagnosis' => 'dermatitis'],
    ['symptoms' => ['redness', 'scaling', 'oozing'], 'diagnosis' => 'dermatitis'],

    // Seborrheic Dermatitis
    ['symptoms' => ['scaly patches', 'dandruff', 'itching'], 'diagnosis' => 'seborrheic dermatitis'],
    ['symptoms' => ['scaly patches', 'red skin', 'flaking'], 'diagnosis' => 'seborrheic dermatitis'],
    ['symptoms' => ['scaly patches', 'oily skin', 'burning'], 'diagnosis' => 'seborrheic dermatitis'],

    // Varicose Veins
    ['symptoms' => ['swollen veins', 'aching legs', 'itching'], 'diagnosis' => 'varicose veins'],
    ['symptoms' => ['swollen veins', 'leg cramps', 'swelling'], 'diagnosis' => 'varicose veins'],
    ['symptoms' => ['swollen veins', 'skin discoloration', 'restless legs'], 'diagnosis' => 'varicose veins'],

    // Deep Vein Thrombosis (DVT)
    ['symptoms' => ['swelling', 'pain', 'warmth'], 'diagnosis' => 'deep vein thrombosis'],
    ['symptoms' => ['swelling', 'redness', 'cramping'], 'diagnosis' => 'deep vein thrombosis'],
    ['symptoms' => ['swelling', 'tenderness', 'discoloration'], 'diagnosis' => 'deep vein thrombosis'],

    // Peripheral Artery Disease (PAD)
    ['symptoms' => ['leg pain', 'cramping', 'weakness'], 'diagnosis' => 'peripheral artery disease'],
    ['symptoms' => ['leg pain', 'numbness', 'coldness'], 'diagnosis' => 'peripheral artery disease'],
    ['symptoms' => ['leg pain', 'slow healing', 'shiny skin'], 'diagnosis' => 'peripheral artery disease'],

    // Myocardial Infarction (Heart Attack)
    ['symptoms' => ['chest pain', 'shortness of breath', 'sweating'], 'diagnosis' => 'myocardial infarction'],
    ['symptoms' => ['chest pain', 'nausea', 'lightheadedness'], 'diagnosis' => 'myocardial infarction'],
    ['symptoms' => ['chest pain', 'pain in arm or shoulder', 'fatigue'], 'diagnosis' => 'myocardial infarction'],

    // Hypertension (High Blood Pressure)
    ['symptoms' => ['headache', 'shortness of breath', 'nosebleeds'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'chest pain', 'dizziness'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['headache', 'blurred vision', 'fatigue'], 'diagnosis' => 'hypertension'],

    // Stroke
    ['symptoms' => ['sudden numbness', 'confusion', 'trouble speaking'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'vision problems', 'trouble walking'], 'diagnosis' => 'stroke'],
    ['symptoms' => ['sudden numbness', 'severe headache', 'dizziness'], 'diagnosis' => 'stroke'],

    // Alzheimer's Disease
    ['symptoms' => ['memory loss', 'confusion', 'difficulty concentrating'], 'diagnosis' => 'alzheimers disease'],
    ['symptoms' => ['memory loss', 'difficulty finding words', 'personality changes'], 'diagnosis' => 'alzheimers disease'],
    ['symptoms' => ['memory loss', 'poor judgment', 'mood swings'], 'diagnosis' => 'alzheimers disease'],

    // Parkinson's Disease
    ['symptoms' => ['tremor', 'slow movement', 'stiffness'], 'diagnosis' => 'parkinsons disease'],
    ['symptoms' => ['tremor', 'balance problems', 'speech changes'], 'diagnosis' => 'parkinsons disease'],
    ['symptoms' => ['tremor', 'writing changes', 'facial masking'], 'diagnosis' => 'parkinsons disease'],

    // Multiple Sclerosis
    ['symptoms' => ['numbness', 'fatigue', 'muscle weakness'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['numbness', 'vision problems', 'dizziness'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['numbness', 'coordination issues', 'bladder problems'], 'diagnosis' => 'multiple sclerosis'],

    // Amyotrophic Lateral Sclerosis (ALS)
    ['symptoms' => ['muscle weakness', 'difficulty speaking', 'difficulty swallowing'], 'diagnosis' => 'als'],
    ['symptoms' => ['muscle weakness', 'cramping', 'difficulty breathing'], 'diagnosis' => 'als'],
    ['symptoms' => ['muscle weakness', 'twitching', 'difficulty walking'], 'diagnosis' => 'als'],

    // Epilepsy
    ['symptoms' => ['seizures', 'confusion', 'staring spells'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['seizures', 'loss of consciousness', 'muscle stiffness'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['seizures', 'mood changes', 'sensory disturbances'], 'diagnosis' => 'epilepsy'],

    // Migraine
    ['symptoms' => ['headache', 'nausea', 'sensitivity to light'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache', 'vomiting', 'sensitivity to sound'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache', 'visual disturbances', 'throbbing pain'], 'diagnosis' => 'migraine'],

    // Depression
    ['symptoms' => ['persistent sadness', 'loss of interest', 'fatigue'], 'diagnosis' => 'depression'],
    ['symptoms' => ['persistent sadness', 'difficulty concentrating', 'changes in appetite'], 'diagnosis' => 'depression'],
    ['symptoms' => ['persistent sadness', 'feelings of worthlessness', 'sleep disturbances'], 'diagnosis' => 'depression'],

    // Anxiety
    ['symptoms' => ['excessive worry', 'restlessness', 'fatigue'], 'diagnosis' => 'anxiety'],
    ['symptoms' => ['excessive worry', 'irritability', 'muscle tension'], 'diagnosis' => 'anxiety'],
    ['symptoms' => ['excessive worry', 'difficulty concentrating', 'sleep disturbances'], 'diagnosis' => 'anxiety'],

    // Schizophrenia
    ['symptoms' => ['hallucinations', 'delusions', 'disorganized speech'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['hallucinations', 'social withdrawal', 'flat affect'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['hallucinations', 'thought disorder', 'movement disorders'], 'diagnosis' => 'schizophrenia'],

    // Obsessive-Compulsive Disorder (OCD)
    ['symptoms' => ['obsessions', 'compulsions', 'anxiety'], 'diagnosis' => 'ocd'],
    ['symptoms' => ['obsessions', 'compulsions', 'ritualistic behavior'], 'diagnosis' => 'ocd'],
    ['symptoms' => ['obsessions', 'compulsions', 'distress'], 'diagnosis' => 'ocd'],

    // Bipolar Disorder
    ['symptoms' => ['mood swings', 'depression', 'mania'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['mood swings', 'irritability', 'racing thoughts'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['mood swings', 'impulsivity', 'sleep disturbances'], 'diagnosis' => 'bipolar disorder'],

    // Post-Traumatic Stress Disorder (PTSD)
    ['symptoms' => ['flashbacks', 'nightmares', 'anxiety'], 'diagnosis' => 'ptsd'],
    ['symptoms' => ['flashbacks', 'hypervigilance', 'emotional numbness'], 'diagnosis' => 'ptsd'],
    ['symptoms' => ['flashbacks', 'avoidance', 'difficulty concentrating'], 'diagnosis' => 'ptsd'],

    // Attention Deficit Hyperactivity Disorder (ADHD)
    ['symptoms' => ['inattention', 'hyperactivity', 'impulsivity'], 'diagnosis' => 'adhd'],
    ['symptoms' => ['inattention', 'difficulty organizing', 'forgetfulness'], 'diagnosis' => 'adhd'],
    ['symptoms' => ['inattention', 'fidgeting', 'talking excessively'], 'diagnosis' => 'adhd'],

    // Autism Spectrum Disorder
    ['symptoms' => ['social challenges', 'repetitive behaviors', 'communication difficulties'], 'diagnosis' => 'autism spectrum disorder'],
    ['symptoms' => ['social challenges', 'restricted interests', 'sensory sensitivities'], 'diagnosis' => 'autism spectrum disorder'],
    ['symptoms' => ['social challenges', 'language delay', 'intellectual disability'], 'diagnosis' => 'autism spectrum disorder'],

    // Down Syndrome
    ['symptoms' => ['intellectual disability', 'delayed development', 'distinct facial features'], 'diagnosis' => 'down syndrome'],
    ['symptoms' => ['intellectual disability', 'hypotonia', 'short stature'], 'diagnosis' => 'down syndrome'],
    ['symptoms' => ['intellectual disability', 'congenital heart defects', 'vision problems'], 'diagnosis' => 'down syndrome'],

    // Cystic Fibrosis
    ['symptoms' => ['chronic cough', 'lung infections', 'difficulty breathing'], 'diagnosis' => 'cystic fibrosis'],
    ['symptoms' => ['chronic cough', 'poor growth', 'salty skin'], 'diagnosis' => 'cystic fibrosis'],
    ['symptoms' => ['chronic cough', 'greasy stools', 'clubbing of fingers'], 'diagnosis' => 'cystic fibrosis'],

    // Sickle Cell Disease
    ['symptoms' => ['pain episodes', 'anemia', 'swelling in hands and feet'], 'diagnosis' => 'sickle cell disease'],
    ['symptoms' => ['pain episodes', 'fatigue', 'delayed growth'], 'diagnosis' => 'sickle cell disease'],
    ['symptoms' => ['pain episodes', 'jaundice', 'frequent infections'], 'diagnosis' => 'sickle cell disease'],

    // Hemophilia
    ['symptoms' => ['excessive bleeding', 'easy bruising', 'joint pain'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'prolonged bleeding', 'nosebleeds'], 'diagnosis' => 'hemophilia'],
    ['symptoms' => ['excessive bleeding', 'blood in urine', 'swollen joints'], 'diagnosis' => 'hemophilia'],

    // Thalassemia
    ['symptoms' => ['fatigue', 'weakness', 'pale skin'], 'diagnosis' => 'thalassemia'],
    ['symptoms' => ['fatigue', 'delayed growth', 'dark urine'], 'diagnosis' => 'thalassemia'],
    ['symptoms' => ['fatigue', 'bone deformities', 'yellowish skin'], 'diagnosis' => 'thalassemia'],

    // Leukemia
    ['symptoms' => ['fatigue', 'frequent infections', 'easy bruising'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'weight loss', 'swollen lymph nodes'], 'diagnosis' => 'leukemia'],
    ['symptoms' => ['fatigue', 'bleeding gums', 'night sweats'], 'diagnosis' => 'leukemia'],

    // Lymphoma
    ['symptoms' => ['swollen lymph nodes', 'fatigue', 'night sweats'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'weight loss', 'itching'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'fever', 'chest pain'], 'diagnosis' => 'lymphoma'],

    // Multiple Myeloma
    ['symptoms' => ['bone pain', 'fatigue', 'frequent infections'], 'diagnosis' => 'multiple myeloma'],
    ['symptoms' => ['bone pain', 'nausea', 'anemia'], 'diagnosis' => 'multiple myeloma'],
    ['symptoms' => ['bone pain', 'constipation', 'weight loss'], 'diagnosis' => 'multiple myeloma'],

    // Prostate Cancer
    ['symptoms' => ['difficulty urinating', 'blood in urine', 'bone pain'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['difficulty urinating', 'weak urine flow', 'erectile dysfunction'], 'diagnosis' => 'prostate cancer'],
    ['symptoms' => ['difficulty urinating', 'pelvic pain', 'weight loss'], 'diagnosis' => 'prostate cancer'],

    // Breast Cancer
    ['symptoms' => ['lump in breast', 'nipple discharge', 'breast pain'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'nipple inversion', 'skin changes'], 'diagnosis' => 'breast cancer'],
    ['symptoms' => ['lump in breast', 'swelling in breast', 'weight loss'], 'diagnosis' => 'breast cancer'],

    // Lung Cancer
    ['symptoms' => ['persistent cough', 'shortness of breath', 'chest pain'], 'diagnosis' => 'lung cancer'],
    ['symptoms' => ['persistent cough', 'coughing up blood', 'weight loss'], 'diagnosis' => 'lung cancer'],
    ['symptoms' => ['persistent cough', 'hoarseness', 'fatigue'], 'diagnosis' => 'lung cancer'],

    // Colorectal Cancer
    ['symptoms' => ['blood in stool', 'abdominal pain', 'weight loss'], 'diagnosis' => 'colorectal cancer'],
    ['symptoms' => ['blood in stool', 'change in bowel habits', 'fatigue'], 'diagnosis' => 'colorectal cancer'],
    ['symptoms' => ['blood in stool', 'cramping', 'iron deficiency anemia'], 'diagnosis' => 'colorectal cancer'],

    // Liver Cancer
    ['symptoms' => ['abdominal pain', 'jaundice', 'weight loss'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'fatigue'], 'diagnosis' => 'liver cancer'],
    ['symptoms' => ['abdominal pain', 'swelling in abdomen', 'loss of appetite'], 'diagnosis' => 'liver cancer'],

    // Pancreatic Cancer
    ['symptoms' => ['abdominal pain', 'weight loss', 'jaundice'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'loss of appetite'], 'diagnosis' => 'pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'blood clots', 'depression'], 'diagnosis' => 'pancreatic cancer'],

    // Bladder Cancer
    ['symptoms' => ['blood in urine', 'frequent urination', 'pelvic pain'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['blood in urine', 'painful urination', 'back pain'], 'diagnosis' => 'bladder cancer'],
    ['symptoms' => ['blood in urine', 'weight loss', 'fatigue'], 'diagnosis' => 'bladder cancer'],

    // Kidney Cancer
    ['symptoms' => ['blood in urine', 'abdominal pain', 'weight loss'], 'diagnosis' => 'kidney cancer'],
    ['symptoms' => ['blood in urine', 'back pain', 'fatigue'], 'diagnosis' => 'kidney cancer'],
    ['symptoms' => ['blood in urine', 'fever', 'swelling in legs'], 'diagnosis' => 'kidney cancer'],

    // Skin Cancer
    ['symptoms' => ['new growths', 'changes in moles', 'sores that don\'t heal'], 'diagnosis' => 'skin cancer'],
    ['symptoms' => ['new growths', 'itching or tenderness', 'scaly patches'], 'diagnosis' => 'skin cancer'],
    ['symptoms' => ['new growths', 'bleeding moles', 'lumps'], 'diagnosis' => 'skin cancer'],

    // Leukemia (Childhood)
    ['symptoms' => ['fatigue', 'frequent infections', 'easy bruising'], 'diagnosis' => 'childhood leukemia'],
    ['symptoms' => ['fatigue', 'weight loss', 'swollen lymph nodes'], 'diagnosis' => 'childhood leukemia'],
    ['symptoms' => ['fatigue', 'bleeding gums', 'night sweats'], 'diagnosis' => 'childhood leukemia'],

    // Autism Spectrum Disorder (Childhood)
    ['symptoms' => ['social challenges', 'repetitive behaviors', 'communication difficulties'], 'diagnosis' => 'childhood autism spectrum disorder'],
    ['symptoms' => ['social challenges', 'restricted interests', 'sensory sensitivities'], 'diagnosis' => 'childhood autism spectrum disorder'],
    ['symptoms' => ['social challenges', 'language delay', 'intellectual disability'], 'diagnosis' => 'childhood autism spectrum disorder'],

    // Down Syndrome (Childhood)
    ['symptoms' => ['intellectual disability', 'delayed development', 'distinct facial features'], 'diagnosis' => 'childhood down syndrome'],
    ['symptoms' => ['intellectual disability', 'hypotonia', 'short stature'], 'diagnosis' => 'childhood down syndrome'],
    ['symptoms' => ['intellectual disability', 'congenital heart defects', 'vision problems'], 'diagnosis' => 'childhood down syndrome'],

    // Cystic Fibrosis (Childhood)
    ['symptoms' => ['chronic cough', 'lung infections', 'difficulty breathing'], 'diagnosis' => 'childhood cystic fibrosis'],
    ['symptoms' => ['chronic cough', 'poor growth', 'salty skin'], 'diagnosis' => 'childhood cystic fibrosis'],
    ['symptoms' => ['chronic cough', 'greasy stools', 'clubbing of fingers'], 'diagnosis' => 'childhood cystic fibrosis'],

    // Sickle Cell Disease (Childhood)
    ['symptoms' => ['pain episodes', 'anemia', 'swelling in hands and feet'], 'diagnosis' => 'childhood sickle cell disease'],
    ['symptoms' => ['pain episodes', 'fatigue', 'delayed growth'], 'diagnosis' => 'childhood sickle cell disease'],
    ['symptoms' => ['pain episodes', 'jaundice', 'frequent infections'], 'diagnosis' => 'childhood sickle cell disease'],

    // Hemophilia (Childhood)
    ['symptoms' => ['excessive bleeding', 'easy bruising', 'joint pain'], 'diagnosis' => 'childhood hemophilia'],
    ['symptoms' => ['excessive bleeding', 'prolonged bleeding', 'nosebleeds'], 'diagnosis' => 'childhood hemophilia'],
    ['symptoms' => ['excessive bleeding', 'blood in urine', 'swollen joints'], 'diagnosis' => 'childhood hemophilia'],

    // Thalassemia (Childhood)
    ['symptoms' => ['fatigue', 'weakness', 'pale skin'], 'diagnosis' => 'childhood thalassemia'],
    ['symptoms' => ['fatigue', 'delayed growth', 'dark urine'], 'diagnosis' => 'childhood thalassemia'],
    ['symptoms' => ['fatigue', 'bone deformities', 'yellowish skin'], 'diagnosis' => 'childhood thalassemia'],

    // Leukemia (Childhood)
    ['symptoms' => ['fatigue', 'frequent infections', 'easy bruising'], 'diagnosis' => 'childhood leukemia'],
    ['symptoms' => ['fatigue', 'weight loss', 'swollen lymph nodes'], 'diagnosis' => 'childhood leukemia'],
    ['symptoms' => ['fatigue', 'bleeding gums', 'night sweats'], 'diagnosis' => 'childhood leukemia'],

    // Lymphoma (Childhood)
    ['symptoms' => ['swollen lymph nodes', 'fatigue', 'night sweats'], 'diagnosis' => 'childhood lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'weight loss', 'itching'], 'diagnosis' => 'childhood lymphoma'],
    ['symptoms' => ['swollen lymph nodes', 'fever', 'chest pain'], 'diagnosis' => 'childhood lymphoma'],

    // Multiple Myeloma (Childhood)
    ['symptoms' => ['bone pain', 'fatigue', 'frequent infections'], 'diagnosis' => 'childhood multiple myeloma'],
    ['symptoms' => ['bone pain', 'nausea', 'anemia'], 'diagnosis' => 'childhood multiple myeloma'],
    ['symptoms' => ['bone pain', 'constipation', 'weight loss'], 'diagnosis' => 'childhood multiple myeloma'],

    // Prostate Cancer (Childhood)
    ['symptoms' => ['difficulty urinating', 'blood in urine', 'bone pain'], 'diagnosis' => 'childhood prostate cancer'],
    ['symptoms' => ['difficulty urinating', 'weak urine flow', 'erectile dysfunction'], 'diagnosis' => 'childhood prostate cancer'],
    ['symptoms' => ['difficulty urinating', 'pelvic pain', 'weight loss'], 'diagnosis' => 'childhood prostate cancer'],

    // Breast Cancer (Childhood)
    ['symptoms' => ['lump in breast', 'nipple discharge', 'breast pain'], 'diagnosis' => 'childhood breast cancer'],
    ['symptoms' => ['lump in breast', 'nipple inversion', 'skin changes'], 'diagnosis' => 'childhood breast cancer'],
    ['symptoms' => ['lump in breast', 'swelling in breast', 'weight loss'], 'diagnosis' => 'childhood breast cancer'],

    // Lung Cancer (Childhood)
    ['symptoms' => ['persistent cough', 'shortness of breath', 'chest pain'], 'diagnosis' => 'childhood lung cancer'],
    ['symptoms' => ['persistent cough', 'coughing up blood', 'weight loss'], 'diagnosis' => 'childhood lung cancer'],
    ['symptoms' => ['persistent cough', 'hoarseness', 'fatigue'], 'diagnosis' => 'childhood lung cancer'],

    // Colorectal Cancer (Childhood)
    ['symptoms' => ['blood in stool', 'abdominal pain', 'weight loss'], 'diagnosis' => 'childhood colorectal cancer'],
    ['symptoms' => ['blood in stool', 'change in bowel habits', 'fatigue'], 'diagnosis' => 'childhood colorectal cancer'],
    ['symptoms' => ['blood in stool', 'cramping', 'iron deficiency anemia'], 'diagnosis' => 'childhood colorectal cancer'],

    // Liver Cancer (Childhood)
    ['symptoms' => ['abdominal pain', 'jaundice', 'weight loss'], 'diagnosis' => 'childhood liver cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'fatigue'], 'diagnosis' => 'childhood liver cancer'],
    ['symptoms' => ['abdominal pain', 'swelling in abdomen', 'loss of appetite'], 'diagnosis' => 'childhood liver cancer'],

    // Pancreatic Cancer (Childhood)
    ['symptoms' => ['abdominal pain', 'weight loss', 'jaundice'], 'diagnosis' => 'childhood pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'nausea', 'loss of appetite'], 'diagnosis' => 'childhood pancreatic cancer'],
    ['symptoms' => ['abdominal pain', 'blood clots', 'depression'], 'diagnosis' => 'childhood pancreatic cancer'],

    // Bladder Cancer (Childhood)
    ['symptoms' => ['blood in urine', 'frequent urination', 'pelvic pain'], 'diagnosis' => 'childhood bladder cancer'],
    ['symptoms' => ['blood in urine', 'painful urination', 'back pain'], 'diagnosis' => 'childhood bladder cancer'],
    ['symptoms' => ['blood in urine', 'weight loss', 'fatigue'], 'diagnosis' => 'childhood bladder cancer'],

    // Kidney Cancer (Childhood)
    ['symptoms' => ['blood in urine', 'abdominal pain', 'weight loss'], 'diagnosis' => 'childhood kidney cancer'],
    ['symptoms' => ['blood in urine', 'back pain', 'fatigue'], 'diagnosis' => 'childhood kidney cancer'],
    ['symptoms' => ['blood in urine', 'fever', 'swelling in legs'], 'diagnosis' => 'childhood kidney cancer'],

    // Skin Cancer (Childhood)
    ['symptoms' => ['new growths', 'changes in moles', 'sores that don\'t heal'], 'diagnosis' => 'childhood skin cancer'],
    ['symptoms' => ['new growths', 'itching or tenderness', 'scaly patches'], 'diagnosis' => 'childhood skin cancer'],
    ['symptoms' => ['new growths', 'bleeding moles', 'lumps'], 'diagnosis' => 'childhood skin cancer'],

    // Appendicitis
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'nausea'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['abdominal pain', 'fever', 'vomiting'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['abdominal pain', 'low-grade fever', 'painful urination'], 'diagnosis' => 'appendicitis'],

    // Pneumonia
    ['symptoms' => ['cough', 'fever', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chest pain', 'fatigue'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chills', 'rapid breathing'], 'diagnosis' => 'pneumonia'],

    // Urinary Tract Infection (UTI)
    ['symptoms' => ['burning sensation during urination', 'frequent urination', 'lower abdominal pain'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['cloudy or bloody urine', 'strong-smelling urine', 'fever'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['urinary urgency', 'fatigue', 'confusion'], 'diagnosis' => 'urinary tract infection'],

    // Migraine
    ['symptoms' => ['severe headache', 'nausea', 'sensitivity to light'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['throbbing pain', 'visual disturbances', 'dizziness'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['headache worsened by physical activity', 'aura', 'vomiting'], 'diagnosis' => 'migraine'],

    // Gastroesophageal Reflux Disease (GERD)
    ['symptoms' => ['heartburn', 'acid reflux', 'chest pain'], 'diagnosis' => 'GERD'],
    ['symptoms' => ['regurgitation of food or sour liquid', 'difficulty swallowing', 'chronic cough'], 'diagnosis' => 'GERD'],
    ['symptoms' => ['bitter taste in mouth', 'bad breath', 'tooth erosion'], 'diagnosis' => 'GERD'],

    // Osteoarthritis
    ['symptoms' => ['joint pain', 'stiffness', 'swelling'], 'diagnosis' => 'osteoarthritis'],
    ['symptoms' => ['joint tenderness', 'loss of flexibility', 'bone spurs'], 'diagnosis' => 'osteoarthritis'],
    ['symptoms' => ['grating sensation', 'joint instability', 'muscle weakness'], 'diagnosis' => 'osteoarthritis'],

    // Asthma
    ['symptoms' => ['shortness of breath', 'wheezing', 'chest tightness'], 'diagnosis' => 'asthma'],
    ['symptoms' => ['coughing', 'difficulty sleeping due to coughing', 'fatigue'], 'diagnosis' => 'asthma'],
    ['symptoms' => ['rapid breathing', 'anxiety', 'pale or sweaty face'], 'diagnosis' => 'asthma'],

    // Hypertension (High Blood Pressure)
    ['symptoms' => ['headaches', 'shortness of breath', 'nosebleeds'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['vision changes', 'chest pain', 'fatigue'], 'diagnosis' => 'hypertension'],
    ['symptoms' => ['irregular heartbeat', 'blood in urine', 'pounding in chest, neck, or ears'], 'diagnosis' => 'hypertension'],

    // Type 2 Diabetes
    ['symptoms' => ['increased thirst', 'frequent urination', 'fatigue'], 'diagnosis' => 'type 2 diabetes'],
    ['symptoms' => ['unintended weight loss', 'slow-healing sores', 'frequent infections'], 'diagnosis' => 'type 2 diabetes'],
    ['symptoms' => ['blurred vision', 'tingling or numbness in hands or feet', 'dry, itchy skin'], 'diagnosis' => 'type 2 diabetes'],

    // Influenza (Flu)
    ['symptoms' => ['fever', 'chills', 'muscle aches'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['sore throat', 'runny or stuffy nose', 'headache'], 'diagnosis' => 'influenza'],
    ['symptoms' => ['fatigue', 'vomiting', 'diarrhea'], 'diagnosis' => 'influenza'],

    // Irritable Bowel Syndrome (IBS)
    ['symptoms' => ['abdominal pain or cramping', 'bloating', 'gas'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['diarrhea or constipation', 'mucus in stool', 'sudden urgency to have bowel movements'], 'diagnosis' => 'irritable bowel syndrome'],
    ['symptoms' => ['feeling of incomplete bowel movement', 'fatigue', 'anxiety or depression'], 'diagnosis' => 'irritable bowel syndrome'],

    // Chronic Obstructive Pulmonary Disease (COPD)
    ['symptoms' => ['shortness of breath', 'chronic cough', 'wheezing'], 'diagnosis' => 'COPD'],
    ['symptoms' => ['chest tightness', 'frequent respiratory infections', 'lack of energy'], 'diagnosis' => 'COPD'],
    ['symptoms' => ['cyanosis (bluish tint) of lips or fingernails', 'swelling in ankles, feet, or legs', 'weight loss'], 'diagnosis' => 'COPD'],

    // Rheumatoid Arthritis
    ['symptoms' => ['joint pain', 'stiffness', 'swelling'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['fatigue', 'fever', 'weight loss'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['tender joints', 'warmth around joints', 'decreased range of motion'], 'diagnosis' => 'rheumatoid arthritis'],

    // Coronary Artery Disease (CAD)
    ['symptoms' => ['chest pain or discomfort', 'shortness of breath', 'heart palpitations'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['weakness or dizziness', 'nausea or sweating', 'pain that spreads to shoulders, neck, or arms'], 'diagnosis' => 'coronary artery disease'],
    ['symptoms' => ['irregular heartbeat', 'fatigue', 'pain during physical activity'], 'diagnosis' => 'coronary artery disease'],

    // Anxiety Disorders
    ['symptoms' => ['excessive worry', 'restlessness', 'fatigue'], 'diagnosis' => 'anxiety disorders'],
    ['symptoms' => ['irritability', 'muscle tension', 'sleep disturbances'], 'diagnosis' => 'anxiety disorders'],
    ['symptoms' => ['panic attacks', 'difficulty concentrating', 'physical symptoms such as sweating or trembling'], 'diagnosis' => 'anxiety disorders'],

    // Depression
    ['symptoms' => ['persistent sadness', 'loss of interest in activities', 'fatigue or decreased energy'], 'diagnosis' => 'depression'],
    ['symptoms' => ['feelings of guilt or worthlessness', 'difficulty concentrating', 'changes in appetite or weight'], 'diagnosis' => 'depression'],
    ['symptoms' => ['agitation or restlessness', 'sleep disturbances', 'thoughts of death or suicide'], 'diagnosis' => 'depression'],

    // Schizophrenia
    ['symptoms' => ['hallucinations', 'delusions', 'disorganized thinking'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['social withdrawal', 'difficulty expressing emotions', 'lack of motivation'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['cognitive deficits', 'poor hygiene', 'flat affect (reduced emotional expression)'], 'diagnosis' => 'schizophrenia'],

    // Bipolar Disorder
    ['symptoms' => ['extreme mood swings', 'mania or hypomania', 'depressive episodes'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['racing thoughts', 'increased energy or activity', 'reckless behavior'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['irritability', 'sleep disturbances', 'difficulty in making decisions'], 'diagnosis' => 'bipolar disorder'],

    //Alzheimer's Disease (continued)
    ['symptoms' => ['personality changes', 'difficulty in recognizing family and friends', 'depression or apathy'], 'diagnosis' => 'Alzheimer\'s disease'],
    ['symptoms' => ['agitation', 'hallucinations', 'delusions'], 'diagnosis' => 'Alzheimer\'s disease'],
    ['symptoms' => ['trouble with coordination and motor functions', 'difficulty swallowing', 'behavioral changes'], 'diagnosis' => 'Alzheimer\'s disease'],

    // Obsessive-Compulsive Disorder (OCD)
    ['symptoms' => ['obsessions (unwanted, intrusive thoughts)', 'compulsions (repetitive behaviors)', 'fear of contamination'], 'diagnosis' => 'obsessive-compulsive disorder'],
    ['symptoms' => ['checking behaviors', 'ordering and arranging items', 'hoarding'], 'diagnosis' => 'obsessive-compulsive disorder'],
    ['symptoms' => ['excessive cleaning or washing', 'counting', 'touching or tapping'], 'diagnosis' => 'obsessive-compulsive disorder'],

    // Attention-Deficit/Hyperactivity Disorder (ADHD)
    ['symptoms' => ['inattention', 'hyperactivity', 'impulsivity'], 'diagnosis' => 'attention-deficit/hyperactivity disorder'],
    ['symptoms' => ['careless mistakes', 'easily distracted', 'forgetfulness'], 'diagnosis' => 'attention-deficit/hyperactivity disorder'],
    ['symptoms' => ['restlessness', 'difficulty organizing tasks', 'talkativeness'], 'diagnosis' => 'attention-deficit/hyperactivity disorder'],

    // Eating Disorders
    ['symptoms' => ['extreme thinness', 'intense fear of gaining weight', 'distorted body image'], 'diagnosis' => 'eating disorders'],
    ['symptoms' => ['self-induced vomiting', 'excessive exercise', 'preoccupation with food'], 'diagnosis' => 'eating disorders'],
    ['symptoms' => ['food restriction', 'binge eating', 'use of laxatives or diuretics'], 'diagnosis' => 'eating disorders'],

    // Post-Traumatic Stress Disorder (PTSD)
    ['symptoms' => ['flashbacks', 'nightmares', 'severe anxiety'], 'diagnosis' => 'post-traumatic stress disorder'],
    ['symptoms' => ['avoidance of reminders of trauma', 'negative changes in mood or thoughts', 'difficulty concentrating'], 'diagnosis' => 'post-traumatic stress disorder'],
    ['symptoms' => ['hyperarousal (easily startled)', 'irritability', 'reckless or self-destructive behavior'], 'diagnosis' => 'post-traumatic stress disorder'],

    // Sleep Disorders
    ['symptoms' => ['difficulty falling or staying asleep', 'excessive daytime sleepiness', 'irregular breathing or movements during sleep'], 'diagnosis' => 'sleep disorders'],
    ['symptoms' => ['loud snoring', 'difficulty awakening from sleep', 'unrefreshing sleep'], 'diagnosis' => 'sleep disorders'],
    ['symptoms' => ['morning headaches', 'chronic fatigue', 'restless legs during sleep'], 'diagnosis' => 'sleep disorders'],

    // Substance Use Disorders
    ['symptoms' => ['compulsive drug seeking and use', 'continued use despite harmful consequences', 'physical withdrawal symptoms'], 'diagnosis' => 'substance use disorders'],
    ['symptoms' => ['increased tolerance to the substance', 'neglecting other activities', 'social or interpersonal problems'], 'diagnosis' => 'substance use disorders'],
    ['symptoms' => ['taking larger amounts or for longer than intended', 'unsuccessful attempts to cut down or control use', 'spending a lot of time obtaining, using, or recovering from the substance'], 'diagnosis' => 'substance use disorders'],

    // Bipolar Disorder (continued)
    ['symptoms' => ['depressive episodes', 'mania (elevated mood)', 'hypomania (milder form of mania)'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['rapid speech', 'racing thoughts', 'euphoria'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['reckless behavior', 'excessive spending', 'increased energy'], 'diagnosis' => 'bipolar disorder'],

    // Multiple Sclerosis (MS)
    ['symptoms' => ['numbness or weakness in one or more limbs', 'partial or complete loss of vision', 'electric shock sensations with certain neck movements'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['tremor', 'fatigue', 'dizziness'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['problems with coordination and balance', 'slurred speech', 'bladder or bowel dysfunction'], 'diagnosis' => 'multiple sclerosis'],

    // Parkinson's Disease
    ['symptoms' => ['tremor', 'rigidity', 'bradykinesia (slowness of movement)'], 'diagnosis' => 'Parkinson\'s disease'],
    ['symptoms' => ['postural instability (balance problems)', 'loss of automatic movements', 'speech changes'], 'diagnosis' => 'Parkinson\'s disease'],
    ['symptoms' => ['sleep disturbances', 'constipation', 'depression'], 'diagnosis' => 'Parkinson\'s disease'],

    // Epilepsy
    ['symptoms' => ['seizures (convulsions)', 'temporary confusion', 'staring spells'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['uncontrollable jerking movements of the arms and legs', 'loss of consciousness or awareness', 'psychic symptoms (such as fear or déjà vu)'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['automatisms (repetitive movements)', 'aura (sensory warning)', 'sudden falling or stumbling for no reason'], 'diagnosis' => 'epilepsy'],

    // Guillain-Barré Syndrome
    ['symptoms' => ['muscle weakness or paralysis', 'tingling or prickling sensations', 'pain'], 'diagnosis' => 'Guillain-Barré syndrome'],
    ['symptoms' => ['difficulty breathing or swallowing', 'heart rate or blood pressure problems', 'difficulty with eye movement, facial movement, or speaking'], 'diagnosis' => 'Guillain-Barré syndrome'],
    ['symptoms' => ['problems with bladder control or bowel function', 'abnormal heart rhythms', 'blood clots'], 'diagnosis' => 'Guillain-Barré syndrome'],

    // Amyotrophic Lateral Sclerosis (ALS)
    ['symptoms' => ['muscle weakness', 'difficulty speaking or swallowing', 'muscle cramps or twitching'], 'diagnosis' => 'amyotrophic lateral sclerosis'],
    ['symptoms' => ['progressive inability to move', 'breathing problems', 'thick speech or difficulty forming words'], 'diagnosis' => 'amyotrophic lateral sclerosis'],
    ['symptoms' => ['unintended weight loss', 'frontotemporal dementia', 'sleep disturbances'], 'diagnosis' => 'amyotrophic lateral sclerosis'],

    // Celiac Disease
    ['symptoms' => ['digestive symptoms (diarrhea, bloating, gas)', 'fatigue', 'weight loss'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['bone or joint pain', 'anemia (low blood count)', 'headaches'], 'diagnosis' => 'celiac disease'],
    ['symptoms' => ['depression or anxiety', 'skin rash (dermatitis herpetiformis)', 'infertility or miscarriage'], 'diagnosis' => 'celiac disease'],

    // Fibromyalgia
    ['symptoms' => ['widespread pain', 'fatigue', 'cognitive difficulties (fibro fog)'], 'diagnosis' => 'fibromyalgia'],
    ['symptoms' => ['sleep disturbances', 'headaches', 'sensitivity to light, sound, or touch'], 'diagnosis' => 'fibromyalgia'],
    ['symptoms' => ['irritable bowel syndrome (IBS)', 'temporomandibular joint disorders (TMJ)', 'depression or anxiety'], 'diagnosis' => 'fibromyalgia'],

    // Schizophrenia
    ['symptoms' => ['hallucinations (seeing or hearing things that aren\'t there)', 'delusions (false beliefs)', 'disorganized thinking'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['disorganized speech', 'negative symptoms (lack of motivation or emotions)', 'difficulty concentrating'], 'diagnosis' => 'schizophrenia'],
    ['symptoms' => ['social withdrawal', 'anhedonia (inability to experience pleasure)', 'irrational beliefs or behavior'], 'diagnosis' => 'schizophrenia'],

    // Borderline Personality Disorder (BPD)
    ['symptoms' => ['intense fear of abandonment', 'unstable relationships', 'impulsive behaviors'], 'diagnosis' => 'borderline personality disorder'],
    ['symptoms' => ['identity disturbance', 'chronic feelings of emptiness', 'anger or irritability'], 'diagnosis' => 'borderline personality disorder'],
    ['symptoms' => ['paranoia', 'self-harm or suicidal behaviors', 'mood swings'], 'diagnosis' => 'borderline personality disorder'],

    // Social Anxiety Disorder (Social Phobia)
    ['symptoms' => ['intense fear of social situations', 'fear of being judged or embarrassed', 'avoidance of social activities'], 'diagnosis' => 'social anxiety disorder'],
    ['symptoms' => ['physical symptoms of anxiety (sweating, trembling)', 'rapid heartbeat', 'nausea'], 'diagnosis' => 'social anxiety disorder'],
    ['symptoms' => ['blushing', 'difficulty speaking', 'feeling faint or dizzy'], 'diagnosis' => 'social anxiety disorder'],

    // Generalized Anxiety Disorder (GAD)
    ['symptoms' => ['excessive worry or anxiety', 'restlessness', 'fatigue'], 'diagnosis' => 'generalized anxiety disorder'],
    ['symptoms' => ['difficulty concentrating', 'irritability', 'muscle tension'], 'diagnosis' => 'generalized anxiety disorder'],
    ['symptoms' => ['sleep disturbances', 'panic attacks', 'digestive problems'], 'diagnosis' => 'generalized anxiety disorder'],

    // Major Depressive Disorder (MDD)
    ['symptoms' => ['persistent sadness', 'loss of interest or pleasure', 'feelings of worthlessness or guilt'], 'diagnosis' => 'major depressive disorder'],
    ['symptoms' => ['changes in appetite or weight', 'sleep disturbances (insomnia or hypersomnia)', 'psychomotor agitation or retardation'], 'diagnosis' => 'major depressive disorder'],
    ['symptoms' => ['fatigue or loss of energy', 'difficulty concentrating or making decisions', 'recurrent thoughts of death or suicide'], 'diagnosis' => 'major depressive disorder'],

    // Bipolar Disorder (continued)
    ['symptoms' => ['depressive episodes', 'mania (elevated mood)', 'hypomania (milder form of mania)'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['rapid speech', 'racing thoughts', 'euphoria'], 'diagnosis' => 'bipolar disorder'],
    ['symptoms' => ['reckless behavior', 'excessive spending', 'increased energy'], 'diagnosis' => 'bipolar disorder'],

    // Panic Disorder
    ['symptoms' => ['sudden and repeated attacks of intense fear', 'feeling of losing control or going crazy', 'fear of dying'], 'diagnosis' => 'panic disorder'],
    ['symptoms' => ['palpitations, pounding heart, or accelerated heart rate', 'sweating', 'trembling or shaking'], 'diagnosis' => 'panic disorder'],
    ['symptoms' => ['shortness of breath or smothering sensations', 'chest pain or discomfort', 'nausea or abdominal distress'], 'diagnosis' => 'panic disorder'],

    // Dissociative Identity Disorder (DID)
    ['symptoms' => ['presence of two or more distinct identities or personality states', 'gaps in memory of everyday events', 'personal information or traumatic events'], 'diagnosis' => 'dissociative identity disorder'],
    ['symptoms' => ['significant distress or impairment in social, occupational, or other important areas of functioning', 'not attributable to cultural or religious practices'], 'diagnosis' => 'dissociative identity disorder'],
    ['symptoms' => ['frequent time loss (amnesia)', 'trances or out-of-body experiences', 'depersonalization (feeling detached from oneself)'], 'diagnosis' => 'dissociative identity disorder'],

    // Conduct Disorder
    ['symptoms' => ['aggression towards people and animals', 'destruction of property', 'deceitfulness or theft'], 'diagnosis' => 'conduct disorder'],
    ['symptoms' => ['serious violations of rules', 'lack of empathy or remorse', 'initiation of physical fights'], 'diagnosis' => 'conduct disorder'],
    ['symptoms' => ['use of weapons', 'forced sexual activity', 'cruelty to animals'], 'diagnosis' => 'conduct disorder'],

    // Oppositional Defiant Disorder (ODD)
    ['symptoms' => ['frequent temper tantrums', 'arguing with adults or authority figures', 'refusal to comply with rules'], 'diagnosis' => 'oppositional defiant disorder'],
    ['symptoms' => ['deliberate attempts to annoy or upset others', 'blaming others for mistakes or misbehavior', 'spiteful or vindictive behavior'], 'diagnosis' => 'oppositional defiant disorder'],
    ['symptoms' => ['anger and resentment', 'hostility towards peers', 'difficulty maintaining friendships'], 'diagnosis' => 'oppositional defiant disorder'],

    // Adjustment Disorder
    ['symptoms' => ['emotional or behavioral symptoms in response to stressful events', 'excessive worry', 'feeling sad or hopeless'], 'diagnosis' => 'adjustment disorder'],
    ['symptoms' => ['disturbance of conduct', 'marked distress that is out of proportion to the severity or intensity of the stressor', 'impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'adjustment disorder'],
    ['symptoms' => ['inability to concentrate', 'withdrawal from social activities', 'physical complaints with no medical basis'], 'diagnosis' => 'adjustment disorder'],

    // Tourette Syndrome
    ['symptoms' => ['motor tics (involuntary movements)', 'vocal tics (uncontrollable sounds)', 'tics that change over time'], 'diagnosis' => 'Tourette syndrome'],
    ['symptoms' => ['tics that occur many times a day', 'tics that occur nearly every day or intermittently for more than a year', 'onset before age 18'], 'diagnosis' => 'Tourette syndrome'],
    ['symptoms' => ['tics that wax and wane', 'tics that worsen with stress, excitement, or fatigue', 'other conditions, such as OCD or ADHD, often co-occur'], 'diagnosis' => 'Tourette syndrome'],

    // Gender Dysphoria
    ['symptoms' => ['strong desire to be of the other gender', 'strong dislike of one\'s sexual anatomy', 'strong desire for the sexual characteristics of the other gender'], 'diagnosis' => 'gender dysphoria'],
    ['symptoms' => ['desire to be treated as the other gender', 'conviction that one has the typical feelings and reactions of the other gender', 'significant distress or impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'gender dysphoria'],
    ['symptoms' => ['desire for primary or secondary sex characteristics of the other gender', 'desire to be of the other gender in children', 'insistence that one is of the other gender'], 'diagnosis' => 'gender dysphoria'],

    // Hoarding Disorder
    ['symptoms' => ['persistent difficulty discarding possessions', 'strong urges to save items', 'distress associated with discarding items'], 'diagnosis' => 'hoarding disorder'],
    ['symptoms' => ['accumulation of a large number of possessions that clutter living areas', 'significant distress or impairment in functioning', 'reluctance to let others touch or borrow possessions'], 'diagnosis' => 'hoarding disorder'],
    ['symptoms' => ['hoarding causes distress in social, occupational, or other important areas of functioning', 'hoarding is not attributable to another medical condition', 'hoarding is not better explained by symptoms of another mental disorder'], 'diagnosis' => 'hoarding disorder'],

    // Factitious Disorder
    ['symptoms' => ['falsification of physical or psychological signs or symptoms', 'deceptive behavior', 'presenting oneself as ill, impaired, or injured'], 'diagnosis' => 'factitious disorder'],
    ['symptoms' => ['absence of external rewards', 'behavior is not better explained by another mental disorder', 'behavior is not explained by another medical condition'], 'diagnosis' => 'factitious disorder'],
    ['symptoms' => ['behavior is not better accounted for by malingering or antisocial behavior', 'behavior is not exclusively during the course of schizophrenia or another psychotic disorder', 'behavior is not accounted for by delusional disorder'], 'diagnosis' => 'factitious disorder'],

    // Kleptomania
    ['symptoms' => ['recurrent failure to resist impulses to steal objects', 'increased tension before committing theft', 'pleasure or gratification while stealing'], 'diagnosis' => 'kleptomania'],
    ['symptoms' => ['stealing is not done to express anger or vengeance', 'stealing is not due to a manic episode or conduct disorder', 'stealing is not better explained by antisocial personality disorder, a psychotic disorder, or dementia'], 'diagnosis' => 'kleptomania'],
    ['symptoms' => ['guilt or remorse after stealing', 'returning stolen items or giving them away', 'stealing is not attributable to another mental disorder'], 'diagnosis' => 'kleptomania'],

    // Pyromania
    ['symptoms' => ['deliberate and purposeful fire setting on more than one occasion', 'tension or affective arousal before setting fires', 'fascination with, interest in, curiosity about, or attraction to fire and its situational contexts'], 'diagnosis' => 'pyromania'],
    ['symptoms' => ['pleasure, gratification, or relief when setting fires or when witnessing or participating in their aftermath', 'fire setting is not done for monetary gain, as an expression of sociopolitical ideology, to conceal criminal activity, to express anger or vengeance, to improve one\'s living circumstances, in response to a delusion or hallucination, or impaired judgment'], 'diagnosis' => 'pyromania'],
    ['symptoms' => ['fire setting is not better explained by conduct disorder, a manic episode, antisocial personality disorder, mental disorder, or dementia'], 'diagnosis' => 'pyromania'],

    // Trichotillomania (Hair-Pulling Disorder)
    ['symptoms' => ['recurrent pulling out of one\'s hair', 'increasing sense of tension immediately before pulling out the hair or when attempting to resist the behavior', 'pleasure, gratification, or relief when pulling out the hair'], 'diagnosis' => 'trichotillomania'],
    ['symptoms' => ['hair pulling is not attributable to another medical condition (e.g., dermatological condition)', 'hair pulling is not better explained by the symptoms of another mental disorder (e.g., attempts to improve a perceived defect or flaw in appearance in body dysmorphic disorder)', 'hair pulling causes clinically significant distress or impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'trichotillomania'],
    ['symptoms' => ['hair pulling is not due to the effects of a substance (e.g., cocaine) or a medication (e.g., a drug used to treat Parkinson\'s disease)', 'hair pulling is not better explained by another mental disorder (e.g., delusions or hallucinations in schizophrenia)'], 'diagnosis' => 'trichotillomania'],

    // Excoriation (Skin-Picking) Disorder
    ['symptoms' => ['recurrent skin picking resulting in skin lesions', 'repeated attempts to stop skin picking', 'clinically significant distress or impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'excoriation (skin-picking) disorder'],
    ['symptoms' => ['skin picking is not attributable to the effects of a substance (e.g., cocaine) or another medical condition (e.g., scabies)', 'skin picking is not better explained by the symptoms of another mental disorder (e.g., delusions in delusional disorder or tactile hallucinations in schizophrenia)', 'skin picking is not better explained by the symptoms of another mental disorder (e.g., attempts to improve a perceived defect or flaw in appearance in body dysmorphic disorder)'], 'diagnosis' => 'excoriation (skin-picking) disorder'],
    ['symptoms' => ['skin picking is not better explained by the symptoms of another mental disorder (e.g., attempts to improve a perceived defect or flaw in appearance in body dysmorphic disorder)', 'skin picking is not better explained by the symptoms of another mental disorder (e.g., attempts to improve a perceived defect or flaw in appearance in body dysmorphic disorder)', 'skin picking is not better explained by the symptoms of another mental disorder (e.g., attempts to improve a perceived defect or flaw in appearance in body dysmorphic disorder)'], 'diagnosis' => 'excoriation (skin-picking) disorder'],

    // Selective Mutism
    ['symptoms' => ['consistent failure to speak in specific social situations (e.g., at school or with playmates) despite speaking in other situations', 'interference with educational or occupational achievement or with social communication', 'duration of at least 1 month (not limited to the first month of school)'], 'diagnosis' => 'selective mutism'],
    ['symptoms' => ['failure to speak is not due to a lack of knowledge or comfort with the spoken language required in the social situation', 'failure to speak is not better explained by a communication disorder (e.g., childhood-onset fluency disorder) and does not occur exclusively during the course of autism spectrum disorder, schizophrenia, or another psychotic disorder'], 'diagnosis' => 'selective mutism'],
    ['symptoms' => ['failure to speak is not better explained by a lack of knowledge or comfort with the spoken language required in the social situation', 'failure to speak is not better explained by a lack of knowledge or comfort with the spoken language required in the social situation', 'failure to speak is not better explained by a lack of knowledge or comfort with the spoken language required in the social situation'], 'diagnosis' => 'selective mutism'],

    // Reactive Attachment Disorder (RAD)
    ['symptoms' => ['consistent pattern of inhibited, emotionally withdrawn behavior towards adult caregivers', 'persistent social or emotional disturbance', 'experiencing a pattern of extremes of insufficient care as evidenced by at least one of the following: social neglect or deprivation in the form of persistent lack of having basic emotional needs for comfort, stimulation, and affection responded to by a caring adult'], 'diagnosis' => 'reactive attachment disorder (RAD)'],
    ['symptoms' => ['persistent lack of having basic emotional needs for comfort, stimulation, and affection responded to by a caring adult', 'persistent social or emotional disturbance', 'persistent lack of having basic emotional needs for comfort, stimulation, and affection responded to by a caring adult'], 'diagnosis' => 'reactive attachment disorder (RAD)'],
    ['symptoms' => ['lack of emotional responsiveness to others', 'not exclusively during the course of autism spectrum disorder, a pervasive developmental disorder, schizophrenia, or another psychotic disorder', 'lack of emotional responsiveness to others'], 'diagnosis' => 'reactive attachment disorder (RAD)'],

    // Neurodevelopmental Disorders
    ['symptoms' => ['diagnostic features of neurodevelopmental disorders', 'chronic course with onset in childhood', 'impairment in social, academic, or occupational functioning'], 'diagnosis' => 'neurodevelopmental disorders'],
    ['symptoms' => ['evidence of deficits in social communication and social interaction across contexts', 'restricted, repetitive patterns of behavior, interests, or activities', 'symptoms are present in the early developmental period (typically recognized in the first two years of life)'], 'diagnosis' => 'neurodevelopmental disorders'],
    ['symptoms' => ['evidence of deficits in social communication and social interaction across contexts', 'restricted, repetitive patterns of behavior, interests, or activities', 'symptoms are not attributable to another medical or neurological condition'], 'diagnosis' => 'neurodevelopmental disorders'],

    // Intellectual Disabilities
    ['symptoms' => ['deficits in intellectual functions', 'deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards', 'onset during the developmental period'], 'diagnosis' => 'intellectual disabilities'],
    ['symptoms' => ['deficits in intellectual functions', 'deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards', 'deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards'], 'diagnosis' => 'intellectual disabilities'],
    ['symptoms' => ['deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards', 'deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards', 'deficits in adaptive functioning that result in failure to meet developmental and sociocultural standards'], 'diagnosis' => 'intellectual disabilities'],

    // Communication Disorders
    ['symptoms' => ['persistent difficulties in the acquisition and use of language across modalities', 'limitations in effective communication, social participation, academic achievement, or occupational performance', 'onset in the early developmental period'], 'diagnosis' => 'communication disorders'],
    ['symptoms' => ['deficits in the ability to effectively exchange, use, or understand verbal or nonverbal information', 'limitations in effective communication, social participation, academic achievement, or occupational performance', 'onset in the early developmental period'], 'diagnosis' => 'communication disorders'],
    ['symptoms' => ['deficits in the ability to effectively exchange, use, or understand verbal or nonverbal information', 'limitations in effective communication, social participation, academic achievement, or occupational performance', 'deficits in the ability to effectively exchange, use, or understand verbal or nonverbal information'], 'diagnosis' => 'communication disorders'],

    // Obsessive-Compulsive Disorder (OCD)
    ['symptoms' => ['presence of obsessions (recurrent and persistent thoughts, urges, or images)', 'attempt to ignore or suppress obsessions', 'engagement in compulsions (repetitive behaviors or mental acts)'], 'diagnosis' => 'obsessive-compulsive disorder'],
    ['symptoms' => ['obsessions or compulsions are time-consuming', 'obsessions or compulsions cause clinically significant distress or impairment', 'symptoms are not attributable to the physiological effects of a substance or another medical condition'], 'diagnosis' => 'obsessive-compulsive disorder'],
    ['symptoms' => ['recurrent thoughts about contamination', 'need for symmetry or exactness', 'fear of harm coming to oneself or others'], 'diagnosis' => 'obsessive-compulsive disorder'],

    // Body Dysmorphic Disorder (BDD)
    ['symptoms' => ['preoccupation with one or more perceived defects or flaws in physical appearance', 'performing repetitive behaviors (e.g., mirror checking)', 'excessive grooming or seeking reassurance about appearance'], 'diagnosis' => 'body dysmorphic disorder'],
    ['symptoms' => ['preoccupation causes clinically significant distress or impairment', 'preoccupation is not better explained by concerns with body fat or weight in an eating disorder', 'symptoms are not attributable to the physiological effects of a substance or another medical condition'], 'diagnosis' => 'body dysmorphic disorder'],
    ['symptoms' => ['frequent comparing of appearance with others', 'avoidance of social situations or mirrors', 'seeking cosmetic procedures to correct perceived flaws'], 'diagnosis' => 'body dysmorphic disorder'],

    // Insomnia Disorder
    ['symptoms' => ['difficulty initiating sleep or maintaining sleep', 'non-restorative sleep', 'significant distress or impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'insomnia disorder'],
    ['symptoms' => ['sleep disturbance occurs at least 3 times per week', 'sleep disturbance is present for at least 3 months', 'sleep disturbance is not better explained by another sleep disorder, medical condition, or substance use'], 'diagnosis' => 'insomnia disorder'],
    ['symptoms' => ['daytime fatigue or sleepiness', 'difficulty concentrating or irritability', 'worry or anxiety about sleep'], 'diagnosis' => 'insomnia disorder'],

    // Nightmare Disorder
    ['symptoms' => ['repeated awakenings from sleep due to intensely disturbing dreams', 'recollection of dreams upon awakening', 'significant distress or impairment in social, occupational, or other important areas of functioning'], 'diagnosis' => 'nightmare disorder'],
    ['symptoms' => ['dreams usually involve threats to survival, security, or physical integrity', 'awakenings usually occur during the second half of the sleep period', 'sleep disturbance is not better explained by another sleep disorder, mental disorder, or medical condition'], 'diagnosis' => 'nightmare disorder'],
    ['symptoms' => ['sleep disturbance causes clinically significant distress or impairment', 'nightmares occur at least once a week', 'dream content involves persistent themes of failure, personal inadequacy, or loss'], 'diagnosis' => 'nightmare disorder'],

    // Hypersomnolence Disorder
    ['symptoms' => ['excessive sleepiness despite a main sleep period of 7 hours or more', 'sleep inertia (difficulty waking up)', 'prolonged nighttime sleep or daytime naps that do not refresh'], 'diagnosis' => 'hypersomnolence disorder'],
    ['symptoms' => ['sleepiness occurs at least 3 times per week for at least 3 months', 'sleepiness is accompanied by difficulty waking up after abrupt awakenings', 'sleepiness is not better explained by another sleep disorder, medical condition, or substance use'], 'diagnosis' => 'hypersomnolence disorder'],
    ['symptoms' => ['sleep disturbance causes clinically significant distress or impairment', 'daytime sleepiness interferes with daily activities', 'long naps do not relieve sleepiness'], 'diagnosis' => 'hypersomnolence disorder'],

    // Avoidant/Restrictive Food Intake Disorder (ARFID)
    ['symptoms' => ['lack of interest in eating or food avoidance', 'significant weight loss or nutritional deficiency', 'interference with psychosocial functioning'], 'diagnosis' => 'avoidant/restrictive food intake disorder (ARFID)'],
    ['symptoms' => ['fear of aversive consequences of eating', 'avoidance based on the sensory characteristics of food', 'lack of concern about weight or body image'], 'diagnosis' => 'avoidant/restrictive food intake disorder (ARFID)'],
    ['symptoms' => ['eating disturbance is not explained by lack of food availability or cultural practices', 'eating disturbance does not occur exclusively during the course of anorexia nervosa or bulimia nervosa', 'eating disturbance is not attributable to another medical condition or mental disorder'], 'diagnosis' => 'avoidant/restrictive food intake disorder (ARFID)'],

    // Rumination Disorder
    ['symptoms' => ['repeated regurgitation of food', 're-chewing of food', 'repeatedly spitting out or re-swallowing food'], 'diagnosis' => 'rumination disorder'],
    ['symptoms' => ['regurgitation is not due to a gastrointestinal or other medical condition', 'regurgitation is not attributable to anorexia nervosa, bulimia nervosa, binge-eating disorder, or avoidant/restrictive food intake disorder', 'regurgitation does not occur exclusively during the course of another mental disorder (e.g., rumination in OCD)'], 'diagnosis' => 'rumination disorder'],
    ['symptoms' => ['regurgitation occurs at least once a week for at least 3 months', 'regurgitation is not better explained by another medical condition or mental disorder', 'regurgitation causes clinically significant distress or impairment'], 'diagnosis' => 'rumination disorder'],

    // Factitious Disorder Imposed on Another (Munchausen Syndrome by Proxy)
    ['symptoms' => ['falsification of physical or psychological signs or symptoms in another person', 'deceptive behavior', 'motivation is to assume the sick role by proxy'], 'diagnosis' => 'factitious disorder imposed on another (Munchausen syndrome by proxy)'],
    ['symptoms' => ['behavior is not better explained by another mental disorder', 'behavior is not explained by another medical condition', 'behavior is not better explained by malingering or antisocial behavior'], 'diagnosis' => 'factitious disorder imposed on another (Munchausen syndrome by proxy)'],
    ['symptoms' => ['behavior is not exclusively during the course of another psychotic disorder or delusional disorder', 'behavior is not better accounted for by another mental disorder (e.g., factitious disorder imposed on self)', 'behavior is not better accounted for by the effects of a substance (e.g., a drug of abuse, a medication) or another medical condition (e.g., severe illness with psychological factors)'], 'diagnosis' => 'factitious disorder imposed on another (Munchausen syndrome by proxy)'],

    // Somatic Symptom Disorder
    ['symptoms' => ['one or more somatic symptoms that are distressing or result in significant disruption of daily life', 'excessive thoughts, feelings, or behaviors related to the somatic symptoms', 'symptoms persist despite medical reassurance'], 'diagnosis' => 'somatic symptom disorder'],
    ['symptoms' => ['duration of symptoms is at least 6 months', 'symptoms are not better explained by another medical condition', 'symptoms are not better explained by another mental disorder'], 'diagnosis' => 'somatic symptom disorder'],
    ['symptoms' => ['symptoms cause significant distress or impairment', 'symptoms are not intentionally produced or feigned (as in factitious disorder or malingering)', 'symptoms are not attributable to cultural or religious beliefs'], 'diagnosis' => 'somatic symptom disorder'],

    // Conversion Disorder (Functional Neurological Symptom Disorder)
    ['symptoms' => ['one or more symptoms of altered voluntary motor or sensory function', 'clinical findings provide evidence of incompatibility between the symptom and recognized neurological or medical conditions', 'symptoms are not better explained by another medical or mental disorder'], 'diagnosis' => 'conversion disorder (functional neurological symptom disorder)'],
    ['symptoms' => ['symptoms cause significant distress or impairment', 'symptoms are not intentionally produced or feigned (as in factitious disorder or malingering)', 'symptoms are not attributable to cultural or religious beliefs'], 'diagnosis' => 'conversion disorder (functional neurological symptom disorder)'],
    ['symptoms' => ['symptoms are not better explained by another medical or mental disorder', 'symptoms are not better explained by another medical or mental disorder', 'symptoms are not better explained by another medical or mental disorder'], 'diagnosis' => 'conversion disorder (functional neurological symptom disorder)'],

    // Chronic Kidney Disease
    ['symptoms' => ['persistent proteinuria', 'abnormal urine sediment (red blood cell casts)', 'electrolyte and other abnormalities due to tubular disorders'], 'diagnosis' => 'chronic kidney disease'],
    ['symptoms' => ['slowly progressive impairment of renal function', 'chronic anemia and bone mineral disorders', 'elevated serum creatinine and reduced glomerular filtration rate'], 'diagnosis' => 'chronic kidney disease'],
    ['symptoms' => ['end-stage renal disease requiring dialysis or transplantation', 'elevated blood pressure, fluid overload, metabolic acidosis', 'uremic symptoms and sequelae of loss of kidney function'], 'diagnosis' => 'chronic kidney disease'],

    // Cirrhosis
    ['symptoms' => ['complications of portal hypertension', 'splenomegaly, thrombocytopenia, and leukopenia', 'chronic liver disease and hepatic encephalopathy'], 'diagnosis' => 'cirrhosis'],
    ['symptoms' => ['jaundice, pruritus, and erythema', 'spider angiomata and palmar erythema', 'ascites and spontaneous bacterial peritonitis'], 'diagnosis' => 'cirrhosis'],
    ['symptoms' => ['varices, bleeding, and splenomegaly', 'hepatorenal syndrome and hepatopulmonary syndrome', 'hepatocellular carcinoma and cholangiocarcinoma'], 'diagnosis' => 'cirrhosis'],

    // Chronic Obstructive Pulmonary Disease (COPD)
    ['symptoms' => ['emphysema, chronic bronchitis, and asthma', 'dyspnea, cough, and sputum production', 'smoking and occupational exposure'], 'diagnosis' => 'chronic obstructive pulmonary disease (COPD)'],
    ['symptoms' => ['exercise intolerance and poor health status', 'acute exacerbations requiring hospitalization', 'airflow limitation and irreversible loss of pulmonary function'], 'diagnosis' => 'chronic obstructive pulmonary disease (COPD)'],
    ['symptoms' => ['pulmonary hypertension and respiratory failure', 'cor pulmonale and skeletal muscle dysfunction', 'weight loss and systemic inflammation'], 'diagnosis' => 'chronic obstructive pulmonary disease (COPD)'],

    // Crohn's Disease
    ['symptoms' => ['abdominal pain and diarrhea', 'fever and fatigue', 'malabsorption and nutritional deficiencies'], 'diagnosis' => 'Crohn\'s disease'],
    ['symptoms' => ['bowel strictures and fistulae', 'extraintestinal manifestations (e.g., arthritis)', 'granulomas and transmural inflammation'], 'diagnosis' => 'Crohn\'s disease'],
    ['symptoms' => ['rectal bleeding and perianal disease', 'aphthous ulcers and skip lesions', 'string sign and cobblestoning appearance'], 'diagnosis' => 'Crohn\'s disease'],

    // Chronic Pancreatitis
    ['symptoms' => ['persistent abdominal pain and steatorrhea', 'pancreatic calcifications and ductal dilatation', 'alcohol abuse and genetic predisposition'], 'diagnosis' => 'chronic pancreatitis'],
    ['symptoms' => ['diabetes mellitus and malnutrition', 'pancreatic pseudocysts and pancreatic cancer', 'pancreatic insufficiency and exocrine dysfunction'], 'diagnosis' => 'chronic pancreatitis'],
    ['symptoms' => ['recurrent acute pancreatitis and pancreatic necrosis', 'pancreatic ascites and pleural effusion', 'pancreatic encephalopathy and systemic inflammatory response syndrome'], 'diagnosis' => 'chronic pancreatitis'],

    // Chronic Heart Failure
    ['symptoms' => ['reduced left ventricular ejection fraction', 'pulmonary congestion and peripheral edema', 'myocardial infarction and ischemic cardiomyopathy'], 'diagnosis' => 'chronic heart failure'],
    ['symptoms' => ['dilated cardiomyopathy and systolic dysfunction', 'cardiac remodeling and neurohormonal activation', 'ventricular arrhythmias and sudden cardiac death'], 'diagnosis' => 'chronic heart failure'],
    ['symptoms' => ['biventricular heart failure and right-sided heart failure', 'pulmonary hypertension and cor pulmonale', 'cardiorenal syndrome and hepatic congestion'], 'diagnosis' => 'chronic heart failure'],

    // Chronic Hepatitis C
    ['symptoms' => ['persistent viremia and viral replication', 'hepatocellular injury and hepatic fibrosis', 'intravenous drug use and blood transfusion'], 'diagnosis' => 'chronic hepatitis C'],
    ['symptoms' => ['liver cirrhosis and hepatocellular carcinoma', 'liver transplantation and antiviral therapy', 'genotype and resistance-associated substitutions'], 'diagnosis' => 'chronic hepatitis C'],
    ['symptoms' => ['acute hepatitis and chronic liver disease', 'liver enzyme elevation and hyperbilirubinemia', 'chronic fatigue and extrahepatic manifestations'], 'diagnosis' => 'chronic hepatitis C'],

    // Chronic Gastritis
    ['symptoms' => ['persistent stomach inflammation and mucosal injury', 'helicobacter pylori infection and autoimmune disorder', 'acute and chronic gastritis'], 'diagnosis' => 'chronic gastritis'],
    ['symptoms' => ['gastric ulceration and intestinal metaplasia', 'epigastric pain and nausea', 'upper gastrointestinal bleeding and anemia'], 'diagnosis' => 'chronic gastritis'],
    ['symptoms' => ['dyspepsia and loss of appetite', 'malabsorption and gastric atrophy', 'gastric dysplasia and gastric cancer'], 'diagnosis' => 'chronic gastritis'],

    // Chronic Interstitial Nephritis
    ['symptoms' => ['persistent tubulointerstitial inflammation', 'renal dysfunction and impaired urine concentration', 'medication-induced and autoimmune-related'], 'diagnosis' => 'chronic interstitial nephritis'],
    ['symptoms' => ['nephrotic syndrome and glomerulonephritis', 'cyst formation and renal fibrosis', 'kidney biopsy and renal impairment'], 'diagnosis' => 'chronic interstitial nephritis'],
    ['symptoms' => ['systemic lupus erythematosus and autoimmune disorders', 'chronic kidney disease and acute renal failure', 'tubular atrophy and interstitial fibrosis'], 'diagnosis' => 'chronic interstitial nephritis'],
    
    ['symptoms' => ['chest pain', 'shortness of breath', 'sweating'], 'diagnosis' => 'heart attack'],
    ['symptoms' => ['burning sensation during urination', 'lower abdominal pain', 'frequent urination'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['severe headache', 'nausea', 'vomiting'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['intense abdominal pain', 'bloody diarrhea', 'fever'], 'diagnosis' => 'inflammatory bowel disease'],
    ['symptoms' => ['swollen joints', 'morning stiffness', 'fatigue'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['excessive thirst', 'frequent urination', 'blurred vision'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'yellowing of skin'], 'diagnosis' => 'hepatitis'],
    ['symptoms' => ['persistent cough', 'blood in sputum', 'weight loss'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['muscle pain', 'high fever', 'rash'], 'diagnosis' => 'dengue fever'],
    ['symptoms' => ['seizures', 'confusion', 'loss of consciousness'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['chronic fatigue', 'muscle weakness', 'weight gain'], 'diagnosis' => 'hypothyroidism'],
    ['symptoms' => ['vision changes', 'eye pain', 'halos around lights'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['sharp abdominal pain', 'vomiting blood', 'black stools'], 'diagnosis' => 'peptic ulcer'],
    ['symptoms' => ['joint pain', 'skin rash', 'photosensitivity'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fever', 'chills', 'night sweats'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['swelling in legs', 'shortness of breath', 'fatigue'], 'diagnosis' => 'heart failure'],
    ['symptoms' => ['persistent itching', 'yellow skin', 'abdominal pain'], 'diagnosis' => 'cholestasis'],
    ['symptoms' => ['burning sensation in chest', 'regurgitation', 'hoarseness'], 'diagnosis' => 'gastroesophageal reflux disease'],
    ['symptoms' => ['chest pain', 'palpitations', 'dizziness'], 'diagnosis' => 'arrhythmia'],
    ['symptoms' => ['confusion', 'hallucinations', 'tremors'], 'diagnosis' => 'delirium'],
    ['symptoms' => ['numbness in limbs', 'difficulty walking', 'loss of coordination'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['abdominal pain', 'bloody diarrhea', 'dehydration'], 'diagnosis' => 'cholera'],
    ['symptoms' => ['severe abdominal pain', 'fever', 'painful bowel movements'], 'diagnosis' => 'diverticulitis'],
    ['symptoms' => ['swollen lymph nodes', 'night sweats', 'weight loss'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['severe headache', 'stiff neck', 'photophobia'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['loss of smell', 'nasal congestion', 'facial pain'], 'diagnosis' => 'sinus infection'],
    ['symptoms' => ['intense abdominal pain', 'bloody vomit', 'dark urine'], 'diagnosis' => 'hemorrhagic fever'],
    ['symptoms' => ['decreased appetite', 'chronic diarrhea', 'weight loss'], 'diagnosis' => 'HIV/AIDS'],
    ['symptoms' => ['dry mouth', 'fatigue', 'dizziness'], 'diagnosis' => 'dehydration'],
    ['symptoms' => ['painful urination', 'cloudy urine', 'abdominal pain'], 'diagnosis' => 'kidney stones'],
    ['symptoms' => ['sudden vision loss', 'eye pain', 'headache'], 'diagnosis' => 'acute angle-closure glaucoma'],
    ['symptoms' => ['swelling in legs', 'abdominal distension', 'shortness of breath'], 'diagnosis' => 'heart disease'],
    ['symptoms' => ['painful red eye', 'blurred vision', 'headache'], 'diagnosis' => 'uveitis'],
    ['symptoms' => ['chronic cough', 'blood-streaked sputum', 'weight loss'], 'diagnosis' => 'lung cancer'],
    ['symptoms' => ['severe abdominal pain', 'bloating', 'constipation'], 'diagnosis' => 'intestinal obstruction'],
    ['symptoms' => ['painful red eye', 'blurred vision', 'headache'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['pain in upper abdomen', 'bloating', 'heartburn'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['shortness of breath', 'chest pain', 'dizziness'], 'diagnosis' => 'pulmonary embolism'],
    ['symptoms' => ['fatigue', 'muscle weakness', 'weight gain'], 'diagnosis' => 'hypothyroidism'],
    ['symptoms' => ['numbness in limbs', 'tingling sensation', 'muscle weakness'], 'diagnosis' => 'peripheral neuropathy'],
    ['symptoms' => ['severe headache', 'vision changes', 'vomiting'], 'diagnosis' => 'brain tumor'],
    ['symptoms' => ['confusion', 'memory loss', 'difficulty speaking'], 'diagnosis' => 'Alzheimer\'s disease'],
    ['symptoms' => ['abdominal pain', 'diarrhea', 'fever'], 'diagnosis' => 'intestinal infection'],
    ['symptoms' => ['chronic cough', 'shortness of breath', 'wheezing'], 'diagnosis' => 'chronic obstructive pulmonary disease'],
    ['symptoms' => ['numbness in limbs', 'muscle spasms', 'muscle weakness'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['swollen glands', 'sore throat', 'fatigue'], 'diagnosis' => 'mononucleosis'],
    ['symptoms' => ['pain in lower abdomen', 'vaginal discharge', 'vaginal itching'], 'diagnosis' => 'vaginal infection'],
    ['symptoms' => ['muscle pain', 'joint pain', 'rash'], 'diagnosis' => 'Lupus'],
    ['symptoms' => ['painful urination', 'cloudy urine', 'abdominal pain'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['chest pain', 'palpitations', 'dizziness'], 'diagnosis' => 'arrhythmia'],
    ['symptoms' => ['burning sensation in chest', 'regurgitation', 'hoarseness'], 'diagnosis' => 'gastroesophageal reflux disease'],
    ['symptoms' => ['severe headache', 'nausea', 'vomiting'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['intense abdominal pain', 'bloody diarrhea', 'fever'], 'diagnosis' => 'inflammatory bowel disease'],
    ['symptoms' => ['swollen joints', 'morning stiffness', 'fatigue'], 'diagnosis' => 'rheumatoid arthritis'],
    ['symptoms' => ['excessive thirst', 'frequent urination', 'blurred vision'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['abdominal pain', 'loss of appetite', 'yellowing of skin'], 'diagnosis' => 'hepatitis'],
    ['symptoms' => ['persistent cough', 'blood in sputum', 'weight loss'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['muscle pain', 'high fever', 'rash'], 'diagnosis' => 'dengue fever'],
    ['symptoms' => ['seizures', 'confusion', 'loss of consciousness'], 'diagnosis' => 'epilepsy'],
    ['symptoms' => ['chronic fatigue', 'muscle weakness', 'weight gain'], 'diagnosis' => 'hypothyroidism'],
    ['symptoms' => ['vision changes', 'eye pain', 'halos around lights'], 'diagnosis' => 'glaucoma'],
    ['symptoms' => ['sharp abdominal pain', 'vomiting blood', 'black stools'], 'diagnosis' => 'peptic ulcer'],
    ['symptoms' => ['joint pain', 'skin rash', 'photosensitivity'], 'diagnosis' => 'lupus'],
    ['symptoms' => ['fever', 'chills', 'night sweats'], 'diagnosis' => 'tuberculosis'],
    ['symptoms' => ['swelling in legs', 'shortness of breath', 'fatigue'], 'diagnosis' => 'heart failure'],
    ['symptoms' => ['persistent itching', 'yellow skin', 'abdominal pain'], 'diagnosis' => 'cholestasis'],
    ['symptoms' => ['burning sensation in chest', 'regurgitation', 'hoarseness'], 'diagnosis' => 'gastroesophageal reflux disease'],
    ['symptoms' => ['chest pain', 'palpitations', 'dizziness'], 'diagnosis' => 'arrhythmia'],
    ['symptoms' => ['confusion', 'hallucinations', 'tremors'], 'diagnosis' => 'delirium'],
    ['symptoms' => ['numbness in limbs', 'difficulty walking', 'loss of coordination'], 'diagnosis' => 'multiple sclerosis'],
    ['symptoms' => ['abdominal pain', 'bloody diarrhea', 'dehydration'], 'diagnosis' => 'cholera'],
    ['symptoms' => ['severe abdominal pain', 'fever', 'painful bowel movements'], 'diagnosis' => 'diverticulitis'],
    ['symptoms' => ['swollen lymph nodes', 'night sweats', 'weight loss'], 'diagnosis' => 'lymphoma'],
    ['symptoms' => ['severe headache', 'stiff neck', 'photophobia'], 'diagnosis' => 'meningitis'],
    ['symptoms' => ['loss of smell', 'nasal congestion', 'facial pain'], 'diagnosis' => 'sinus infection'],
    ['symptoms' => ['intense abdominal pain', 'bloody vomit', 'dark urine'], 'diagnosis' => 'hemorrhagic fever'],
    
];

function balanceDataset($dataset) {
    $diagnosisCounts = array_count_values(array_column($dataset, 'diagnosis'));
    $maxCount = max($diagnosisCounts);
    $balancedDataset = [];

    foreach ($diagnosisCounts as $diagnosis => $count) {
        $filteredSamples = array_filter($dataset, function ($sample) use ($diagnosis) {
            return $sample['diagnosis'] === $diagnosis;
        });

        while (count($filteredSamples) < $maxCount) {
            $filteredSamples[] = $filteredSamples[array_rand($filteredSamples)];
        }

        $balancedDataset = array_merge($balancedDataset, array_slice($filteredSamples, 0, $maxCount));
    }

    shuffle($balancedDataset);
    return $balancedDataset;
}

function trainTestSplit($dataset, $testSize = 0.2) {
    $shuffled = $dataset;
    shuffle($shuffled);
    $testCount = (int)($testSize * count($shuffled));
    return [
        'train' => array_slice($shuffled, $testCount),
        'test' => array_slice($shuffled, 0, $testCount)
    ];
}


$balancedDataset = balanceDataset($dataset);
$split = trainTestSplit($balancedDataset);
$trainSet = $split['train'];
$testSet = $split['test'];

$naiveBayes = new NaiveBayesClassifier();
$naiveBayes->train($trainSet);


?>
