<?php

namespace App\Http\Controllers;

use App\Models\TestBook;
use Illuminate\Http\Request;

class TestBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = TestBook::all();
        return response()->json(
            $books,
            200,
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = TestBook::create($request->all());
        return response()->json(
            $book,
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = [
            "titel" => $request->title,
            "author" => $request->author,
        ];

        $book = TestBook::where("id", $id)->update($update);

        if ($book) {
            $updateBook = TestBook::find($id);
            return response()->json(
                $updateBook,
                200,
            );
        } else {
            return response()->json(
                [
                    "message" => "指定された書籍は存在しません。"
                ],
                404,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = TestBook::where("id", $id)->delete();
        if ($book) {
            return response()->json(
                [
                    "message" => "指定した書籍データは正常に削除されました。",
                ],
                200
            );
        } else {
            return response()->json(
                [
                    "message" => "指定された書籍データが存在しません。",
                ],
                404
            );
        }
    }
}
