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
