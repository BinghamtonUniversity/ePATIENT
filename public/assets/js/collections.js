
Berry.collection.add('category',[
    "Laboratory",
    "Diagnostics",
    "Dietetics",
    "Consult",
    "Procedure",
    "Supply/Device",
    "Care",
    "Specialty Department",
    "Screening/Measurements",
    "Therapy"
]);
Berry.collection.add('frequency',[
    "As directed",
    "As directed PRN",
    "Daily",
    "BID",
    "TID",
    "TID AC",
    "TID PC",
    "QID",
    "Every other day",
    "Continuous",
    "M-W-F",
    "Now",
    "STAT",
    "Once",
    "Q5MIN",
    "Q10MIN",
    "Q15MIN",
    "Q30MIN",
    "Q2H",
    "Q3H",
    "Q4H",
    "Q6H",
    "Q8H",
    "Q12H",
    "Q18H",
    "Q24H",
    "Q36H",
    "PRN Q2H",
    "PRN Q6H",
    "PRN Q8H",
    "PRN Q12H",
    "PRN QHS",
    "QAC",
    "QAC, QHS",
    "QHS",
    "QPC",
    "TODAY",
    "TOMORROW",
    "WEEKLY"
]);
Berry.collection.add('route',[
    "Arterial",
    "Both Ears (AU)",
    "Both Eyes (OU)",
    "By Mouth (PO)",
    "Epidural",
    "G-Tube",
    "Inhalation (INH)",
    "Intracardiac",
    "Intradermal",
    "Intramuscular (IM)",
    "Intrasynovial",
    "Intrathecal",
    "Intravenous (IV)",
    "Irrigation",
    "IV Piggyback (IVPB)",
    "IV Push (IVP)",
    "J Tube",
    "Left Ear (AS)",
    "Left Eye (OS)",
    "Nasal",
    "NG Tube",
    "PCA",
    "Percutanous",
    "Peritoneal",
    "Rectal",
    "Right Ear (AD)",
    "Right Eye (OD)",
    "Spinal",
    "Subcutanous (SQ)",
    "Sublingual (SL)",
    "Topical",
    "Trach",
    "Transdermal",
    "Urethral",
    "Vaginal"
]);
// Berry.collection.add('providers',_.sortBy(data.providers,'last_name'));
// Berry.collection.add('products',_.sortBy(data.products,'name'));
// Berry.collection.add('solutions',_.sortBy(data.solutions,'solution_name'));
// Berry.collection.add('labs',data.labs);
Berry.collection.add('lab_types',
[
    {'name':"abgs","label":"ABG"},
    {'name':"bmp","label":"BMP (Basic Metabolic Panel)"},
    {'name':"cmpanel","label":"Complete Metabolic Panel"},
    {'name':"cbc","label":"CBC with Differential"},
    {'name':"cmprofile","label":"CMP (Comprehensive Metabolic Profile)"},
    {'name':"ck","label":"Creatine kinase (CK) isoenzymes"},
    {'name':"electrolytes","label":"Electrolytes"},
    {'name':"lp","label":"Lipid Profile"},
    {'name':"pfp","label":"Liver Panel (Liver Function Panel)"},
    {'name':"urinalysis","label":"Urinalysis"},
    {'name':"btc","label":"Blood Type & Crossmatch"},
    {'name':"csf","label":"Cerebrospinal Fluid (CSF)"},
    {'name':"coagulation","label":"Coagulation Screen"}
]);






