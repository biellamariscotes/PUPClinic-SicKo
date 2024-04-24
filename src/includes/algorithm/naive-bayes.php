    <?php

    class NaiveBayesClassifier {
        private $priorProbabilities = [];
        private $likelihoods = [];

        public function train($dataset) {

            // Calculate Prior Probabilities
            $this->calculatePriorProbabilities($dataset);

            // Step 3: Calculate Likelihoods
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
                        // Assume a very small likelihood for unseen symptoms
                        $likelihood *= 0.000001;
                    }
                }
                $predictions[$diagnosis] = $likelihood * $priorProbability;
            }
    
            // Select the diagnosis with the highest probability
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
        ['symptoms' => ['fever', 'cough', 'fatigue'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'flu'],
        ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'sore throat'], 'diagnosis' => 'strep throat'],
        ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
        ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['cough', 'congestion'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'body aches'], 'diagnosis' => 'flu'],
        ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['sore throat', 'runny nose', 'headache'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'sweating'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'cough', 'fatigue'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'flu'],
        ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'sore throat'], 'diagnosis' => 'strep throat'],
        ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
        ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['cough', 'congestion'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'body aches'], 'diagnosis' => 'flu'],
        ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['sore throat', 'runny nose', 'headache'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'sweating'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'cough', 'fatigue'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'flu'],
        ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'sore throat'], 'diagnosis' => 'strep throat'],
        ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
        ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['cough', 'congestion'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'body aches'], 'diagnosis' => 'flu'],
        ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['sore throat', 'runny nose', 'headache'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'sweating'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'cough', 'fatigue'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'flu'],
        ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'sore throat'], 'diagnosis' => 'strep throat'],
        ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
        ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['cough', 'congestion'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'body aches'], 'diagnosis' => 'flu'],
        ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['sore throat', 'runny nose', 'headache'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'sweating'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'cough', 'fatigue'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'cough', 'sore throat'], 'diagnosis' => 'flu'],
        ['symptoms' => ['fatigue', 'headache', 'nausea'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'sore throat'], 'diagnosis' => 'strep throat'],
        ['symptoms' => ['fatigue', 'headache', 'muscle pain'], 'diagnosis' => 'flu'],
        ['symptoms' => ['nausea', 'vomiting', 'abdominal pain'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['headache', 'sore throat'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['cough', 'congestion'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'body aches'], 'diagnosis' => 'flu'],
        ['symptoms' => ['diarrhea', 'abdominal cramps'], 'diagnosis' => 'food poisoning'],
        ['symptoms' => ['fever', 'chills', 'chest pain'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'headache', 'fatigue'], 'diagnosis' => 'flu'],
        ['symptoms' => ['sore throat', 'runny nose', 'headache'], 'diagnosis' => 'common cold'],
        ['symptoms' => ['fever', 'chills', 'sweating'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'chills', 'fatigue'], 'diagnosis' => 'malaria'],
        ['symptoms' => ['fever', 'sweating', 'body aches'], 'diagnosis' => 'dengue fever'],
        ['symptoms' => ['fever', 'joint pain', 'rash'], 'diagnosis' => 'chikungunya'],
        ['symptoms' => ['fever', 'headache', 'vomiting'], 'diagnosis' => 'typhoid fever'],
        ['symptoms' => ['fever', 'cough', 'chest congestion'], 'diagnosis' => 'pneumonia'],
        ['symptoms' => ['fever', 'sore throat', 'hoarseness'], 'diagnosis' => 'tonsillitis'],
        ['symptoms' => ['fever', 'abdominal pain', 'bloody stool'], 'diagnosis' => 'dysentery'],
        ['symptoms' => ['fever', 'joint swelling', 'redness'], 'diagnosis' => 'rheumatoid arthritis'],
        ['symptoms' => ['fever', 'swollen lymph nodes', 'fatigue'], 'diagnosis' => 'infectious mononucleosis'],
        ['symptoms' => ['fever', 'cough', 'shortness of breath'], 'diagnosis' => 'bronchitis'],
        ['symptoms' => ['fever', 'muscle stiffness', 'headache'], 'diagnosis' => 'meningitis'],
        ['symptoms' => ['fever', 'red eyes', 'runny nose'], 'diagnosis' => 'measles'],
        ['symptoms' => ['fever', 'itchy skin', 'hives'], 'diagnosis' => 'urticaria'],
        ['symptoms' => ['fever', 'joint pain', 'conjunctivitis'], 'diagnosis' => 'zika virus'],
        ['symptoms' => ['fever', 'abdominal pain', 'yellowing of skin'], 'diagnosis' => 'hepatitis'],
        ['symptoms' => ['fever', 'vomiting blood', 'confusion'], 'diagnosis' => 'hemorrhagic fever'],
        ['symptoms' => ['fever', 'difficulty swallowing', 'neck stiffness'], 'diagnosis' => 'tetanus'],
        ['symptoms' => ['fever', 'painful urination', 'abdominal pain'], 'diagnosis' => 'urinary tract infection'],
        ['symptoms' => ['fever', 'swelling in legs', 'shortness of breath'], 'diagnosis' => 'heart failure'],
        ['symptoms' => ['fever', 'numbness in limbs', 'visual disturbances'], 'diagnosis' => 'migraine']
    ];


    $naiveBayes = new NaiveBayesClassifier();
    $naiveBayes->train($dataset);

    ?>