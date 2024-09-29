const statesAndDistricts = {
    "Andaman and Nicobar Islands": ["North and Middle Andaman", "South Andaman", "Nicobar"],
    "Andhra Pradesh": ["Ananthapur", "Chittoor", "East Godavari", "Guntur", "Krishna", "Kurnool", "Nellore", "Prakasam", "Srikakulam", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR Kadapa"],
    "Arunachal Pradesh": ["Anjaw", "Changlang", "Dibang Valley", "East Kameng", "East Siang", "Kurung Kumey", "Lohit", "Longding", "Namsai", "Papum Pare", "Tawang", "Tirap", "Upper Siang", "Upper Subansiri"],
    "Assam": ["Bajali", "Baksa", "Barpeta", "Biswanath", "Bongaigaon", "Cachar", "Charaideo", "Chirang", "Darrang", "Dhemaji", "Dhubri", "Dibrugarh", "Golaghat", "Hailakandi", "Jorhat", "Kamrup", "Karbi Anglong", "Karimganj", "Kokrajhar", "Lakhimpur", "Morigaon", "Nagaon", "Nalbari", "Sivasagar", "Sonitpur", "Tinsukia", "Udalguri", "West Karbi Anglong"],
    "Bihar": ["Araria", "Aurangabad", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Kaimur", "Katihar", "Khagaria", "Madhepura", "Madhubani", "Muzaffarpur", "Nalanda", "Nawada", "Patna", "Purnia", "Rohtas", "Saharsa", "Samastipur", "Saran", "Sheikhpura", "Sheohar", "Sitamarhi", "Siwan", "Supaul", "Vaishali", "West Champaran"],
    "Chandigarh": ["Chandigarh"],
    "Chhattisgarh": ["Balod", "Baloda Bazar", "Bastar", "Bemetara", "Bijapur", "Bilaspur", "Dantewada", "Dhamtari", "Durg", "Gariaband", "Janjgir-Champa", "Jashpur", "Kanker", "Korba", "Kondagaon", "Mahasamund", "Narayanpur", "Raigarh", "Raipur", "Rajnandgaon", "Sukma", "Surguja"],
    "Dadra and Nagar Haveli and Daman and Diu": ["Dadra and Nagar Haveli", "Daman", "Diu"],
    "Delhi": ["Central Delhi", "East Delhi", "New Delhi", "North Delhi", "North East Delhi", "North West Delhi", "South Delhi", "South East Delhi", "South West Delhi", "West Delhi"],
    "Goa": ["North Goa", "South Goa"],
    "Gujarat": ["Ahmedabad", "Amreli", "Anand", "Banaskantha", "Bharuch", "Bhavnagar", "Dahod", "Dangs", "Gandhinagar", "Kutch", "Kheda", "Mahisagar", "Mehsana", "Narmada", "Navsari", "Panchmahal", "Patan", "Porbandar", "Rajkot", "Sabarkantha", "Surat", "Surendranagar", "Vadodara", "Valsad"],
    "Haryana": ["Ambala", "Bhiwani", "Charkhi Dadri", "Faridabad", "Fatehabad", "Gurugram", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Mahendragarh", "Nuh", "Panchkula", "Panipat", "Rewari", "Rohtak", "Sirsa", "Sonipat", "Yamunanagar"],
    "Himachal Pradesh": ["Bilaspur", "Chamba", "Hamirpur", "Kangra", "Kinnaur", "Kullu", "Lahaul and Spiti", "Shimla", "Sirmaur", "Solan", "Una"],
    "Jammu and Kashmir": ["Jammu", "Kathua", "Kishtwar", "Poonch", "Rajouri", "Reasi", "Samba", "Doda", "Ganderbal", "Jammu", "Pulwama", "Shopian", "Srinagar", "Anantnag", "Bandipora", "Baramulla", "Kupwara", "Budgam"],
    "Jharkhand": ["Bokaro", "Chatra", "Deoghar", "Dhanbad", "Dumka", "East Singhbhum", "Garhwa", "Ghazipur", "Godda", "Gumla", "Hazaribagh", "Jamtara", "Khunti", "Koderma", "Latehar", "Lohardaga", "Pakur", "Palamu", "Ranchi", "Sahibganj", "Seraikela Kharsawan", "Simdega", "West Singhbhum"],
    "Karnataka": ["Bagalkot", "Ballari", "Bangalore Rural", "Bangalore Urban", "Belagavi", "Bellary", "Bidar", "Chamarajanagar", "Chikkamagaluru", "Chikkaballapura", "Chitradurga", "Dakshina Kannada", "Davanagere", "Dharwad", "Gadag", "Hassan", "Haveri", "Kodagu", "Kolar", "Koppal", "Mandya", "Mysuru", "Raichur", "Ramanagara", "Shimoga", "Tumakuru", "Udupi", "Uttara Kannada", "Vijayapura", "Yadgir"],
    "Kerala": ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasargod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur", "Wayanad"],
    "Ladakh": ["Leh", "Kargil"],
    "Lakshadweep": ["Lakshadweep"],
    "Madhya Pradesh": ["Agar Malwa", "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chhindwara", "Dewas", "Dhar", "Guna", "Gwalior", "Harda", "Hoshangabad", "Indore", "Jabalpur", "Jhabua", "Katni", "Khandwa", "Khargone", "Mandla", "Mandsaur", "Narsinghpur", "Neemuch", "Panna", "Rajgarh", "Ratlam", "Sagar", "Satna", "Sehore", "Shahdol", "Shajapur", "Sheopur", "Sidhi", "Singrauli", "Tikamgarh", "Ujjain", "Umaria", "Vidisha"],
    "Maharashtra": ["Ahmednagar", "Akola", "Amravati", "Aurangabad", "Beed", "Bhandara", "Buldhana", "Chandrapur", "Dhule", "Gadchiroli", "Gondia", "Hingoli", "Jalna", "Jalgaon", "Kolhapur", "Latur", "Mumbai", "Nagpur", "Nanded", "Nashik", "Osmanabad", "Parbhani", "Pune", "Raigad", "Ratnagiri", "Sindhudurg", "Solapur", "Thane", "Wardha", "Washim", "Yavatmal"],
    "Manipur": ["Bishnupur", "Chandel", "Churachandpur", "Imphal East", "Imphal West", "Jiribam", "Kakching", "Kamjong", "Kangpokpi", "Noney", "Pherzawl", "Senapati", "Tamenglong", "Tengnoupal", "Thoubal", "Ukhrul"],
    "Meghalaya": ["East Garo Hills", "East Khasi Hills", "North Garo Hills", "Ri Bhoi", "South Garo Hills", "South Khasi Hills", "West Garo Hills", "West Khasi Hills"],
    "Mizoram": ["Aizawl", "Champhai", "Kolasib", "Lunglei", "Mamit", "Saiha", "Serchhip"],
    "Nagaland": ["Dimapur", "Kiphire", "Kohima", "Longleng", "Mokokchung", "Mon", "Peren", "Phek", "Tuensang", "Wokha", "Zunheboto"],
    "Odisha": ["Angul", "Balangir", "Balasore", "Bargarh", "Bhadrak", "Bolangir", "Deogarh", "Dhenkanal", "Ganjam", "Gajapati", "Jagatsinghpur", "Jajpur", "Kandhamal", "Kendrapara", "Kendujhar", "Khurda", "Nabarangpur", "Nayagarh", "Nuapada", "Puri", "Rayagada", "Sambalpur", "Subarnapur", "Sundargarh"],
    "Puducherry": ["Puducherry", "Karaikal", "Mahe", "Yanam"],
    "Punjab": ["Amritsar", "Barnala", "Bathinda", "Faridkot", "Fatehgarh Sahib", "Fazilka", "Gurdaspur", "Hoshiarpur", "Jalandhar", "Kapurthala", "Ludhiana", "Mansa", "Moga", "Patiala", "Rupnagar", "Sangrur", "SAS Nagar", "SBS Nagar", "Sri Muktsar Sahib"],
    "Rajasthan": ["Ajmer", "Alwar", "Banswara", "Baran", "Barmer", "Bikaner", "Chittorgarh", "Churu", "Dausa", "Dholpur", "Dungarpur", "Hanumangarh", "Jaipur", "Jaisalmer", "Jalore", "Jodhpur", "Karauli", "Nagaur", "Pali", "Rajsamand", "Sawai Madhopur", "Sikar", "Tonk", "Udaipur"],
    "Sikkim": ["East Sikkim", "North Sikkim", "South Sikkim", "West Sikkim"],
    "Tamil Nadu": ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kanchipuram", "Kanyakumari", "Karur", "Krishnagiri", "Madurai", "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukkottai", "Ramanathapuram", "Salem", "Sivagangai", "Thanjavur", "Theni", "Tiruchirappalli", "Tirunelveli", "Tiruppur", "Vellore", "Virudhunagar"],
    "Telangana": ["Adilabad", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Mahabubnagar", "Mancherial", "Medak", "Medchal–Malkajgiri", "Nalgonda", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Rangareddy", "Siddipet", "Suryapet", "Vikarabad", "Warangal Rural", "Warangal Urban", "Yadadri Bhuvanagiri"],
    "Tripura": ["Dhalai", "Gomati", "North Tripura", "South Tripura", "Sepahijala", "Unakoti", "West Tripura"],
    "Uttar Pradesh": ["Agra", "Aligarh", "Ambedkarnagar", "Auraiya", "Azamgarh", "Badaun", "Bahraich", "Banda", "Barabanki", "Bareilly", "Bijnor", "Budaun", "Bulandshahr", "Chandauli", "Chitrakoot", "Deoria", "Etah", "Etawah", "Faizabad", "Farrukhabad", "Fatehpur", "Firozabad", "Gautam Buddha Nagar", "Ghaziabad", "Ghazipur", "Gonda", "Gorakhpur", "Hamirpur", "Hapur", "Hardoi", "Hathras", "Jhansi", "Kannauj", "Kanpur Dehat", "Kanpur Nagar", "Kanshiram Nagar", "Kheri", "Lakhimpur Kheri", "Lalitpur", "Lucknow", "Maharajganj", "Mahoba", "Mainpuri", "Mathura", "Mau", "Meerut", "Mirzapur", "Moradabad", "Muzaffarnagar", "Pilibhit", "Pratapgarh", "Raebareli", "Rampur", "Saharanpur", "Sant Ravidas Nagar", "Shahjahanpur", "Shrawasti", "Siddharthnagar", "Sitapur", "Sonbhadra", "Sultanpur", "Unnao", "Varanasi"],
    "Uttarakhand": ["Almora", "Bageshwar", "Chamoli", "Champawat", "Dehradun", "Haridwar", "Nainital", "Pauri Garhwal", "Pithoragarh", "Rudraprayag", "Tehri Garhwal", "Udham Singh Nagar", "Uttarkashi"],    
    "West Bengal": ["Alipurduar", "Bankura", "Bardhaman", "Birbhum", "Cooch Behar", "Dakshin Dinajpur", "Darjeeling", "Hooghly", "Howrah", "Jalpaiguri", "Jhargram", "Kalimpong", "Koch Bihar", "Malda", "Murshidabad", "Nadia", "North 24 Parganas", "Purba Medinipur", "Purulia", "South 24 Parganas", "Uttar Dinajpur", "Paschim Medinipur", "Siliguri", "Sundarbans"]
};

// Function to update districts based on the selected state
function updateDistricts() {
    var stateSelect = document.getElementById("inputState");
    var districtSelect = document.getElementById("inputDistrict");
    var selectedState = stateSelect.value;

    // Clear the current districts
    districtSelect.innerHTML = '<option selected hidden>Select District</option>';

    // If the state is found in the array, update the district options
    if (statesAndDistricts[selectedState]) {
        statesAndDistricts[selectedState].forEach(function (district) {
            var option = document.createElement("option");
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }
}

// Event listener for state dropdown change
document.getElementById("inputState").addEventListener("change", updateDistricts);