gform.collections.add('category',[
    "Laboratory",
    "Diagnostics",
    "Dietetics",
    "Consult",
    "Procedure",
    "Supply/Device",
    "Care",
    "Specialty Department",
    "Screening/Measurements",
    "Therapy"
]);
gform.collections.add('frequency',[
    "As directed",
    "As directed PRN",
    "Daily",
    "BID",
    "TID",
    "TID AC",
    "TID PC",
    "QID",
    "Every other day",
    "Continuous",
    "M-W-F",
    "Now",
    "STAT",
    "Once",
    "Q5MIN",
    "Q10MIN",
    "Q15MIN",
    "Q30MIN",
    "Q2H",
    "Q3H",
    "Q4H",
    "Q6H",
    "Q8H",
    "Q12H",
    "Q18H",
    "Q24H",
    "Q36H",
    "PRN Q2H",
    "PRN Q6H",
    "PRN Q8H",
    "PRN Q12H",
    "PRN QHS",
    "QAC",
    "QAC, QHS",
    "QHS",
    "QPC",
    "TODAY",
    "TOMORROW",
    "WEEKLY"
]);
gform.collections.add('route',[
    "Arterial",
    "Both Ears (AU)",
    "Both Eyes (OU)",
    "By Mouth (PO)",
    "Epidural",
    "G-Tube",
    "Inhalation (INH)",
    "Intracardiac",
    "Intradermal",
    "Intramuscular (IM)",
    "Intrasynovial",
    "Intrathecal",
    "Intravenous (IV)",
    "Irrigation",
    "IV Piggyback (IVPB)",
    "IV Push (IVP)",
    "J Tube",
    "Left Ear (AS)",
    "Left Eye (OS)",
    "Nasal",
    "NG Tube",
    "PCA",
    "Percutanous",
    "Peritoneal",
    "Rectal",
    "Right Ear (AD)",
    "Right Eye (OD)",
    "Spinal",
    "Subcutanous (SQ)",
    "Sublingual (SL)",
    "Topical",
    "Trach",
    "Transdermal",
    "Urethral",
    "Vaginal"
]);
// Berry.collection.add('providers',_.sortBy(data.providers,'last_name'));
// Berry.collection.add('products',_.sortBy(data.products,'name'));
// Berry.collection.add('solutions',_.sortBy(data.solutions,'solution_name'));
// Berry.collection.add('labs',data.labs);
gform.collections.add('lab_types',
[
    {'name':"abgs","label":"ABG"},
    {'name':"bmp","label":"BMP (Basic Metabolic Panel)"},
    {'name':"cmpanel","label":"Complete Metabolic Panel"},
    {'name':"cbc","label":"CBC with Differential"},
    {'name':"cmprofile","label":"CMP (Comprehensive Metabolic Profile)"},
    {'name':"ck","label":"Creatine kinase (CK) isoenzymes"},
    {'name':"electrolytes","label":"Electrolytes"},
    {'name':"lp","label":"Lipid Profile"},
    {'name':"pfp","label":"Liver Panel (Liver Function Panel)"},
    {'name':"urinalysis","label":"Urinalysis"},
    {'name':"btc","label":"Blood Type & Crossmatch"},
    {'name':"csf","label":"Cerebrospinal Fluid (CSF)"},
    {'name':"coagulation","label":"Coagulation Screen"}
]);

gform.collections.add('order_category',
[
    {"value":"Care","label":"Care"},
    {"value":"Consult","label":"Consult"},
    {"value":"Diagnostics","label":"Diagnostics"},
    {"value":"Dietetics","label":"Dietetics"},
    {"value":"Laboratory","label":"Laboratory"},
    {"value":"Procedure","label":"Procedure"},
    {"value":"Screening_Measurements","label":"Screening/Measurements"},
    {"value":"Speciality_Department","label":"Speciality Department"},
    {"value":"Supply_Device","label":"Supply/Device"},
    {"value":"Therapy","label":"Therapy"}
]);

gform.collections.add('order_status',
["Active","On Hold","Discontinued","Completed"]);

gform.collections.add('drug_category',
[
    "Home Medications","Inpatient Medications/Scheduled Medications","Infusion"
]);

gform.collections.add('drug_order_status',
[
    "Active","Discontinued","On Hold","Non-Verified/Processing"
]);


