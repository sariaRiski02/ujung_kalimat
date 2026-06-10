<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class workspaceController extends Controller
{


    public function __construct(
        protected ArticleService $service
        
    ){}

    public function dashboard(){
        return view('pages.pageWorkspace.dashboard');
    }

    public function write(){
        return view('pages.pageWorkspace.write');
    }



    public function store(StoreArticleRequest $request){
        $this->service->store($request);
    }



    public function articles(){
        return view('pages.pageWorkspace.listArticle');
    }
    public function monetization(){
        return view('pages.pageWorkspace.monetization');
    }
    public function following(){
        return view('pages.pageWorkspace.following');
    }

    public function followers(){
        return view('pages.pageWorkspace.followers');
    }

    public function profile(){
        return view('pages.pageWorkspace.profile');
    }

    public function analytics(){
        return view('pages.pageWorkspace.analyticDetailArticle');
    }

    
    
}
