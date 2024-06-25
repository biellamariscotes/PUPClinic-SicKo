<?php
require_once('includes/session-nurse.php');
require_once('includes/connect.php');
require_once('includes/algorithm/naive-bayes.php');

function performDiagnosis($symptoms) {
    global $naiveBayes;
    $predictedSicknesses = [];
    $suggestedTreatments = [];

    // Predict sickness based on symptoms
    $predictedSickness = $naiveBayes->predict($symptoms);
    
    // Recommend treatment based on predicted sickness
    switch ($predictedSickness) {
        case "common cold":
            $suggestedTreatments[] = "Antihistamine";
            break;
        case "flu":
            $suggestedTreatments[] = "Tamiflu/Relenza/Rapivab";
            break;
        case "food poisoning":
            $suggestedTreatments[] = "Loperamide";
            break;
        case "strep throat":
            $suggestedTreatments[] = "Penicilin/Amoxicillin";
            break;
        case "pneumonia":
            $suggestedTreatments[] = "Zithromax";
            break;
        case "malaria":
            $suggestedTreatments[] = "Malarone";
            break;
        case "chikungunya":
            $suggestedTreatments[] = "No Medication";
            break;
        case "typhoid fever":
            $suggestedTreatments[] = "Ciprofloxacin/Azithromycin";
            break;
        case "dengue fever":
            $suggestedTreatments[] = "Acetaminophen";
            break;
        case "tonsillitis":
            $suggestedTreatments[] = "Penicillin";
            break;
        case "dysentery":
            $suggestedTreatments[] = "Loperamide";
            break;
        case "rheumatoid arthritis":
            $suggestedTreatments[] = "Nabumetone";
            break;
        case "infectious mononucleosis":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "bronchitis":
            $suggestedTreatments[] = "Carbocisteine";
            break;
        case "meningitis":
            $suggestedTreatments[] = "Cefotaxime";
            break;
        case "measles":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "urticaria":
            $suggestedTreatments[] = "Cetirizine";
            break;
        case "zika virus":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "hepatitis":
            $suggestedTreatments[] = "Revovir";
            break;
        case "hemorrhagic fever":
            $suggestedTreatments[] = "No Medicine";
            break;
        case "tetanus":
            $suggestedTreatments[] = "Metronidazole";
            break;
        case "urinary tract infection":
            $suggestedTreatments[] = "Ciprofloxacin/Levofloxacin";
            break;
        case "heart failure":
            $suggestedTreatments[] = "Angiotensin";
            break;
        case "migraine":
            $suggestedTreatments[] = "Sumatriptan";
            break;
        case "low blood pressure":
            $suggestedTreatments[] = "phenylephrine";
            break;
        case "anemia":
            $suggestedTreatments[] = "Ferrous sulfate";
            break;
        case "diarrhea":
            $suggestedTreatments[] = "loperamide, bismuth subsalicylate";
            break;
        case "asthma":
            $suggestedTreatments[] = "Inhaled corticosteroids";
            break;
        case "allergic rhinitis":
            $suggestedTreatments[] = "cetirizine, loratadine";
            break;
        case "sinusitis":
            $suggestedTreatments[] = "ibuprofen, acetaminophen, decongestants";
            break;
        case "conjunctivitis":
            $suggestedTreatments[] = "eye drops";
            break;
        case "chickenpox":
            $suggestedTreatments[] = "acyclovir";
            break;
        case "vertigo":
            $suggestedTreatments[] = "prochlorperazine, cinnarizine";
            break;
        case "stress":
            $suggestedTreatments[] = "beta-blockers, selective serotonin reuptake inhibitors";
            break;
        case "heartburn":
            $suggestedTreatments[] = "Antacids";
            break;
        case "eye strain":
            $suggestedTreatments[] = "nonsteroidal anti-inflammatory drug (NSAID)";
            break;
        case "cataracts":
            $suggestedTreatments[] = "eye drop for cataracts";
            break;
        case "glaucoma":
            $suggestedTreatments[] = "latanoprost, travoprost";
            break;
        case "colon cancer":
            $suggestedTreatments[] = "Capecitabine, Irinotecan, Oxaliplatin, Lonsurf";
            break;
        case "dermatitis (eczema)":
            $suggestedTreatments[] = "Corticosteroids";
            break;
        default:
            $suggestedTreatments[] = "No specific treatment recommended.";
            break;
    }

    return [$predictedSickness, $suggestedTreatments];
}

