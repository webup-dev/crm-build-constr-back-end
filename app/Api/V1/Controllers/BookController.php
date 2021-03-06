<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\Book;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Books
 */
class BookController extends Controller
{
    use Helpers;

    /**
     * Get index of books
     *
     * @response 200 [{
     *  "id": 1,
     *  "name": "Department 1",
     *  "description": "Description 1",
     *  "parent_id": null,
     *  "created_at": "2019-06-24 07:12:03",
     *  "updated_at": "2019-06-24 07:12:03"
     * },
     * {
     *  "id": 4,
     *  "name": "Department 2",
     *  "description": "Description 2",
     *  "parent_id": 1,
     *  "created_at": "2019-06-24 07:12:03",
     *  "updated_at": "2019-06-24 07:12:03"
     * }]
     * @response 404 {
     *  "message": "Departments not found."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $books = Book::all();
        $data  = $books->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Books retrieved successfully.'
        ];
        return response()->json($response, 200);
    }

    public function indexOfUser()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        return $currentUser
            ->books()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }

    public function store(Request $request)
    {
        $rules = array(
            'title'       => 'required|string',
            'author_name' => 'required|string',
            'pages_count' => 'required|integer'
        );

        $messages = array(
            'title.required'       => 'Please enter a title.',
            'title.string'         => 'Title must be a string',
            'author_name.required' => 'Please enter Author Name.',
            'author_name.string'   => 'Author Name must be a string',
            'pages_count.required' => 'Please enter Pages Count.',
            'pages_count.integer'  => 'Pages Count must be integer.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors   = $messages->all();
            return $errors;
            $string = '';
            foreach ($errors as $error) {
                $string += ' ' . $error;
            }
            return $this->response->error($string, 500);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = new Book;

        $book->title       = $request->get('title');
        $book->author_name = $request->get('author_name');
        $book->pages_count = $request->get('pages_count');
        $book->user_id     = $currentUser->id;

        if ($book->save()) {
            $response = [
                'success' => true,
                'message' => 'New Book created successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('Could not create book', 500);
        }
    }

    public function show($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = $currentUser->books()->find($id);

        $user_name       = User::find($book->user_id)->name;
        $book->user_name = $user_name;

        if (!$book)
            throw new NotFoundHttpException;

        $data = $book->toArray();

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Books retrieved successfully.'
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = Book::whereId($id)->first();
        if (!$book){
            $response = [
                'success' => false,
                'message' => 'Book does not exist.'
            ];

            return response()->json($response, 401);
        }

        $book->fill($request->all());

        if ($book->save()) {
            $response = [
                'success' => true,
                'message' => 'Books updated successfully.'
            ];

            return response()->json($response, 200);
        } else {
            return $this->response->error('could_not_update_book', 500);
        }
    }

    public function destroy($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $book = $currentUser->books()->find($id);

        if (!$book)
            throw new NotFoundHttpException;

        if ($book->delete()) {
            $response = [
                'message' => 'Books retrieved successfully.'
            ];
            return response()->json($response, 200);
        } else {
            return $this->response->error('could_not_delete_book', 500);
        }
    }
}
