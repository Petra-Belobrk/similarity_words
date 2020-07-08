<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

   
    public function calculate(Request $request) {
       
       $picked = $request->picked;

       switch ($picked) {
        case 'Bacon':
            $url = 'https://baconipsum.com/api/?type=all-meat&sentences=2&start-with-lorem=1';
            break;
        case 'Pirate':
            $url = 'https://pirateipsum.me/includes/makeipsum.php';
            break;
    }
    $client = new Client();
    $getBody = $client->request('GET', $url)->getBody();
    
    
        while (strpos($getBody, "  ")!==false) {
            $request = str_replace("  ", " ", $getBody);
        }
        while (strpos($getBody, "[\"")!==false && strpos($getBody, "\"]")!==false) {
            $getBody = str_replace("[\"", "", $getBody);
            $getBody = str_replace("\"]", "", $getBody);
        }

        $ex = explode(".", $getBody);
        $first = $ex[0];
        $second = $ex[1];

        return $this->randomize($first, $second); 

    }

    public function userInput(Request $request) {
        $data = $request->validate([
            'sentences' => 'required|min:2',
        ]);
        $sentences = $data['sentences'];

        while (strpos($sentences, "  ")!==false) {
            $sentences = str_replace("  ", " ", $sentences);
        }
        $ex = explode(".", $sentences);
        if(count($ex) <=1) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'sentences' => ['Must contain at least two sentences'],
             ]);
             throw $error;
        }
        $first = $ex[0];
        $second = $ex[1];

        return $this->randomize($first, $second);
        
    }

    public function randomize($first, $second) {
        
        $array1 = explode(" ", $first);
        $array2 = explode(" ", $second);

        $lenght1 = count($array1);
               
        $matches = 0;
    
        //find matching words
        foreach($array1 as $word) {
            if (in_array($word, $array2))
                $matches++;
        }

        return response()->json([
            'percentage' => number_format((($matches / $lenght1) * 100), 2),
            'firstSentance' => $first,
            'secondSentance' => $second
        ]);
       
    }
}