function getSicknessMeaning($sickness) {
    $meanings = [
        "common cold" => "A viral infection causing congestion, cough, and sore throat in your nose, sinuses, throat and windpipe.",
        "flu" => "Also called influenza, is an infection of the nose, throat and lungs, which are part of the respiratory system. The flu is caused by a virus.",
        "food poisoning" => "An infection or irritation of your digestive tract that spreads through food or drinks. Viruses, bacteria, and parasites cause most food poisoning. Harmful chemicals may also cause food poisoning.",
        "strep throat" => "A bacterial infection that can make your throat feel sore and scratchy. Strep throat accounts for only a small portion of sore throats. If untreated, strep throat can cause complications, such as kidney inflammation or rheumatic fever.",
        "pneumonia" => "An infection that affects one or both lungs. It causes the air sacs, or alveoli, of the lungs to fill up with fluid or pus. Bacteria, viruses, or fungi may cause pneumonia.",
        "malaria" => "A disease caused by a parasite. The parasite is spread to humans through the bites of infected mosquitoes. People who have malaria usually feel very sick with a high fever and shaking chills. While the disease is uncommon in temperate climates, malaria is still common in tropical and subtropical countries.",
        "chikungunya" => "Derives from a word in the Kimakonde language of southern Tanzania, meaning “to become contorted”, and describes the stooped appearance of sufferers with joint pain (arthralgia). Chikungunya is transmitted to humans by the bites of infected female mosquitoes.",
        "typhoid fever" => "A life-threatening infection caused by the bacterium Salmonella Typhi. It is usually spread through contaminated food or water. Once Salmonella Typhi bacteria are ingested, they multiply and spread into the bloodstream.",
        "dengue fever" => "A viral infection that spreads from mosquitoes to people. It is more common in tropical and subtropical climates.A mosquito-borne viral disease causing high fever, rash, and muscle and joint pain.",
        "tonsillitis" => "Inflammation of the tonsils, two oval-shaped pads of tissue at the back of the throat — one tonsil on each side. Signs and symptoms of tonsillitis include swollen tonsils, sore throat, difficulty swallowing and tender lymph nodes on the sides of the neck.",
        "dysentery" => "An infection in your intestines that causes bloody diarrhea. It can be caused by a parasite or bacteria.",
        "rheumatoid arthritis" => "An autoimmune and inflammatory disease, which means that your immune system attacks healthy cells in your body by mistake, causing inflammation (painful swelling) in the affected parts of the body.",
        "infectious mononucleosis" => "A type of infection. It causes swollen lymph glands, fever, sore throat, and often extreme fatigue. It's often spread through contact with infected saliva from the mouth. Symptoms can take between 4 to 6 weeks to appear.",
        "bronchitis" => "A condition that develops when the airways in the lungs, called bronchial tubes, become inflamed and cause coughing, often with mucus production.",
        "meningitis" => "An infection and inflammation of the fluid and membranes surrounding the brain and spinal cord. These membranes are called meninges.",
        "measles" => "A very contagious disease that causes fever, a red rash, cough and watery eyes. It can have serious complications in some people. ",
        "urticaria" => "A raised, itchy rash that appears on the skin. Children are often affected by the condition, as well as women aged 30 to 60, and people with a history of allergies. Hives rashes usually improve within a few minutes to a few days.",
        "zika virus" => "A mosquito-borne virus, similar to dengue fever, yellow fever and West Nile virus. The infection is associated with a birth defect called microcephaly, which can affect babies born to people who become infected with Zika while pregnant.",
        "hepatitis" => "Inflammation of the liver. The liver is a vital organ that processes nutrients, filters the blood, and fights infections. When the liver is inflamed or damaged, its function can be affected. Heavy alcohol use, toxins, some medications, and certain medical conditions can cause hepatitis.",
        "hemorrhagic fever" => " infectious diseases that can cause severe, life-threatening illness. They can damage the walls of tiny blood vessels, making them leak, and can hamper the blood's ability to clot. The resulting internal bleeding is usually not life-threatening, but the diseases can be.",
        "tetanus" => "A serious disease of the nervous system caused by a toxin-producing bacterium. The disease causes muscle contractions, particularly of your jaw and neck muscles. Tetanus is commonly known as lockjaw. Severe complications of tetanus can be life-threatening. There's no cure for tetanus.",
        "urinary tract infection" => "An infection in any part of the urinary system.",
        "heart failure" => "Occurs when the heart muscle doesn't pump blood as well as it should. Blood often backs up and causes fluid to build up in the lungs and in the legs. The fluid buildup can cause shortness of breath and swelling of the legs and feet. Poor blood flow may cause the skin to appear blue or gray.",
        "migraine" => "a headache that can cause severe throbbing pain or a pulsing sensation, usually on one side of the head. It's often accompanied by nausea, vomiting, and extreme sensitivity to light and sound.",
        "low blood pressure" => "Low blood pressure occurs when blood pressure is much lower than normal. This means the heart, brain, and other parts of the body may not get enough blood. Normal blood pressure is mostly between 90/60 mmHg and 120/80 mmHg. The medical word for low blood pressure is hypotension.",
        "anemia" => "A problem of not having enough healthy red blood cells or hemoglobin to carry oxygen to the body's tissues. Hemoglobin is a protein found in red cells that carries oxygen from the lungs to all other organs in the body. Having anemia can cause tiredness, weakness and shortness of breath.",
        "diarrhea" => "Loose, watery and possibly more-frequent bowel movements — is a common problem. Sometimes, it's the only symptom. At other times, it may be associated with other symptoms, such as nausea, vomiting, abdominal pain or weight loss. Luckily, diarrhea is usually short-lived, lasting no more than a few days.",
        "asthma" => "A condition in which your airways narrow and swell and may produce extra mucus. This can make breathing difficult and trigger coughing, a whistling sound (wheezing) when you breathe out and shortness of breath.",
        "allergic rhinitis" => "An atopic disease presenting with symptoms of sneezing, nasal congestion, clear rhinorrhea, and nasal pruritis. It is an IgE-mediated immune response that is against inhaled antigens in the immediate phase, with a subsequent leukotriene-mediated late phase.",
        "sinusitis" => "Present when the tissue lining the sinuses become swollen or inflamed. It occurs as the result of an inflammatory reaction or an infection from a virus, bacteria, or fungus.",
        "conjunctivitis" => "More commonly known as pink eye or sore eyes, is a very common disease. Conjunctivitis is an inflammation of the conjunctiva, the thin, clear layer that covers the white surface of the eye and the inner eyelid.",
        "chickenpox" => "An infectious disease causing a mild fever and a rash of itchy inflamed blisters. It is caused by the herpes zoster virus and mainly affects children, who are afterward usually immune.",
        "vertigo" => "A sensation of motion or spinning that is often described as dizziness. Vertigo is not the same as being lightheaded. People with vertigo feel as though they are actually spinning or moving, or that the world is spinning around them.",
        "mononucleosis" => "A viral infection that causes a sore throat and fever. Cases often happen in teens and young adults. It goes away on its own after a few weeks of rest.",
        "gastroenteritis" => "An inflammation of the lining of the stomach and intestines. The main symptoms include vomiting and diarrhea. It is usually not serious in healthy people, but it can sometimes lead to dehydration or cause severe symptoms.",
        "stress" => "A feeling of emotional or physical tension. It can come from any event or thought that makes you feel frustrated, angry, or nervous.",
        "ear infection" => "An infection of the middle ear, the air-filled space behind the eardrum that contains the tiny vibrating bones of the ear. Children are more likely than adults to get ear infections.",
        "appendicitis" => "Inflammation of your appendix, a finger-like pouch attached to your large intestine. The appendix is in the lower-right area of your abdomen, or belly. Your appendix is in the lower-right area of your abdomen, near where the small intestine attaches to the large intestine.",
        "measles" => "An infectious viral disease causing fever and a red rash on the skin, typically occurring in childhood.",
        "mumps" => "An illness caused by a virus. It usually affects the glands on each side of the face. These glands, called parotid glands, make saliva. Swollen glands may be tender or painful.",
        "hyperventilation" => "Rapid or deep breathing, usually caused by anxiety or panic. This overbreathing, as it is sometimes called, may actually leave you feeling breathless.",
        "hypothermia" => "A condition that occurs when core body temperature drops below 95 degrees Fahrenheit (35 degrees Celsius). It is a medical emergency. In hypothermia (hi-poe-THUR-me-uh), the body loses heat faster than it can produce heat, causing a dangerously low body temperature.",
        "heartburn" => "An irritation of your esophagus, the tube that connects your throat and stomach. This leads to a burning discomfort in your upper belly or chest. It's caused by acid reflux, which is when your lower esophageal sphincter (LES) muscle doesn't close properly, letting stomach acids back up into your esophagus.",
        "indigestion" => "Also called dyspepsia or an upset stomach — is discomfort in your upper abdomen. Indigestion describes certain symptoms, such as belly pain and a feeling of fullness soon after you start eating, rather than a specific disease. Indigestion can also be a symptom of other digestive disorders.",
        "dysmenorrhea" => "Medical term for moderate to severe pain caused by menstrual periods: Primary dysmenorrhea may begin one to three days before your period and last until two to three days after the onset of menstruation. It typically includes a collection of other symptoms, such as nausea, vomiting, and fatigue.",
        "ulcer" => "A break on the skin, in the lining of an organ, or on the surface of a tissue. An ulcer forms when the surface cells become inflamed, die, and are shed. Ulcers may be linked to cancer and other diseases.",
        "fever" => "An abnormally high body temperature, usually accompanied by shivering, headache, and in severe instances, delirium.",
        "diabetes" => "A chronic, metabolic disease characterized by elevated levels of blood glucose (or blood sugar), which leads over time to serious damage to the heart, blood vessels, eyes, kidneys and nerves.",
        "over fatigue" => "Excessive fatigue especially when carried beyond the recuperative capacity of the individual.",
        "eye strain" => "A common condition that occurs when your eyes get tired from intense use, such as while driving long distances or staring at computer screens and other digital devices.",
        "stroke" => "A loss of blood flow to part of the brain, which damages brain tissue. Strokes are caused by blood clots and broken blood vessels in the brain. Symptoms include dizziness, numbness, weakness on one side of the body, and problems with talking, writing, or understanding language.",
        "hyperthyroidism" => "Happens when the thyroid gland makes too much thyroid hormone. This condition also is called overactive thyroid. Hyperthyroidism speeds up the body's metabolism. That can cause many symptoms, such as weight loss, hand tremors, and rapid or irregular heartbeat.",
        "hypothyroidism" => "Also called underactive thyroid, is when the thyroid gland doesn't make enough thyroid hormones to meet your body's needs. The thyroid is a small, butterfly-shaped gland in the front of your neck.",
        "gallstones" => "Are hard, pebble-like pieces of material, usually made of cholesterol or bilirubin, that form in your gallbladder. Gallstones can range in size from a grain of sand to a golf ball. The gallbladder can make one large gallstone, hundreds of tiny stones, or both small and large stones.",
        "hepatitis" => "An inflammation of the liver that is caused by a variety of infectious viruses and noninfectious agents leading to a range of health problems, some of which can be fatal.",
        "hepatitis b" => "An infection of the liver caused by the hepatitis B virus. The infection can be acute (short and severe) or chronic (long term). Hepatitis B can cause a chronic infection and puts people at high risk of death from cirrhosis and liver cancer.",
        "hepatitis c" => "An inflammation of the liver caused by the hepatitis C virus. The virus can cause both acute and chronic hepatitis, ranging in severity from a mild illness to a serious, lifelong illness including liver cirrhosis and cancer.",
        "irritable bowel syndrome" => "A group of symptoms that occur together, including repeated pain in your abdomen and changes in your bowel movements, which may be diarrhea, constipation, or both. With IBS, you have these symptoms without any visible signs of damage or disease in your digestive tract.",
        "celiac disease" => "A chronic digestive and immune disorder that damages the small intestine. The disease is triggered by eating foods containing gluten. The disease can cause long-lasting digestive problems and keep your body from getting all the nutrients it needs.",
        "chronic kidney disease" => "A long-term condition where the kidneys do not work as well as they should. It's a common condition often associated with getting older. It can affect anyone, but it's more common in people who are black or of south Asian origin.",
        "chronic obstructive pulmonary disease" => "is a common lung disease causing restricted airflow and breathing problems. It is sometimes called emphysema or chronic bronchitis. In people with COPD, the lungs can get damaged or clogged with phlegm.",
        "gout" => "A type of inflammatory arthritis that causes pain and swelling in your joints, usually as flares that last for a week or two, and then resolve. Gout flares often begin in your big toe or a lower limb.",
        "rheumatoid arthritis" => "A chronic (long-lasting) autoimmune disease that mostly affects joints. RA occurs when the immune system, which normally helps protect the body from infection and disease, attacks its own tissues. The disease causes pain, swelling, stiffness, and loss of function in joints.",
        "depression" => "A common mental disorder. It involves a depressed mood or loss of pleasure or interest in activities for long periods of time. Depression is different from regular mood changes and feelings about everyday life.",
        "anxiety disorder" => "A condition in which a person has excessive worry and feelings of fear, dread, and uneasiness. Other symptoms may include sweating, restlessness, irritability, fatigue, poor concentration, trouble sleeping, trouble breathing, a fast heartbeat, and dizziness.",
        "pancreatitis" => "Pancreatitis is inflammation of the pancreas. The pancreas is a large gland behind the stomach, close to the first part of the small intestine, called the duodenum.",
        "gastritis" => "A redness and swelling (inflammation) of the stomach lining. It can be caused by drinking too much alcohol, certain medicines, or smoking. Some diseases and other health issues can also cause gastritis.",
        "tuberculosis" => "An infectious disease that most often affects the lungs and is caused by a type of bacteria. It spreads through the air when infected people cough, sneeze or spit. Tuberculosis is preventable and curable.",
        "hypertension" => "When the pressure in your blood vessels is too high (140/90 mmHg or higher).",
        "heart failure" => "A condition that develops when your heart doesn't pump enough blood for your body's needs. This can happen if your heart can't fill up with enough blood. It can also happen when your heart is too weak to pump properly.",
        "coronary artery disease" => "A condition that affects your heart. It is the most common heart disease in the United States. CAD happens when coronary arteries struggle to supply the heart with enough blood, oxygen and nutrients.",
        "arrhythmia" => "Or irregular heartbeat, is a problem with the rate or rhythm of your heartbeat. Your heart may beat too quickly, too slowly, or with an irregular rhythm.",
        "alzheimer\'s disease" => "The most common type of dementia. It is a progressive disease beginning with mild memory loss and possibly leading to loss of the ability to carry on a conversation and respond to the environment.",
        "bipolar disorder" => "A mental illness that causes unusual shifts in a person's mood, energy, activity levels, and concentration. These shifts can make it difficult to carry out day-to-day tasks. There are three types of bipolar disorder.",
        "epilepsy" => "A neurological condition involving the brain that makes people more susceptible to having recurrent unprovoked seizures. It is one of the most common disorders of the nervous system and affects people of all ages, races and ethnic background.",
        "kidney stones" => "Are hard, pebble-like pieces of material that form in one or both of your kidneys when high levels of certain minerals are in your urine. Kidney stones rarely cause permanent damage if treated by a health care professional. Kidney stones vary in size and shape.",
        "shingles" => "A viral infection that causes a painful rash. Shingles can occur anywhere on your body. It typically looks like a single stripe of blisters that wraps around the left side or the right side of your torso. Shingles is caused by the varicella-zoster virus — the same virus that causes chickenpox.",
        "glaucoma" => "A group of eye diseases that can cause vision loss and blindness by damaging a nerve in the back of your eye called the optic nerve.",
        "cataracts" => "A clouding of the lens of the eye, which is typically clear. For people who have cataracts, seeing through cloudy lenses is like looking through a frosty or fogged-up window.",
        "macular degeneration" => "An eye disease that can blur your central vision. It happens when aging causes damage to the macula — the part of the eye that controls sharp, straight-ahead vision.",
        "osteoporosis" => "A bone disease that develops when bone mineral density and bone mass decreases, or when the structure and strength of bone changes. This can lead to a decrease in bone strength that can increase the risk of fractures (broken bones).",
        "lupus" => "An autoimmune disease that makes your immune system damage organs and tissue throughout your body. It causes inflammation that can affect your skin, joints, blood and organs like your kidneys, lungs and heart.",
        "hay fever" => "An allergic reaction that causes sneezing, congestion, itchy nose and watery eyes. Pollen, pet dander, mold and insects can lead to hay fever symptoms.",
        "sleep apnea" => "A potentially serious sleep disorder in which breathing repeatedly stops and starts. If you snore loudly and feel tired even after a full night's sleep, you might have sleep apnea.",
        "hemophilia" => "A rare disorder in which the blood doesn't clot in the typical way because it doesn't have enough blood-clotting proteins (clotting factors). If you have hemophilia, you might bleed for a longer time after an injury than you would if your blood clotted properly.",
        "leukemia" => "A type of cancer found in your blood and bone marrow and is caused by the rapid production of abnormal white blood cells. These abnormal white blood cells are not able to fight infection and impair the ability of the bone marrow to produce red blood cells and platelets.",
        "lymphoma" => "Types of cancer that begin in the lymphatic system (the various lymph glands around the body) when abnormal white blood cells grow. Lymphomas are the sixth most common form of cancer overall (excluding non-melanoma skin cancer).",
        "melanoma" => "A form of cancer that begins in melanocytes (cells that make the pigment melanin). It may begin in a mole (skin melanoma), but can also begin in other pigmented tissues, such as in the eye or in the intestines.",
        "psoriasis" => "A chronic (long-lasting) disease in which the immune system becomes overactive, causing skin cells to multiply too quickly. Patches of skin become scaly and inflamed, most often on the scalp, elbows, or knees, but other parts of the body can be affected as well.",
        "endometriosis" => "a disease in which tissue similar to the lining of the uterus grows outside the uterus. It can cause severe pain in the pelvis and make it harder to get pregnant.",
        "fibroids" => "Are non-cancerous growths in the muscle layer of your uterus (womb). Fibroids are common and can cause painful and heavy periods. Most fibroids do not need treatment.",
        "polycystic ovary syndrome" => "A condition in which the ovaries produce an abnormal amount of androgens, male sex hormones that are usually present in women in small amounts. The name polycystic ovary syndrome describes the numerous small cysts (fluid-filled sacs) that form in the ovaries.",
        "ovarian cancer" => "when abnormal cells in the ovary begin to grow and divide in an uncontrolled way. They eventually form a growth (tumour). If not caught early, cancer cells gradually grow into the surrounding tissues. And may spread to other areas of the body.",
        "prostate cancer" => "Cancer that forms in the tissues of the prostate (a gland in the male reproductive system that lies just below the bladder and in front of the rectum). Prostate cancer usually occurs in older men.",
        "testicular cancer" => "Cancer that forms in tissues of one or both testicles. Testicular cancer is most common in young or middle-aged men. Most testicular cancers begin in germ cells (cells that make sperm) and are called testicular germ cell tumors.",
        "colon cancer" => "Cancer that forms in the tissues of the colon (the longest part of the large intestine). Most colon cancers are adenocarcinomas (cancers that begin in cells that make and release mucus and other fluids).",
        "breast cancer" => "A disease in which abnormal breast cells grow out of control and form tumours. If left unchecked, the tumours can spread throughout the body and become fatal. Breast cancer cells begin inside the milk ducts and/or the milk-producing lobules of the breast.",
        "dermatitis (eczema)" => "A condition that causes dry, itchy and inflamed skin. It's common in young children but can occur at any age. Atopic dermatitis is long lasting (chronic) and tends to flare sometimes. It can be irritating but it's not contagious.",
        "acne" => "a skin condition that occurs when your hair follicles become plugged with oil and dead skin cells. It causes whiteheads, blackheads or pimples. Acne is most common among teenagers, though it affects people of all ages.",
        "hives (urticaria)" => "A raised, itchy rash that appears on the skin. Children are often affected by the condition, as well as women aged 30 to 60, and people with a history of allergies. Hives rashes usually improve within a few minutes to a few days.",
        "ringworm (tinea corporis)" => "Means fungus, the cause of the rash, and “corporis” means the body. It's a superficial fungal skin infection caused by dermatophytes, which are a type of fungus.",
        "athlete\'s foot (tinea pedis)" => "A fungal skin infection that usually begins between the toes. It commonly occurs in people whose feet have become very sweaty while confined within tight-fitting shoes. Signs and symptoms of athlete's foot include an itchy, scaly rash.",
        "warts" => "Warts are raised bumps on your skin caused by the human papillomavirus (HPV). They can be uncomfortable, contagious, and painful.",
        "cold sores (herpes simplex virus)" => "Are small blisters that usually form on the lips or skin around the mouth, nose and on the chin. They are caused by infection with the herpes simplex virus (HSV). People are usually infected in childhood or young adulthood, and the infection persists for life.",
        "scleroderma" => "An autoimmune disease that causes inflammation and fibrosis (thickening) in the skin and other areas of the body.",
        "muscle strain" => "When a muscle is stretched too much and part of it tears. It is also called a pulled muscle. A strain is a painful injury. It can be caused by an accident, overusing a muscle, or using a muscle in the wrong way.",
        "muscle sprain" => "An injury to the ligaments and capsule of a joint in the body.",
        "muscular dystrophy" => "A group of diseases that cause progressive weakness and loss of muscle mass. In muscular dystrophy, abnormal genes (mutations) interfere with the production of proteins needed to form healthy muscle.",
        "compartment syndrome" => "A painful buildup of pressure around your muscles. Acute compartment syndrome is a medical emergency that happens after severe injuries or as a surgery complication.",
        "muscular atrophy" => "The wasting or thinning of muscle mass. It can be caused by disuse of your muscles or neurogenic conditions. Symptoms include a decrease in muscle mass, one limb being smaller than the other, and numbness, weakness and tingling in your limbs.",
        "bone cancer" => "Cancer that forms in cells of the bone. Some types of primary bone cancer are osteosarcoma, Ewing sarcoma, malignant fibrous histiocytoma, and chondrosarcoma. Secondary bone cancer is cancer that spreads to the bone from another part of the body (such as the prostate, breast, or lung).",
        "bone spurs (osteophytes)" => "Are bony lumps that grow on the bones in the spine or around joints. They form when a joint or bone has been damaged by arthritis, but do not always cause problems.",
        "bone cysts" => "Are fluid-filled spots that form in bone. Most go away on their own over time. Bone cysts are generally diagnosed through X-rays, often when a child is being seen for another condition. While there are often no symptoms, bone cysts can cause the bone to be weak enough to fracture when it otherwise wouldn't.",
        "bone marrow cancer (multiple myeloma)" => "A type of blood cancer that develops from plasma cells in the bone marrow.",
        "osteogenesis imperfecta (brittle bone disease)" => "An inherited (genetic) bone disorder that is present at birth. It is also known as brittle bone disease. A child born with OI may have soft bones that break (fracture) easily, bones that are not formed normally, and other problems. Signs and symptoms may range from mild to severe.",
        "osteochondroma" => "An inherited (genetic) bone disorder that is present at birth. It is also known as brittle bone disease. A child born with OI may have soft bones that break (fracture) easily, bones that are not formed normally, and other problems. Signs and symptoms may range from mild to severe.",    
        "cirrhosis" => "A condition in which the liver is scarred and permanently damaged. Scar tissue replaces healthy liver tissue and prevents the liver from working normally. Scar tissue also partly blocks the flow of blood through the liver. As cirrhosis gets worse, the liver begins to fail.",
        "pancreatic cancer" => "A type of cancer that forms in the tissues of the pancreas. Smoking and health history can affect the risk of pancreatic cancer. Signs and symptoms of pancreatic cancer include jaundice, pain, and weight loss. Pancreatic cancer is difficult to diagnose early.",
        "liver cancer" => "A disease in which malignant (cancer) cells form in the tissues of the liver. Cancer that forms in other parts of the body and spreads to the liver is not primary liver cancer.",
        "bladder cancer" => "Cancer that forms in tissues of the bladder (the organ that stores urine). Most bladder cancers are transitional cell carcinomas (cancer that begins in cells that normally make up the inner lining of the bladder).",

    ];

    return $meanings[$sickness] ?? "No information available.";
}

