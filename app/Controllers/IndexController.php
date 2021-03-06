<?php

namespace App\Controllers;

    use App\App;
    use App\Models\User;
    use App\Models\Model;
    use App\Models\Article;
    use App\Models\ModelCollection;
    use App\Models\UserCollection;
    use App\Http\Request\Request;

    class IndexController extends BaseController {

        public function test() {
            return view("test");
        }

        public function index() {
            
            $users = User::all();
            $article = Article::where(['id' => 1]);

            return view('index')
                ->withUsers($users)
                ->withArticle($article);

        }

        public function about() {
            
            $article = Article::find(1);
            $users = User::all();

            return view('index')
                ->withUsers($users)
                ->withArticle($article);

        }

        public function testPost(Request $request) {

            $valid = $this->validate($request, [
                'name' => 'required|integer'
            ]);

            if (!$valid) {
                return error("500", "validation fail");
            }

            return $request->input();

        }

    }
