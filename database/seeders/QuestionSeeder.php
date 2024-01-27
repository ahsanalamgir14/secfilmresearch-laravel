<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
        	1=>"I feel anxious shortly before going to the dentist.",
        	2=>"I generally avoid going to the dentist because I find the experience unpleasant or distressing.",
        	3=>"I get nervous or edgy about upcoming dental visits.",
        	4=>"I think that something really bad would happen to me if I were to visit a dentist.",
        	5=>"I feel afraid or fearful when visiting the dentist.",
        	6=>"My heart beats faster when I go to the dentist",
        	7=>"I delay making appointments to go to the dentist.",
        	8=>"I often think about all the things that might go wrong prior to going to the dentist.",

            9=>"Going to the dentist is actively avoided or else endured with intense fear or anxiety.",
            10=>"My fear of going to the dentist has been present for at least 6 months.",
            11=>"My fear, anxiety or avoidance of going to the dentist significantly affects my life in some way (dental pain, avoid eating some foods, embarrassed or self-conscious about appearance of teeth or mouth, etc).	1",
            12=>"I am afraid of going to the dentist because I am concerned I may have a panic attack (abrupt fear with sweating, pounding heart, fear of dying or losing control, chest pain etc.)",
            13=>"I am afraid of going to the dentist because I am generally highly self-conscious or concerned about being watched or judged in social situations.",

            14=>"Painful or uncomfortable procedures",
            15=>"Feeling embarrassed or ashamed",
            16=>"Not being in control of what is happening",
            17=>"Feeling sick, queasy or disgusted",
            18=>"Numbness caused by the anesthetic",
            19=>"Not knowing what the dentist is going to do",
            20=>"The cost of dental treatment",
            21=>"Needles or injections",
            22=>"Gagging or choking",
            23=>"Having an unsympathetic or unkind dentist",
        ];

        foreach ($questions as $key => $value) {
            DB::table('questions')->insert([
                'question' => $value,
                'question_key' => $key,

                ]);
        }
    }
}