// gform.collections.add('frequency',
// [
//     {"value":"ac","label":"a.c. (Before food, before meals)"},
//     {"value":"pc","label":"p.c. (after food, after meals)"},
//     {"value":"am","label":"a.m (morning)"},
//     {"value":"pm","label":"p.m. (afternoon, evening)"},
//     {"value":"qhs","label":"Qhs (Bedtime)"},
//     {"value":"h","label":"h (at the hour of )"},
//     {"value":"qxh","label":"q”x”h (every__hour(s))"},
//     {"value":"d","label":"d (day)"},
//     {"value":"fx3","label":"For 3 days (FX3?)"},
//     {"value":"q","label":"Q (each, every)"},
//     {"value":"q4h","label":"Q4h (every 4 hours)"},
//     {"value":"a","label":"a (before)"},
//     {"value":"p","label":"p (after)"},
//     {"value":"prn","label":"prn (as needed)"},
//     {"value":"stat","label":"Stat (immediately)"},
//     {"value":"atc","label":"ATC (around the clock)"},
//     {"value":"daily","label":"Daily"},
//     {"value":"bid","label":"BID (twice daily)"},
//     {"value":"tid","label":"TID (three times daily)"},
//     {"value":"4xd","label":"Four times daily"},
//     {"value":"eod","label":"Every other day"},
//     {"value":"uad","label":"UAD (Use As directed)"},
//     {"value":"tg","label":"Tg, TG (until all taken)"},
//     {"value":"s","label":"s, sine (without)"}
// ]);




 















// gform.collection.add('category',[
//     "Laboratory",
//     "Diagnostics",
//     "Dietetics",
//     "Consult",
//     "Procedure",
//     "Supply/Device",
//     "Care",
//     "Specialty Department",
//     "Screening/Measurements",
//     "Therapy"
// ]);
gform.collections.add('frequency',[
    "As directed",
    "As directed PRN",
    "Daily",
    "BID",
    "TID",
    "TID AC",
    "TID PC",
    "QID",
    "Every other day",
    "Continuous",
    "M-W-F",
    "Now",
    "STAT",
    "Once",
    "Q5MIN",
    "Q10MIN",
    "Q15MIN",
    "Q30MIN",
    "Q2H",
    "Q3H",
    "Q4H",
    "Q6H",
    "Q8H",
    "Q12H",
    "Q18H",
    "Q24H",
    "Q36H",
    "PRN Q2H",
    "PRN Q6H",
    "PRN Q8H",
    "PRN Q12H",
    "PRN QHS",
    "QAC",
    "QAC, QHS",
    "QHS",
    "QPC",
    "TODAY",
    "TOMORROW",
    "WEEKLY"
]);
gform.collections.add('route',[
    "Arterial",
    "Both Ears (AU)",
    "Both Eyes (OU)",
    "By Mouth (PO)",
    "Epidural",
    "G-Tube",
    "Inhalation (INH)",
    "Intracardiac",
    "Intradermal",
    "Intramuscular (IM)",
    "Intrasynovial",
    "Intrathecal",
    "Intravenous (IV)",
    "Irrigation",
    "IV Piggyback (IVPB)",
    "IV Push (IVP)",
    "J Tube",
    "Left Ear (AS)",
    "Left Eye (OS)",
    "Nasal",
    "NG Tube",
    "PCA",
    "Percutanous",
    "Peritoneal",
    "Rectal",
    "Right Ear (AD)",
    "Right Eye (OD)",
    "Spinal",
    "Subcutanous (SQ)",
    "Sublingual (SL)",
    "Topical",
    "Trach",
    "Transdermal",
    "Urethral",
    "Vaginal"
]);

// gform.collection.add('lab_types',
// [
//     {'name':"abgs","label":"ABG"},
//     {'name':"bmp","label":"BMP (Basic Metabolic Panel)"},
//     {'name':"cmpanel","label":"Complete Metabolic Panel"},
//     {'name':"cbc","label":"CBC with Differential"},
//     {'name':"cmprofile","label":"CMP (Comprehensive Metabolic Profile)"},
//     {'name':"ck","label":"Creatine kinase (CK) isoenzymes"},
//     {'name':"electrolytes","label":"Electrolytes"},
//     {'name':"lp","label":"Lipid Profile"},
//     {'name':"pfp","label":"Liver Panel (Liver Function Panel)"},
//     {'name':"urinalysis","label":"Urinalysis"},
//     {'name':"btc","label":"Blood Type & Crossmatch"},
//     {'name':"csf","label":"Cerebrospinal Fluid (CSF)"},
//     {'name':"coagulation","label":"Coagulation Screen"}
// ]);