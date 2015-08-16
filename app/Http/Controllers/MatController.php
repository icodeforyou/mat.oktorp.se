<?php

namespace App\Http\Controllers;

use App\Mat;
use App\VeckoMeny;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MatController extends Controller
{

    /**
     * @var Mat
     */
    private $Mat;
    /**
     * @var VeckoMeny
     */
    private $veckoMeny;
    /**
     * @var Carbon
     */
    private $carbon;

    public function __construct(Mat $Mat, VeckoMeny $veckoMeny, Carbon $carbon) {
        $this->Mat = $Mat;
        $this->veckoMeny = $veckoMeny;
        $this->carbon = $carbon;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Kolla initialt om det finns en meny för denna veckan
        // finns det ingen meny, slumpa fram en veckomeny för denna veckan.
        // Är vi på helgen så tar vi och skapar upp nästa veckas meny
        if ($this->carbon->isWeekend()) {
            $nextWeek = $this->carbon->addWeek(1);

            // Kolla så det inte redan finns en meny för nästa vecka
            if (!$this->veckoMeny->where("vecka", $nextWeek->weekOfYear)->first()) {
                $this->randomizeWeek($nextWeek->weekOfYear);
            }
            $week = $nextWeek->weekOfYear;
        } else {
            $week = $this->carbon->weekOfYear;
            if (!$weekMenu = $this->veckoMeny->where("vecka", $week)->first()) {
                $this->randomizeWeek($week);
            }
        }

        // finns det en meny för denna veckan visa dagens maträtt
        // och resten av veckans maträtter
        return View("welcome")->with([
            "veckoMeny" => $this->veckoMeny->with("matratt")->where("vecka", $week)->get(),
            "week" => $week,
            "today" => $this->carbon->toDateString()
        ]);
    }

    /**
     * @param $week
     * @return mixed
     */
    public function randomizeWeek($week)
    {
        // Ladda alla maträtter som inte blivit serverade
        // eller blivit serverade längre bak i tiden än en vecka
        $matratter = $this->Mat;
        $sunday = $this->carbon->startOfWeek()->subDay(1);

        for($i = 0; $i < 5; $i++) {

            $datum = $sunday->addDay(1);
            $randomMatratt = $matratter
                                ->where("serverat_datum", "<", $this->carbon->now()->subWeek(1)->toDateTimeString())
                                ->orWhere("serverat_datum", '=', "0000-00-00 00:00:00")
                                ->get()->random(1);
            $randomMatratt->serverat_datum = $datum->toDateTimeString();
            $randomMatratt->save();

            $meny = new $this->veckoMeny;
            $meny->mat_id = $randomMatratt->id;
            $meny->datum = $datum;
            $meny->vecka = $week;
            $meny->save();

        }
    }
}
