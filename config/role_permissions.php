<?php

/* This file contains all of the roles as well as their permissions within the ePATIENT App */

return [
    'roles' => [
        'RPH' => ['id'=>'RPH','title'=>'Registered Pharmacist','permissions'=>'pharmacist'],
        'RA' => ['id'=>'RA','title'=>'Registered Nurse','permissions'=>'nurse'],
        'MA' => ['id'=>'MA','title'=>'Medical Assistant','permissions'=>'nurse'],
        'LPN' => ['id'=>'LPN','title'=>'Licensed Practical Nurse','permissions'=>'nurse'],
        'MD' => ['id'=>'MD','title'=>'Medical Doctor','permissions'=>'provider'],
        'DO' => ['id'=>'DO','title'=>'Doctor of Osteopathic Medicine','permissions'=>'provider'],
        'NP' => ['id'=>'NP','title'=>'Nurse Practitioner','permissions'=>'provider'],
        'PA' => ['id'=>'PA','title'=>'Physician Assistant','permissions'=>'provider'],
        'DDS' => ['id'=>'DDS','title'=>'Doctor of Dental Surgery','permissions'=>'provider'],
    ],'permissions' => [
        'pharmacist' => [
            'alerts' =>
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'cardiac' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'diagnostics' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'gi' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'gu' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'history_physical' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'intake_output' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'iv' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>'verify'],
            'labs' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'medical_admin_record' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'mental' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'musculoskeletal' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'neuro' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'notes' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>true],
            'orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pain' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'patient_info' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'prescription_orders' => 
                ['read'=>true,'create'=>true,'update'=>false,'delete'=>false,'action'=>false],
            'problems' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pysician_orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'respiratory' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'skin' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'vital_signs' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
        ],'nurse' => [
            'alerts' =>
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'cardiac' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'diagnostics' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'gi' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'gu' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'history_physical' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'intake_output' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'iv' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>'administer'],
            'labs' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'medical_admin_record' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'mental' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'musculoskeletal' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'neuro' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'notes' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>true],
            'orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pain' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'patient_info' => 
                ['read'=>true,'create'=>true,'update'=>false,'delete'=>false,'action'=>false],
            'prescription_orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'problems' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pysician_orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'respiratory' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'skin' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'vital_signs' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
        ],'provider' => [
            'alerts' =>
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'cardiac' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'diagnostics' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'gi' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'gu' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'history_physical' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'intake_output' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'iv' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>'order'],
            'labs' => 
                ['read'=>true,'create'=>true,'update'=>false,'delete'=>false,'action'=>false],
            'medical_admin_record' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'mental' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>false],
            'musculoskeletal' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'neuro' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'notes' => 
                ['read'=>true,'create'=>true,'update'=>true,'delete'=>false,'action'=>true],
            'orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pain' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'patient_info' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'prescription_orders' => 
                ['read'=>true,'create'=>true,'update'=>false,'delete'=>false,'action'=>false],
            'problems' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'pysician_orders' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'respiratory' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'skin' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
            'vital_signs' => 
                ['read'=>true,'create'=>false,'update'=>false,'delete'=>false,'action'=>false],
        ]


    ],
];