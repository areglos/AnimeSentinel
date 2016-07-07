<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Show;
use App\Video;
use App\AnimeSentinel\MyAnimeList;

class AnimeController extends Controller
{
  protected function recentShows($limit) {
    $recent = Video::orderBy('uploadtime', 'desc')
              ->take($limit)->with('show')->get();
    return $recent;
  }

  /**
   * Show the home page.
   *
   * @return \Illuminate\Http\Response
   */
  public function home() {
    return view('home', [
      'recent' => $this->recentShows(32)
    ]);
  }

  /**
   * Show the 'recently uploaded' page.
   *
   * @return \Illuminate\Http\Response
   */
  public function recent() {
    return view('anime.recent', [
      'recent' => $this->recentShows(64)
    ]);
  }

  /**
   * Show an empty search results page.
   *
   * @return \Illuminate\Http\Response
   */
  public function searchEmpty() {
    return view('anime.search', [
      'results' => []
    ]);
  }

  /**
   * Show the search page with results.
   *
   * @return \Illuminate\Http\Response
   */
  public function searchPost(Request $request) {
    $this->validate($request, [
      'query' => ['required', 'min:3']
    ]);
    $results = MyAnimeList::getSearch($request->query);

    // TODO: expand known anime

    return view('anime.search', [
      'results' => $results
    ]);
  }
}