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
    ['symptoms' => ['swollen tonsils', 'sore throat', 'headache'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['painful swallowing', 'fever'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['bad breath', 'yellow coating on tonsils', 'neck pain'], 'diagnosis' => 'tonsillitis'],
    ['symptoms' => ['sore throat', 'hoarseness', 'fever'], 'diagnosis' => 'tonsillitis'],

    //LBP
    ['symptoms' => ['blurry vision', 'dizziness', 'weakness'], 'diagnosis' => 'low blood pressure'],
    ['symptoms' => ['lightheadedness', 'sleepiness', 'fainting'], 'diagnosis' => 'low blood pressure'],
    ['symptoms' => ['confusion', 'nausea', 'vomiting'], 'diagnosis' => 'low blood pressure'],

    //Allergies
    ['symptoms' => ['rashes', 'puffy eyes', 'runny nose'], 'diagnosis' => 'allergies'],
    ['symptoms' => ['sneezing', 'itchy nose', 'eye irritation'], 'diagnosis' => 'allergies'],
    ['symptoms' => ['itchy throat', 'watery eyes', 'stuffy nose'], 'diagnosis' => 'allergies'],

    //Anemia
    ['symptoms' => ['yellowish skin', 'tiredness', 'shortness of breath'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['cold hands', 'irregular heartbeat', 'dizziness'], 'diagnosis' => 'anemia'],
    ['symptoms' => ['pale skin', 'weakness', 'cold feet'], 'diagnosis' => 'anemia'],

    //Common Cold
    ['symptoms' => ['cough', 'fatigue', 'fever'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['runny nose', 'sneezing'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['cough', 'raised temperature'], 'diagnosis' => 'common cold'],
    ['symptoms' => ['chills', 'watery eyes', 'headache'], 'diagnosis' => 'common cold'],

    //Flu
    ['symptoms' => ['cough', 'sore throat', 'fever'], 'diagnosis' => 'flu'],
    ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
    ['symptoms' => ['chills', 'body aches'], 'diagnosis' => 'flu'],
    ['symptoms' => ['headache', 'fatigue', 'fever'], 'diagnosis' => 'flu'],

    //Food Poisoning
    ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
    ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],

    //Diarrhea
    ['symptoms' => ['loose stool', 'frequent bowel movements', 'fever'], 'diagnosis' => 'diarrhea'],
    ['symptoms' => ['cramping in the abdomen', 'nausea', 'bloody stool'], 'diagnosis' => 'diarrhea'],
    ['symptoms' => ['watery stool', 'bloating', 'pain in the abdomen'], 'diagnosis' => 'diarrhea'],

    //Pneumonia
    ['symptoms' => ['cough', 'shortness of breath', 'fever'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
    ['symptoms' => ['cough', 'chest congestion', 'fever'], 'diagnosis' => 'pneumonia'],

    //Asthma
    ['symptoms' => ['chest pain', 'trouble sleeping'], 'diagnosis' => 'asthma'], 
    ['symptoms' => ['wheeze', 'shortness of breath', 'cough'], 'diagnosis' => 'asthma'],

    //UTI
    ['symptoms' => ['painful urination', 'abdominal pain', 'fever'], 'diagnosis' => 'urinary tract infection'],
    ['symptoms' => ['foul odor of urine', 'yellowish urine', 'fever'], 'diagnosis' => 'urinary tract infection'],
    
    //Migraine
    ['symptoms' => ['trouble sleeping', 'mood changes', 'nausea'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['ringing in your ears', 'muscle weakness', 'vision changes'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['one sided head pain', 'senses sensitivity', 'vomiting'], 'diagnosis' => 'migraine'],
    ['symptoms' => ['neck stiffness', 'fatigue'], 'diagnosis' => 'migraine'],

    //Allergic Rhinitis
    ['symptoms' => ['sneezing', 'runny nose', 'watery eyes'], 'diagnosis' => 'allergic rhinitis'],
    ['symptoms' => ['blocked nose', 'cough', 'red eyes'], 'diagnosis' => 'allergic rhinitis'],

    //Bronchitis
    ['symptoms' => ['persistent cough', 'mucus production', 'fatigue'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['shortness of breath', 'chest discomfort'], 'diagnosis' => 'bronchitis'],
    ['symptoms' => ['wheezing', 'sore throat', 'body aches'], 'diagnosis' => 'bronchitis'],

    //Sinusitis
    ['symptoms' => ['facial pain', 'nasal congestion', 'headache'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['postnasal drip', 'sore throat'], 'diagnosis' => 'sinusitis'],
    ['symptoms' => ['loss of smell', 'cough', 'fever'], 'diagnosis' => 'sinusitis'],

    //Conjunctivitis
    ['symptoms' => ['red eyes', 'itchiness', 'tearing'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['discharge from eyes', 'crusty eyelids'], 'diagnosis' => 'conjunctivitis'],
    ['symptoms' => ['gritty feeling in eyes', 'swollen eyelids'], 'diagnosis' => 'conjunctivitis'],

    //Strep Throat
    ['symptoms' => ['sore throat', 'difficulty swallowing', 'fever'], 'diagnosis' => 'strep throat'],
    ['symptoms' => ['swollen lymph nodes', 'red spots on the roof of the mouth'], 'diagnosis' => 'strep throat'],
    ['symptoms' => ['headache', 'rash', 'nausea'], 'diagnosis' => 'strep throat'],

    //Chickenpox
    ['symptoms' => ['itchy rash', 'fever', 'tiredness'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['loss of appetite', 'headache'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['red spots', 'blistering', 'scabs'], 'diagnosis' => 'chickenpox'],

    //Vertigo
    ['symptoms' => ['spinning sensation', 'dizziness', 'balance problems'], 'diagnosis' => 'vertigo'],
    ['symptoms' => ['nausea', 'vomiting', 'headache'], 'diagnosis' => 'vertigo'],
    ['symptoms' => ['nystagmus', 'hearing loss'], 'diagnosis' => 'vertigo'],

    //Mononucleosis
    ['symptoms' => ['sore throat', 'fever', 'swollen lymph nodes'], 'diagnosis' => 'mononucleosis'],
    ['symptoms' => ['fatigue', 'muscle aches', 'rash'], 'diagnosis' => 'mononucleosis'],
    ['symptoms' => ['headache', 'loss of appetite', 'night sweats'], 'diagnosis' => 'mononucleosis'],

    //Gastroenteritis
    ['symptoms' => ['nausea', 'vomiting', 'diarrhea'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['abdominal pain', 'fever', 'chills'], 'diagnosis' => 'gastroenteritis'],
    ['symptoms' => ['headache', 'muscle pain', 'dehydration'], 'diagnosis' => 'gastroenteritis'],

    //Stress
    ['symptoms' => ['headaches', 'fatigue', 'difficulty sleeping'], 'diagnosis' => 'stress'],
    ['symptoms' => ['irritability', 'muscle tension', 'digestive issues'], 'diagnosis' => 'stress'],
    ['symptoms' => ['anxiety', 'depression', 'difficulty concentrating'], 'diagnosis' => 'stress'],

    // Ear Infection
    ['symptoms' => ['ear pain', 'trouble hearing', 'fluid drainage from ear'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['fever', 'headache', 'loss of balance'], 'diagnosis' => 'ear infection'],
    ['symptoms' => ['tugging at ear', 'irritability', 'difficulty sleeping'], 'diagnosis' => 'ear infection'],

    // Appendicitis
    ['symptoms' => ['abdominal pain', 'nausea', 'vomiting'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['loss of appetite', 'fever', 'abdominal swelling'], 'diagnosis' => 'appendicitis'],
    ['symptoms' => ['constipation', 'diarrhea', 'inability to pass gas'], 'diagnosis' => 'appendicitis'],

    // Measles
    ['symptoms' => ['fever', 'dry cough', 'runny nose'], 'diagnosis' => 'measles'],
    ['symptoms' => ['sore throat', 'inflamed eyes', 'skin rash'], 'diagnosis' => 'measles'],
    ['symptoms' => ['tiny white spots inside mouth', 'muscle pain'], 'diagnosis' => 'measles'],

    // Mumps
    ['symptoms' => ['swollen salivary glands', 'fever', 'headache'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['muscle aches', 'tiredness', 'loss of appetite'], 'diagnosis' => 'mumps'],
    ['symptoms' => ['pain while chewing', 'pain while swallowing'], 'diagnosis' => 'mumps'],

    // Hand, Foot, and Mouth Disease
    ['symptoms' => ['fever', 'sore throat', 'painful sores on mouth'], 'diagnosis' => 'hand, foot, and mouth disease'],
    ['symptoms' => ['rash on hands', 'rash on feet'], 'diagnosis' => 'hand, foot, and mouth disease'],
    ['symptoms' => ['loss of appetite', 'irritability', 'drooling'], 'diagnosis' => 'hand, foot, and mouth disease'],

    // Hyperventilation
    ['symptoms' => ['rapid breathing', 'dizziness', 'chest pain'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['numbness in hands', 'shortness of breath', 'lightheadedness'], 'diagnosis' => 'hyperventilation'],
    ['symptoms' => ['tingling in the feet', 'anxiety', 'confusion'], 'diagnosis' => 'hyperventilation'],

    // Hypothermia
    ['symptoms' => ['shivering', 'slurred speech', 'slow breathing'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['confusion', 'lack of coordination', 'weak pulse'], 'diagnosis' => 'hypothermia'],
    ['symptoms' => ['drowsiness', 'low energy', 'pale skin'], 'diagnosis' => 'hypothermia'],

    // Sore Eyes
    ['symptoms' => ['redness', 'itchiness', 'tearing'], 'diagnosis' => 'sore eyes'],
    ['symptoms' => ['burning sensation', 'sensitivity to light', 'gritty feeling'], 'diagnosis' => 'sore eyes'],
    ['symptoms' => ['blurred vision', 'discharge', 'eye pain'], 'diagnosis' => 'sore eyes'],

    // Heartburn
    ['symptoms' => ['burning sensation in chest', 'acid taste in mouth', 'difficulty swallowing'], 'diagnosis' => 'heartburn'],
    ['symptoms' => ['chest pain', 'regurgitation', 'bloating'], 'diagnosis' => 'heartburn'],
    ['symptoms' => ['sore throat', 'chronic cough', 'hoarseness'], 'diagnosis' => 'heartburn'],

    // Indigestion
    ['symptoms' => ['bloating', 'belching', 'nausea'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['fullness after eating', 'upper abdominal pain', 'heartburn'], 'diagnosis' => 'indigestion'],
    ['symptoms' => ['gas', 'acidic taste', 'growling stomach'], 'diagnosis' => 'indigestion'],

    // Acid Reflux
    ['symptoms' => ['heartburn', 'regurgitation', 'chest pain'], 'diagnosis' => 'acid reflux'],
    ['symptoms' => ['sore throat', 'chronic cough', 'bitter taste'], 'diagnosis' => 'acid reflux'],
    ['symptoms' => ['hoarseness', 'difficulty swallowing', 'bloating'], 'diagnosis' => 'acid reflux'],

    // Dysmenorrhea
    ['symptoms' => ['cramping in lower abdomen', 'lower back pain', 'nausea'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['headache', 'diarrhea', 'fatigue'], 'diagnosis' => 'dysmenorrhea'],
    ['symptoms' => ['dizziness', 'vomiting', 'bloating'], 'diagnosis' => 'dysmenorrhea'],

    // Ulcer
    ['symptoms' => ['burning stomach pain', 'bloating', 'heartburn'], 'diagnosis' => 'ulcer'],
    ['symptoms' => ['nausea', 'vomiting', 'weight loss'], 'diagnosis' => 'ulcer'],
    ['symptoms' => ['dark stools', 'fatigue', 'loss of appetite'], 'diagnosis' => 'ulcer'],

    // Fever
    ['symptoms' => ['elevated body temperature', 'chills', 'sweating'], 'diagnosis' => 'fever'],
    ['symptoms' => ['headache', 'muscle aches', 'weakness'], 'diagnosis' => 'fever'],
    ['symptoms' => ['loss of appetite', 'dehydration', 'irritability'], 'diagnosis' => 'fever'],

    // Diabetes
    ['symptoms' => ['frequent urination', 'excessive thirst', 'unexplained weight loss'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['extreme hunger', 'fatigue', 'blurred vision'], 'diagnosis' => 'diabetes'],
    ['symptoms' => ['slow healing sores', 'frequent infections', 'tingling in hands or feet'], 'diagnosis' => 'diabetes'],

    // Over Fatigue
    ['symptoms' => ['extreme tiredness', 'muscle weakness', 'difficulty concentrating'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['sleep disturbances', 'irritability', 'headaches'], 'diagnosis' => 'over fatigue'],
    ['symptoms' => ['decreased motivation', 'dizziness', 'appetite changes'], 'diagnosis' => 'over fatigue'],

    // Headache
    ['symptoms' => ['head pain', 'pressure in head', 'sensitivity to light'], 'diagnosis' => 'headache'],
    ['symptoms' => ['throbbing head pain', 'nausea', 'visual disturbances'], 'diagnosis' => 'headache'],
    ['symptoms' => ['headache worsens with activity', 'scalp tenderness', 'neck pain'], 'diagnosis' => 'headache'],

    // Chickenpox
    ['symptoms' => ['itchy rash', 'fever', 'tiredness'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['loss of appetite', 'headache', 'general discomfort'], 'diagnosis' => 'chickenpox'],
    ['symptoms' => ['red spots', 'blistering', 'scabs'], 'diagnosis' => 'chickenpox'],

    // Eye Strain
    ['symptoms' => ['eye discomfort', 'headache', 'blurred vision'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['dry eyes', 'sensitivity to light', 'difficulty concentrating'], 'diagnosis' => 'eye strain'],
    ['symptoms' => ['neck or shoulder pain', 'watery eyes', 'difficulty keeping eyes open'], 'diagnosis' => 'eye strain'],
];


function trainTestSplit($dataset, $testSize = 0.2) {
    $shuffled = $dataset;
    shuffle($shuffled);
    $testCount = (int)($testSize * count($shuffled));
    return [
        'train' => array_slice($shuffled, $testCount),
        'test' => array_slice($shuffled, 0, $testCount)
    ];
}

function calculateAccuracy($classifier, $testSet) {
    $correct = 0;
    foreach ($testSet as $data) {
        $prediction = $classifier->predict($data['symptoms']);
        if ($prediction === $data['diagnosis']) {
            $correct++;
        }
    }
    return $correct / count($testSet);
}

$split = trainTestSplit($dataset);
$trainSet = $split['train'];
$testSet = $split['test'];

$naiveBayes = new NaiveBayesClassifier();
$naiveBayes->train($trainSet);

$accuracy = calculateAccuracy($naiveBayes, $testSet);
echo "Accuracy: " . ($accuracy * 100) . "%\n";

?>