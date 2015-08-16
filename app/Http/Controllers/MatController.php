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
     * @param $week
     * @return Response
     */
    public function index($week = null)
    {
        // Om vi inte har vecka att utgår ifrån
        if (!$week) {
            // Om vi tittar på helgen, visa nästa veckas meny
            if ($this->carbon->isWeekend()) {
                $week = $this->carbon->addWeek(1)->weekOfYear;
            } else {
                // denna veckan
                $week = $this->carbon->weekOfYear;
            }
        }

        if (!$this->veckoMeny->where("vecka", $week)->first()) {
            $this->randomizeWeek($week);
        }

        // Orka locale
        $veckodagar = [
            "Monday" => "Måndag",
            "Tuesday" => "Tisdag",
            "Wednesday" => "Onsdag",
            "Thursday" => "Torsdag",
            "Friday" => "Fredag"
        ];

        // finns det en meny för denna veckan visa dagens maträtt
        // och resten av veckans maträtter
        return View("welcome")->with([
            "veckoMeny" => $this->veckoMeny->with("matratt")->where("vecka", $week)->get(),
            "week" => $week,
            "weekdays" => $veckodagar,
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
        $monday = $this->carbon->startOfWeek();

        $weekDiff = $week - $monday->weekOfYear;

        if ($weekDiff < 0) {
            $monday = $monday->subWeeks(abs($weekDiff));
        } else {
            $monday = $monday->addWeeks($weekDiff);
        }

        $sunday = $monday->subDay(1);

        for($i = 0; $i < 5; $i++) {

            $datum = $sunday->addDay(1);

            $randomMatratt = $matratter
                                ->where("serverat_datum", "<", $datum->toDateTimeString())
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
