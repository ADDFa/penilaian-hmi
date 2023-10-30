<?php

namespace App\Http\Controllers;

class AccumulativeUserScore extends Controller
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
        $this->handle();
    }

    private function percent($val, $percentage)
    {
        $percent = $percentage / 100;
        return $val * $percent;
    }

    private function handle()
    {
        $this->accumulativeKepribadian();
        $this->accumulativeIbadah();
        $this->accumulativeAfective();

        $this->accumulativeMiddleTest();
        $this->accumulativeCognitive();

        $this->accumulativeActive();
        $this->accumulativeLeader();
        $this->accumulativePsicomotoric();
    }

    private function accumulativeKepribadian()
    {
        $data = $this->result;
        $accumulative = $data->tingkah_laku + $data->tata_bahasa + $data->pakaian + $data->ketepatan_waktu;
        $accumulative /= 4;

        $this->result->akumulatif_kepribadian = $accumulative;
    }

    private function accumulativeIbadah()
    {
        $ibadahN2 = $this->result->membaca_alquran + $this->result->ceramah_agama;
        $accumulative = $this->result->sholat + $ibadahN2;
        $accumulative /= 2;

        $this->result->ibadah_N1 = $this->result->sholat;
        $this->result->ibadah_N2 = $ibadahN2;
        $this->result->akumulatif_ibadah = $accumulative;
    }

    private function accumulativeAfective()
    {
        $accumulative = $this->result->akumulatif_kepribadian + $this->result->akumulatif_ibadah;
        $accumulative /= 2;

        $this->result->akumulatif_afektif = $accumulative;
    }

    private function accumulativeMiddleTest()
    {
        $accumulative = collect($this->result->middletest)->sum("score");
        $accumulative /= 10;

        $this->result->akumulatif_middletest = $accumulative;
    }

    private function accumulativeCognitive()
    {
        $accumulative = $this->result->akumulatif_middletest + $this->result->pre_test + $this->result->post_test;
        $accumulative /= 3;

        $this->result->akumulatif_kognitif = $accumulative;
    }

    private function accumulativeActive()
    {
        $accumulative = collect($this->result->liveliness)->sum("score");
        $accumulative /= 10;

        $this->result->akumulatif_keaktifan = $accumulative;
    }

    private function accumulativeLeader()
    {
        $accumulative = $this->result->penguasaan_kelompok + $this->result->problem_solving;
        $accumulative /= 2;

        $this->result->akumulatif_kepemimpinan = $accumulative;
    }

    private function accumulativePsicomotoric()
    {
        $accumulative = $this->result->akumulatif_keaktifan + $this->result->akumulatif_kepemimpinan;
        $accumulative /= 2;

        $this->result->akumulatif_psikomotorik = $accumulative;
    }

    public function score()
    {
        $scoreAfektif = $this->percent($this->result->akumulatif_afektif, 50);
        $this->result->skor_afektif = $scoreAfektif;

        $scoreCognitive = $this->percent($this->result->akumulatif_kognitif, 30);
        $this->result->skor_kognitif = $scoreCognitive;

        $scorePsicomotoric = $this->percent($this->result->akumulatif_psikomotorik, 20);
        $this->result->skor_psikomotorik = $scorePsicomotoric;

        $this->result->score = $scoreAfektif + $scoreCognitive + $scorePsicomotoric;
        return $this->result;
    }
}
