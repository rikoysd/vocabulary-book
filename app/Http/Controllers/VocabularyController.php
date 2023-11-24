<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\Vocabulary;
use App\Models\Account;
use App\Models\Example;
use App\Models\Meaning;

class VocabularyController extends Controller
{
    public function register(Request $request) {
        $rules = [
            'mail' => ['required', 'email:filter,dns'],
            'password' => ['required',Password::min(8)->letters()->mixedCase()->numbers()],
            'password_confirmation' => ['required', 'same:password']
        ];
        $this -> validate($request, $rules);
        $param = [
            'mail' => $request -> mail,
            'password' => $request -> password
        ];
        DB::table('accounts') -> insert($param);
        return view('app');
    }

    // public function login(Request $request) {
        
    // }

    public function add(Request $request) {
        $vocabulary = [
            'name' => $request -> name,
            'account_id' => $request -> account_id
        ];
        DB::table('vocabularies') -> insert($vocabulary);
        $latestRecord = DB::table('vocabularies') -> latest('id')->first();
        $latestId = '';
        foreach ($request -> meanings as $item) {
            foreach($item as $key => $value) {
                if ($key === 'wordMeaning') {
                    $meaning = [
                        'wordMeaning' => $value,
                        'vocabulary_id' => $latestRecord -> id
                    ];
                    DB::table('meanings') -> insert($meaning);
                }
                global $latestId;
                $latestId = DB::table('meanings') -> latest('id') -> first() -> id;
                $example = [
                    'sentence' => '',
                    'meaning' => '',
                    'meaning_id' => 0
                ];
                $list = [];
                if($key === 'examples') {
                    global $example;
                    global $list;
                    $list = $value;
                    foreach($list as $item) {
                        foreach($item as $key => $value) {
                            if($key === 'sentence') $example['sentence'] = $value;
                            if($key === 'meaning') $example['meaning'] = $value;
                            if($key === 'meaning_id') $example['meaning_id'] = $latestId;
                        }
                        DB::table('examples') -> insert($example);
                    }
                }
            }
        }
        return view('app');
    }

    public function getList(Request $request) {
        $items = Vocabulary::with('meanings.examples')
            -> where('vocabularies.account_id', '=', Account::find($request -> account_id) -> id)
            -> get();
        return $items;
    }

    public function getExampleList(Request $request) {
        $items = Account::find($request -> account_id)
            -> join('vocabularies', 'accounts.id', '=', 'vocabularies.account_id')
            -> join('meanings', 'vocabularies.id', '=', 'meanings.vocabulary_id')
            -> join('examples', 'meanings.id', '=', 'examples.meaning_id')
            -> get();
        return $items;
    }

    public function getItem(Request $request) {
        $items = Vocabulary::with('meanings.examples')
            -> where('vocabularies.account_id', '=', Account::find($request -> account_id) -> id)
            -> where('vocabularies.id', '=', $request -> vocabulary_id)
            -> get();
        return $items;
    }

    public function update(Request $request) {
        $item = Vocabulary::where('vocabularies.account_id', '=', Account::find($request -> account_id) -> id)
            -> where('vocabularies.id', '=', $request -> id)
            -> first();
        $item -> update([
            'id' => $request -> id,
            'name' => $request -> name,
            'account_id' => $request -> account_id
        ]);
        foreach($request -> meanings as $meaning) {
            $record = Meaning::where('meanings.vocabulary_id', '=', $request -> id)
                -> where('meanings.id', '=', $meaning['id'])
                -> first();
            if(isset($record)) {
                // $recordがnullではない時
                $record -> update([
                    'id' => $meaning['id'],
                    'wordMeaning' => $meaning['wordMeaning'],
                    'vocabulary_id' => $meaning['vocabulary_id']
                ]);
            } else {
                DB::table('meanings') -> insert([
                    'wordMeaning' => $meaning['wordMeaning'],
                    'vocabulary_id' => $meaning['vocabulary_id']
                ]);
            }
            if(count($meaning['examples']) > 0) {
                foreach($meaning['examples'] as $item) {
                    $exampleList = Example::where('examples.meaning_id', '=', $meaning['id'])
                        -> where('examples.id', '=', $item['id'])
                        -> get();
                    if(count($exampleList) > 0) {
                        // $exampleリストが存在するとき
                        foreach($exampleList as $example) {
                            $record = Example::find($example['id']);
                            if(isset($record)) {
                                $record -> update([
                                    'sentence' => $item['sentence'],
                                    'meaning' => $item['meaning'],
                                    'meaning_id' => $meaning['id']
                                ]);
                            } else {
                                DB::table('examples') -> insert([
                                    'sentence' => $item['sentence'],
                                    'meaning' => $item['meaning'],
                                    'meaning_id' => $meaning['id']
                                ]);
                            }
                        }
                    } else {
                        // 新規追加
                        DB::table('examples') -> insert([
                            'sentence' => $item['sentence'],
                            'meaning' => $item['meaning'],
                            'meaning_id' => $meaning['id']
                        ]);
                    }
                }
            }
        }
        return view('app');
    }

    public function delete(Request $request) {
        Vocabulary::with('meanings.examples')
            -> where('vocabularies.account_id', '=', Account::find($request -> account_id) -> id)
            -> where('vocabularies.id', '=', $request -> word_id)
            -> delete();
        return view('app');
    }

    public function deleteExample(Request $request) {
        Example::where('examples.id', '=', $request -> id)
            -> delete();
        return view('app');
    }

    public function deleteMeaning(Request $request) {
        Example::where('examples.meaning_id', '=', $request -> id)
            -> delete();
        Meaning::where('meanings.id', '=', $request -> id)
            -> delete();
        return view('app');
    }

    public function search(Request $request) {
        $items = Vocabulary::with('meanings.examples')
            -> where('vocabularies.account_id', '=', Account::find($request -> account_id) -> id)
            -> where('name', 'LIKE', '%'.$request -> word.'%')
            -> get();
        return $items;
    }

    public function sort(Request $request) {

    }
}