$predictedSickness = "";
$suggestedTreatments = [];
$meaning = "";

if (isset($_POST['symptoms'])) {
    // Get symptoms input
    $symptoms = explode(",", strtolower($_POST['symptoms']));
    
    // Perform diagnosis
    list($predictedSickness, $suggestedTreatments) = performDiagnosis($symptoms);
    $meaning = getSicknessMeaning($predictedSickness);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Diagnosis</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png"> 
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <style>
        .generate-diagnosis-box[disabled="true"] {
            background-color: #D4D4D4;
            color: #fff !important;
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="loader d-flex">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <?php include ('includes/sidebar/ai-basedSDT.php'); ?>

    <div class="content" id="content">
    <div class="dashboard-header-container">
                <img src="images/ai-sdt-header.jpg" alt="Dashboard Header" class="dashboard-header">
                <div class="dashboard-text" >
                    <p>AI-Based, <span class="bold">Symptoms</span></p>
                    <p class="bold" style="color: #E13F3D; font-size: 50px; font-family: 'Poppins', sans-serif;">Diagnostic Tool</p>
                    <p style="color: black; font-size: 17px; font-family: 'Poppins', sans-serif; text-align: justify;">Detects and generates possible diagnosis<br> based on patient symptoms.</p>
                </div>
            </div>

        <div class="left-header">
            <p style="color: #E13F3D; font-size: 40px;">Symptoms</p>
        </div>

        <!-- Symptoms Container -->
        <div class="symptoms-input-container">
            <?php if(isset($_POST['symptoms'])): ?>
                <div class="tags-container" id="tags-container" style="pointer-events: none;">
                    <?php foreach(explode(",", htmlspecialchars($_POST['symptoms'])) as $symptom): ?>
                        <span class="tag"><?= trim($symptom) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <input type="text" id="symptoms-input" name="symptoms" placeholder="Type symptoms keywords..." autocomplete="off">
                <div class="tags-container" id="tags-container"></div>
            <?php endif; ?>
        </div>

        <div class="left-header">
            <p style="color: #E13F3D; font-size: 40px;">Diagnosis</p>
        </div>

        <!-- Diagnosis Container -->
        <?php if(!empty($predictedSickness)): ?>
            <div class="diagnosis-container">
                <div class="diagnosis-box">
                    <div class="medical-condition">
                        <h2 class="medical-condition-header">Medical Condition: <span style="color: #E13F3D;"><?= ucfirst(htmlspecialchars($predictedSickness)) ?></span></h2>
                        <p class="sub-text"><?= htmlspecialchars($meaning) ?></p>
                    </div>
                    <div class="treatment-options-container">
                        <div class="vertical-line"></div>
                        <div class="treatment-options">
                            <h2 class="treatment-options-header">Treatment Options</h2>
                            <ul class="options-list">
                                <?php foreach ($suggestedTreatments as $treatment): ?>
                                    <li><?= htmlspecialchars($treatment) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>No diagnosis found for the given symptoms.</p>
        <?php endif; ?>

        <!-- New container for the two boxes -->
        <div class="new-boxes-container">
            <!-- First box -->
            <div class="back-button" onclick="window.location.href='ai-basedSDT.php'">
                <div class="box-content">
                    <p class="box-text">Back to AI-SDT</p>
                </div>
            </div>

            <!-- Second box -->
            <div class="record-treatment-button" onclick="recordTreatment()">
                <div class="box-content">
                    <p class="box-text">Record Treatment</p>
                    <img src="images/arrow-icon.svg" alt="Arrow Icon">
                </div>
            </div>
        </div>
    </div>

    <?php include ('includes/footer.php'); ?>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>

    <!-- LOADER -->
            <script>
                function simulateContentLoading() {
                    showLoader();
                    setTimeout(function () {
                        hideLoader();
                        showContent();
                    }, 3000);
                }

                function showLoader() {
                    console.log("Showing loader.");
                    document.querySelector('.loader').classList.add('visible');
                }

                function hideLoader() {
                    console.log("Hiding loader with transition.");
                    const loader = document.querySelector('.loader');
                    loader.style.transition = 'opacity 0.5s ease-out';
                    loader.style.opacity = '0';
                    loader.addEventListener('transitionend', function (event) {
                        if (event.propertyName === 'opacity') {
                            loader.classList.remove('d-flex');
                            loader.style.display = 'none';
                        }
                    });
                }

                function showContent() {
                    console.log("Showing content.");
                    const body = document.querySelector('.main-content');
                    body.style.display = 'block';
                }
                simulateContentLoading();
            </script>

            <!-- END OF LOADER -->

    <script>
        function recordTreatment() {
            <?php
            // Get the symptoms, diagnosis, and treatments
            $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : '';
            $diagnosis = $predictedSickness;
            $treatments = implode(", ", $suggestedTreatments);
            ?>

            // Redirect to the next page and pass the parameters
            window.location.href = 'treatment-record.php?symptoms=<?= urlencode($symptoms) ?>&diagnosis=<?= urlencode($diagnosis) ?>&treatments=<?= urlencode($treatments) ?>';
        }

        $(document).ready(function() {
            var symptoms = [];

            $('#symptoms-input').keypress(function(e) {
                if(e.which == 13) {
                    var symptom = $(this).val().trim();
                    if(symptom !== "" && !symptoms.includes(symptom)) {
                        symptoms.push(symptom);
                        $('#tags-container').append('<span class="tag">' + symptom + '</span>');
                        $(this).val('');
                    }
                }
            });
        });
    </script>
</body>
</html>
